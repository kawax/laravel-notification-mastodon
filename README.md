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

Notification::route('mastodon-domain', config('services.mastodon.domain'))
            ->route('mastodon-token', config('services.mastodon.token'))
            ->notify(new MastodonNotification('test'));
```

### Send to user's account
Get token by https://github.com/kawax/socialite-mastodon

```php
class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMastodonDomain($notification)
    {
        return $this->domain;
    }
    
    /**
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMastodonToken($notification)
    {
        return $this->token;
    }
}
```

```php
$user->notify(new MastodonNotification('test'));
```

### Set options
https://github.com/tootsuite/documentation/blob/master/Using-the-API/API.md#posting-a-new-status

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
Copyright kawax
