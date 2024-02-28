<?php

namespace Revolution\Laravel\Notification\Mastodon;

class MastodonRoute
{
    public function __construct(public string $domain, public string $token)
    {
        //
    }

    public static function to(string $domain, string $token): static
    {
        return new static($domain, $token);
    }
}
