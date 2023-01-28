<?php

namespace Revolution\Laravel\Notification\Mastodon;

class MastodonMessage
{
    /**
     * @var string
     */
    public string $status = '';

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

    /**
     * MastodonMessage constructor.
     *
     * @param  string  $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param  string  $status
     *
     * @return $this
     */
    public static function create(string $status): static
    {
        return new static($status);
    }

    /**
     * @param  string  $status
     *
     * @return $this
     */
    public function status(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param  string  $domain
     *
     * @return $this
     */
    public function domain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param  string  $token
     *
     * @return $this
     */
    public function token(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param  array  $options
     *
     * @return $this
     */
    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            [
                'domain' => $this->domain,
                'token'  => $this->token,
                'status' => $this->status,
            ],
            $this->options
        );
    }
}
