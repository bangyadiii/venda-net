<?php

namespace App\Notifications\Whatsapp;

use App\Exceptions\FailedToSendNotification;
use JsonSerializable;

/**
 * Class LaravelWablasMessage.
 *
 * @package Shadowbane\LaravelWablas
 */
class WhatsappMessage implements JsonSerializable
{
    protected $payload = [];

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    /**
     * Message constructor.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    /**
     * Notification message (Supports Markdown).
     *
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content): self
    {
        $this->payload['message'] = $content;

        return $this;
    }

    /**
     * Recipient's Phone number.
     *
     * @param $phoneNumber
     *
     * @return $this
     * @throws FailedToSendNotification
     */
    public function to($phoneNumber): self
    {
        // return debug phone number if local
        // this will prevent real user getting debug notification
        if (app()->isLocal() && config('app.debug')) {
            $this->payload['phone'] = config('laravel-wablas.debug_number');

            return $this;
        }

        // throw error if $phoneNumber is blank
        if (blank($phoneNumber)) {
            throw FailedToSendNotification::destinationIsEmpty();
        }

        // implode, if this is an array
        if (is_array($phoneNumber)) {
            $phoneNumber = implode(',', $phoneNumber);
        }

        $this->payload['phone'] = $phoneNumber;

        return $this;
    }


    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
