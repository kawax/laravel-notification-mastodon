<?php

namespace Revolution\Laravel\Notification\Mastodon;

final class MastodonRoute
{
    public function __construct(
        public string $domain,
        #[\SensitiveParameter]
        public string $token,
    ) {
        //
    }

    public static function to(string $domain, #[\SensitiveParameter] string $token): self
    {
        return new self($domain, $token);
    }
}
