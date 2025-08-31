<?php

namespace App\Notifications;

use App\Helpers\Qs;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class SendTenantEvent extends Notification
{
    /**
     * Create a new notification instance.
     */
    protected $event;
    public function __construct($event)
    {

        // Log::info('SendTenantEvent for tenant created');
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (Qs::is_serialized($notifiable->is_notifiable) || is_array(unserialize($notifiable->is_notifiable))) {
            if (unserialize($notifiable->is_notifiable)['status'] === 1) {
                $channels = FcmChannel::class;
            }
        }

        return $channels;
    }

    public function toFcm(): FcmMessage
    {
        $data = [
            'title' => "Tenant action status",
            'body' => mb_strimwidth($this->event->data["msg"], 0, 50, "..."),
            'icon' => Qs::getAppIcon(),
            'type' => 'tenancy',
            'url' => $this->event->data["url"],
        ];

        return (new FcmMessage())
            ->data($data)
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                        'sound' => 'default',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default'
                        ],
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
    }
}
