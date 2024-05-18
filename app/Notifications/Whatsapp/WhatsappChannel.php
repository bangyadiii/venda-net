<?php

namespace App\Notifications\Whatsapp;

use Illuminate\Notifications\Notification;
use Silvanix\Wablas\Message;
use App\Notifications\Whatsapp\WhatsappMessage;
use Illuminate\Support\Facades\Log;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed        $notifiable
     * @param Notification $notification
     *
     * @throws FailedToSendNotification
     * @return null|array
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if ($notification instanceof ToWhatsappNotification) {
            /**
             * @var WhatsappMessage $message
             */
            $message = $notification->toWhatsapp($notifiable);

            if (is_string($message)) {
                $message = WhatsappMessage::create($message);
            }

            $payload = $notification->toArray($notifiable);
            // Send notification to the $notifiable instance...
            $send = new Message();

            $sentText = $send->single_text($payload['to'], $payload['message']);

            Log::info($sentText);
        }
    }
}
