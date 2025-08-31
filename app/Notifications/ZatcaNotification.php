<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ZatcaNotification extends Notification
{
    use Queueable;
    public $data;
    public function __construct($data)
    {
        $data = $data;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'content' => $this->data['body']
        ];
    }
}
