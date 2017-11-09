# Laravel Notification for Mastodon

## Requirements
- PHP >= 7.0.0
- Laravel >= 5.5

## Installation

### Composer
```
composer require revolution/laravel-notification-mastodon
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

### Send to specific one account

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
        $status = 'test';

        return MastodonMessage::create($status)
                              ->domain(config('services.mastodon.domain'))
                              ->token(config('services.mastodon.token'));
        
        // or 
        return MastodonMessage::create($status);
    }
}
```

### Send to user's account
Get token by https://github.com/kawax/socialite-mastodon


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
        $status = 'test';
        
        // domain and token from somewhere
        $domain = $notifiable->domain;
        $token = $notifiable->token;

        return MastodonMessage::create($status)
                              ->domain($domain)
                              ->token($token);
    }
}
```

### Set options
https://github.com/tootsuite/documentation/blob/master/Using-the-API/API.md#posting-a-new-status

```php
    public function toMastodon($notifiable)
    {
        $status = 'test';
        
        $options = [
            'visibility' => 'unlisted',
        ];

        return MastodonMessage::create($status)
                              ->options($options);
    }
```


## LICENSE
MIT  
Copyright kawax
