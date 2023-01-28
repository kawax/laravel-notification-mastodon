<?php

namespace Revolution\Laravel\Notification\Mastodon;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

use Revolution\Mastodon\Facades\Mastodon;

class MastodonChannel
{
    /**
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        /**
         * @var MastodonMessage $message
         */
        $message = $notification->toMastodon($notifiable);

        $status = $message->status;

        if (empty($status)) {
            return;
        }

        $domain = $message->domain;

        if (empty($domain)) {
            $domain = $notifiable->routeNotificationFor('mastodon-domain');
        }

        if (empty($domain)) {
            $domain = Config::get('services.mastodon.domain');
        }

        if (empty($domain)) {
            return;
        }

        $token = $message->token;

        if (empty($token)) {
            $token = $notifiable->routeNotificationFor('mastodon-token');
        }

        if (empty($token)) {
            $token = Config::get('services.mastodon.token');
        }

        if (empty($token)) {
            return;
        }

        $options = $message->options;

        Mastodon::domain($domain)
                ->token($token)
                ->createStatus($status, $options);
    }
}
