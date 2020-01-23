<?php

namespace App\Request\DTO;

use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

class MessageDto
{
    const TYPE_SUBSCRIBE = 'subscribe';
    const TYPE_UNSUBSCRIBE = 'unsubscribe';
    const TYPE_MESSAGE = 'message';

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback="getAvailableTypes", message="Not Available type message")
     */
    public $type;

    /**
     * @Assert\NotBlank
     */
    public $body;

    /**
     * @Assert\NotBlank
     */
    public $nickname;

    /**
     * @Assert\NotBlank
     */
    public $chat;

    /**
     * MessageDto constructor.
     *
     * @param string $data
     */
    public function __construct(string $data)
    {
        $stdData = json_decode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException($data.' Is not JSON format');
        }

        $this->type = $stdData->type;
        $this->body = $stdData->body;
        $this->nickname = $stdData->nickname;
        $this->chat = $stdData->chat;
    }

    public function getAvailableTypes()
    {
        return [MessageDto::TYPE_SUBSCRIBE, MessageDto::TYPE_UNSUBSCRIBE, MessageDto::TYPE_MESSAGE];
    }

}
