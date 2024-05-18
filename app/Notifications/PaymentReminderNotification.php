<?php

namespace App\Notifications;

use App\Notifications\Whatsapp\ToWhatsappNotification;
use App\Notifications\Whatsapp\WhatsappChannel;
use App\Notifications\Whatsapp\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
implements ToWhatsappNotification, ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private string $phoneNumber, private string $message)
    {
        //
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsappChannel::class];
    }

    /**
     * Get the message representation of the notification.
     */
    public function toWhatsapp(object $notifiable): WhatsappMessage
    {
        return WhatsappMessage::create()
            ->to($this->phoneNumber)
            ->content($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'to' => $this->phoneNumber,
            'message' => $this->message,
        ];
    }
}
