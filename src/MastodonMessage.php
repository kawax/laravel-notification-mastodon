<?php

namespace Revolution\Laravel\Notification\Mastodon;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;

final class MastodonMessage implements Arrayable
{
    use Conditionable;
    use Macroable;

    /**
     * @var string
     */
    public string $domain = '';

    /**
     * @var string
     */
    public string $token = '';

    /**
     * @var array
     */
    public array $options = [];

    public function __construct(public string $status)
    {
        //
    }

    public static function create(string $status): self
    {
        return new self($status);
    }

    public function status(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function domain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function token(#[\SensitiveParameter] string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'domain' => $this->domain,
                'token' => $this->token,
                'status' => $this->status,
            ],
            $this->options,
        );
    }
}
