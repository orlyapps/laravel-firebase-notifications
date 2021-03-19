<?php

namespace Orlyapps\LaravelFirebaseNotifications\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\ServiceAccount;

class PushChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toPush($notifiable);

        foreach ($notifiable->pushTokens as $token) {
            \Log::info('Send Push Notification to ' . $notifiable->email . ' with token' . $token);
            try {
                $this->push($message['title'], $message['body'], $message['url'], $token->token, $message['data']);
            } catch (NotFound $e) {
                $token->delete();
            } catch (InvalidMessage $e) {
                $token->delete();
            } catch (MessagingException $e) {
                \Log::error($e);
                \Log::error('Failed to send Message to ' . $notifiable->email . ' with token' . $token);
            } catch (\Throwable $e) {
                \Log::error($e);
            }
        }

        // Send notification to the $notifiable instance...
    }

    private function push($title, $body, $url, $deviceToken, $data)
    {
        if (env('FCM_SERVICE') === null) {
            $serviceAccount = ServiceAccount::fromValue(config('services.fcm.json_file_path'));
        } else {
            $serviceAccount = ServiceAccount::fromValue(env('FCM_SERVICE'));
        }

        $firebase = (new Firebase\Factory())->withServiceAccount($serviceAccount);
        $messaging = $firebase->createMessaging();

        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => array_merge($data, [
                'url' => $url
            ]),
        ]);
        $message = $message->withAndroidConfig(AndroidConfig::fromArray([
            'priority' => 'high',
        ]));
        $message = $message->withWebPushConfig(WebPushConfig::fromArray([
            'notification' => [
                'icon' => 'https://meintouchtomorrow.de/assets/icons/apple-icon-72x72-dunplab-manifest-15615.png',
            ],
            'fcm_options' => [
                'link' => $url
            ]
        ]));

        $message = $message->withApnsConfig(ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'badge' => 1,
                ],
            ],
        ]));

        $messaging->validate($message);
        $messaging->send($message);
    }
}
