<?php

namespace Revolution\Laravel\Notification\Mastodon;

class MastodonMessage
{
    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $domain;

    /**
     * @var string
     */
    public $token;

    /**
     * @var array
     */
    public $options;

    /**
     * MastodonMessage constructor.
     *
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public static function create(string $status)
    {
        return new static($status);
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function status(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $domain
     *
     * @return $this
     */
    public function domain(string $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function token(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge([
            'domain' => $this->domain,
            'token'  => $this->token,
            'status' => $this->status,
        ], $this->options);
    }
}
