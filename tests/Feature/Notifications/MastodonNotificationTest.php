<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Revolution\Laravel\Notification\Mastodon\MastodonChannel;
use Revolution\Laravel\Notification\Mastodon\MastodonMessage;
use Revolution\Laravel\Notification\Mastodon\MastodonRoute;
use Tests\TestCase;

class MastodonNotificationTest extends TestCase
{
    public function test_notification_fake()
    {
        NotificationFacade::fake();

        NotificationFacade::route('mastodon', MastodonRoute::to('mastodon.social', 'test-token'))
            ->notify(new TestNotification('Test status'));

        NotificationFacade::assertSentOnDemand(TestNotification::class);
    }

    public function test_notification_with_user()
    {
        NotificationFacade::fake();

        $user = new TestUser;
        $user->notify(new TestNotification('Test status'));

        NotificationFacade::assertSentTo($user, TestNotification::class);
    }

    public function test_notification_with_user_routing()
    {
        NotificationFacade::fake();

        $user = new TestUserWithRouting;
        $user->notify(new TestNotification('Test status'));

        NotificationFacade::assertSentTo($user, TestNotification::class);
    }

    public function test_channel_returns_null_for_empty_status()
    {
        $channel = new MastodonChannel;
        $user = new TestUser;
        $notification = new TestNotification('');

        $result = $channel->send($user, $notification);

        $this->assertNull($result);
    }

    public function test_channel_returns_null_for_no_domain()
    {
        $this->app['config']->set('services.mastodon.domain', null);

        $channel = new MastodonChannel;
        $user = new TestUser;
        $notification = new TestNotification('Test status');

        $result = $channel->send($user, $notification);

        $this->assertNull($result);
    }

    public function test_channel_returns_null_for_no_token()
    {
        $this->app['config']->set('services.mastodon.token', null);

        $channel = new MastodonChannel;
        $user = new TestUser;
        $notification = new TestNotification('Test status');

        $result = $channel->send($user, $notification);

        $this->assertNull($result);
    }

    public function test_message_creation()
    {
        $message = new MastodonMessage('Test status');
        $message2 = MastodonMessage::create('Test status');

        $this->assertSame('Test status', $message->status);
        $this->assertSame('Test status', $message2->status);
        $this->assertIsArray($message->toArray());
        $this->assertSame('Test status', $message->toArray()['status']);
    }

    public function test_message_fluent_interface()
    {
        $message = MastodonMessage::create('Test status')
            ->domain('custom.mastodon')
            ->token('custom-token')
            ->options(['visibility' => 'private']);

        $this->assertSame('Test status', $message->status);
        $this->assertSame('custom.mastodon', $message->domain);
        $this->assertSame('custom-token', $message->token);
        $this->assertSame(['visibility' => 'private'], $message->options);
    }

    public function test_message_to_array()
    {
        $message = MastodonMessage::create('Test status')
            ->domain('custom.mastodon')
            ->token('custom-token')
            ->options(['visibility' => 'private']);

        $array = $message->toArray();

        $this->assertSame('Test status', $array['status']);
        $this->assertSame('custom.mastodon', $array['domain']);
        $this->assertSame('custom-token', $array['token']);
        $this->assertSame('private', $array['visibility']);
    }

    public function test_message_status_method()
    {
        $message = MastodonMessage::create('Initial status');

        $this->assertSame('Initial status', $message->status);

        $result = $message->status('Updated status');

        $this->assertSame($message, $result);
        $this->assertSame('Updated status', $message->status);
        $this->assertSame('Updated status', $message->toArray()['status']);
    }

    public function test_route_creation()
    {
        $route = MastodonRoute::to('mastodon.social', 'test-token');

        $this->assertSame('mastodon.social', $route->domain);
        $this->assertSame('test-token', $route->token);
    }

    public function test_user_routing_methods()
    {
        $user = new TestUserWithRouting;
        $route = $user->routeNotificationForMastodon(new TestNotification('test'));

        $this->assertInstanceOf(MastodonRoute::class, $route);
        $this->assertSame('user.mastodon', $route->domain);
        $this->assertSame('user-token', $route->token);
    }

    public function test_user_domain_routing()
    {
        $user = new TestUserWithDomainRouting;
        $domain = $user->routeNotificationForMastodonDomain(new TestNotification('test'));

        $this->assertSame('user-domain.mastodon', $domain);
    }

    public function test_user_token_routing()
    {
        $user = new TestUserWithTokenRouting;
        $token = $user->routeNotificationForMastodonToken(new TestNotification('test'));

        $this->assertSame('user-token', $token);
    }
}

class TestNotification extends Notification
{
    public function __construct(
        protected string $status,
    ) {}

    public function via(object $notifiable): array
    {
        return [MastodonChannel::class];
    }

    public function toMastodon(object $notifiable): MastodonMessage
    {
        return MastodonMessage::create($this->status);
    }
}

class TestNotificationWithDomain extends Notification
{
    public function __construct(
        protected string $status,
    ) {}

    public function via(object $notifiable): array
    {
        return [MastodonChannel::class];
    }

    public function toMastodon(object $notifiable): MastodonMessage
    {
        return MastodonMessage::create($this->status)
            ->domain('message.mastodon');
    }
}

class TestNotificationWithToken extends Notification
{
    public function __construct(
        protected string $status,
    ) {}

    public function via(object $notifiable): array
    {
        return [MastodonChannel::class];
    }

    public function toMastodon(object $notifiable): MastodonMessage
    {
        return MastodonMessage::create($this->status)
            ->token('message-token');
    }
}

class TestUser extends Model
{
    use Notifiable;
}

class TestUserWithRouting extends Model
{
    use Notifiable;

    public function routeNotificationForMastodon($notification): MastodonRoute
    {
        return MastodonRoute::to('user.mastodon', 'user-token');
    }
}

class TestUserWithDomainRouting extends Model
{
    use Notifiable;

    public function routeNotificationForMastodonDomain($notification): string
    {
        return 'user-domain.mastodon';
    }
}

class TestUserWithTokenRouting extends Model
{
    use Notifiable;

    public function routeNotificationForMastodonToken($notification): string
    {
        return 'user-token';
    }
}
