<?php

namespace App\Http\Controllers;

use App\Enums\BillStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Jobs\UnisolateCustomerJob;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sawirricardo\Midtrans\Dto\TransactionStatus;
use Sawirricardo\Midtrans\Laravel\Notification;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MidtransCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // testing purpose
        if ($request->transaction_id == '513f1f01-c9da-474c-9fc9-d5c64364b709') {
            return response()->json(['status' => 'success', 'message' => 'Callback received']);
        }

        $notification = Notification::make($request->all());
        $notification
            ->whenSettlement(function (TransactionStatus $notification) {
                $this->updatePaymentStatus($notification, PaymentStatus::SUCCESS);
            })
            ->whenPending(function (TransactionStatus $notification) {
                // create a new payment and payment log
                $billId = explode('.', $notification->order_id)[1];
                $bill = Bill::query()->findOrFail($billId);
                $payment = Payment::query()->where('bill_id', $bill->id)
                    ->first();

                if (!$payment) {
                    $payment = Payment::create([
                        'bill_id' => $bill->id,
                        'amount' => $bill->total_amount,
                        'payment_date' => \now(),
                        'status' => PaymentStatus::PENDING,
                        'method' => PaymentMethod::MIDTRANS,
                    ]);
                }

                /**
                 * @var ?PaymentLog $log
                 */
                $log = PaymentLog::query()->where('payment_id', $payment->id)
                    ->where('transaction_id', $notification->transaction_id)
                    ->first();

                if ($log) {
                    $log->touch();
                    return;
                }

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'transaction_id' => $notification->transaction_id,
                    'status_code' => $notification->status_code,
                    'log_message' => \json_encode($notification->toArray()),
                ]);
            })
            ->whenDenied(function (TransactionStatus $notification) {
                $this->updatePaymentStatus($notification, PaymentStatus::FAILED);
            })
            ->whenExpired(function (TransactionStatus $notification) {
                $this->updatePaymentStatus($notification, PaymentStatus::FAILED);
            })
            ->whenCancelled(function (TransactionStatus $notification) {
                $this->updatePaymentStatus($notification, PaymentStatus::FAILED);
            })
            ->listen();


        return response()->json(['status' => 'success', 'message' => 'Callback received']);
    }

    /**
     * Update Payment Status
     *
     * @param TransactionStatus $notification
     * @param string $status
     * @return void
     */
    private function updatePaymentStatus(TransactionStatus $notification, PaymentStatus $status)
    {
        $billId = explode('.', $notification->order_id)[1];
        $bill = Bill::query()->with('customer')->findOrFail($billId);
        $payment = Payment::query()->where('bill_id', $bill->id)
            ->firstOrFail();

        $serverKey = \config('midtrans.is_production') ?
            \config('midtrans.server_key') : \config('midtrans.sandbox_server_key');

        if (!$this->checkSignature($notification->signature_key, $notification->order_id, $notification->status_code, $notification->gross_amount, $serverKey)) {
            throw new UnauthorizedHttpException('Unauthorized');
        }

        DB::transaction(function () use ($payment, $bill, $status, $notification) {
            $payment->fill([
                'status' => $status,
                'payment_date' => now(),
            ])->save();

            $billStatus = $status ===  PaymentStatus::SUCCESS ? BillStatus::PAID : BillStatus::UNPAID;
            $bill->fill(['status' => $billStatus])->save();

            $log = PaymentLog::query()->updateOrCreate([
                'payment_id' => $payment->id,
                'transaction_id' => $notification->transaction_id,
            ], [
                'status_code' => $notification->status_code,
                'log_message' => \json_encode($notification->toArray()),
            ]);

            \info('Payment log created', $log->toArray());
        });

        if ($status === PaymentStatus::SUCCESS) {
            \dispatch(new UnisolateCustomerJob($bill->customer));
        }
    }

    /**
     * Check Midtrans Signature
     *
     * @param string $signature
     * @param string $orderId
     * @param string $statusCode
     * @param string $grossAmount
     * @param string $serverKey
     * @return boolean
     */
    private function checkSignature(string $signature, string $orderId, string $statusCode, string $grossAmount, string $serverKey): bool
    {
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($signature !== $expectedSignature) {
            return false;
        }
        return true;
    }
}
