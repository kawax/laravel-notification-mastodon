# Laravel Notification for Mastodon

## Requirements
- PHP >= 8.2
- Laravel >= 11.0

## Installation

This package is part of [revolution/laravel-mastodon-api](https://github.com/invokable/laravel-mastodon-api).

### Composer
```
composer require revolution/laravel-mastodon-api
```

## Config

Set default `domain` and `token`

### config/services.php
```php
    'mastodon' => [
        'domain'        => env('MASTODON_DOMAIN'),
        'token'         => env('MASTODON_TOKEN'),
    ],
```

### .env
```
MASTODON_DOMAIN=https://example.com
MASTODON_TOKEN=
```

### TOKEN?
Go to your Mastodon's preferences page.


## Usage


```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

use Revolution\Laravel\Notification\Mastodon\MastodonChannel;
use Revolution\Laravel\Notification\Mastodon\MastodonMessage;

class MastodonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [MastodonChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return MastodonMessage
     */
    public function toMastodon($notifiable)
    {
        return MastodonMessage::create($this->status);
    }
}
```

### Send to specific one account

```php
use Illuminate\Support\Facades\Notification;
use Revolution\Laravel\Notification\Mastodon\MastodonRoute;

Notification::route('mastodon', MastodonRoute::to(config('services.mastodon.domain'), config('services.mastodon.token')))
            ->notify(new MastodonNotification('test'));
```

### Send to user's account
Get token by https://github.com/invokable/socialite-mastodon

```php
use Illuminate\Notifications\Notifiable;
use Revolution\Laravel\Notification\Mastodon\MastodonRoute;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForMastodon($notification): MastodonRoute
    {
        return MastodonRoute::to(domain: $this->domain, token: $this->token);
    }
}
```

```php
$user->notify(new MastodonNotification('test'));
```

### Set options
https://docs.joinmastodon.org/methods/statuses/

```php
    public function toMastodon($notifiable)
    {
        $options = [
            'visibility' => 'unlisted',
        ];

        return MastodonMessage::create($this->status)
                              ->options($options);
    }
```

## LICENSE
MIT  
