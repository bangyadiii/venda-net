<?php

namespace App\Notifications\Whatsapp;

interface ToWhatsappNotification
{
    /**
     * Get the mail representation of the notification.
     */
    public function toWhatsapp(object $notifiable): WhatsappMessage;
    public function toArray(object $notifiable): array;
}
