<?php

namespace Revolution\Laravel\Notification\Mastodon;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Revolution\Mastodon\Facades\Mastodon;

class MastodonChannel
{
    /**
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        /**
         * @var MastodonMessage $message
         */
        $message = $notification->toMastodon($notifiable);

        $status = $message->status;

        if (empty($status)) {
            return null;
        }

        /**
         * @var MastodonRoute $route
         */
        $route = $notifiable->routeNotificationFor('mastodon');

        $domain = $route?->domain;

        if (empty($domain)) {
            $domain = $message->domain;
        }

        if (empty($domain)) {
            $domain = $notifiable->routeNotificationFor('mastodon-domain');
        }

        if (empty($domain)) {
            $domain = Config::get('services.mastodon.domain');
        }

        if (empty($domain)) {
            return null;
        }

        $token = $route?->token;

        if (empty($token)) {
            $token = $message->token;
        }

        if (empty($token)) {
            $token = $notifiable->routeNotificationFor('mastodon-token');
        }

        if (empty($token)) {
            $token = Config::get('services.mastodon.token');
        }

        if (empty($token)) {
            return null;
        }

        $options = $message->options;

        return Mastodon::domain($domain)
            ->token($token)
            ->createStatus($status, $options);
    }
}
