<?php

namespace Orlyapps\LaravelFirebaseNotifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Orlyapps\LaravelFirebaseNotifications\Channels\PushChannel;

class TextNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $body;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body, $url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PushChannel::class];
    }

    /**
     * Get the voice representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return VoiceMessage
     */
    public function toPush($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'data' => [],
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'data' => []
        ];
    }
}
