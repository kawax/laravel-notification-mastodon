<?php

namespace Revolution\Laravel\Notification\Mastodon;

final class MastodonRoute
{
    public function __construct(
        public string $domain,
        public string $token,
    ) {
        //
    }

    public static function to(string $domain, string $token): self
    {
        return new self($domain, $token);
    }
}
