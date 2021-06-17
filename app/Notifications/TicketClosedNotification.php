<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketClosedNotification extends Notification
{
    use Queueable;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
                'ticket' => $this->ticket
        ];
    }
}
