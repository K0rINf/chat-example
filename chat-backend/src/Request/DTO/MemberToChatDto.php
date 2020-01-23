<?php

namespace App\Request\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class MemberToChatDto
{
    /**
     * @Assert\NotBlank
     */
    public $chat;

    /**
     * @Assert\NotBlank
     */
    public $nickname;

    /**
     * @Assert\NotBlank
     */
    public $member;

    /**
     * MemberToChatDto constructor.
     *
     * @param string $chat
     * @param string $nickname
     * @param string $member
     */
    public function __construct(string $chat, string $nickname, string $member)
    {
        $this->chat = $chat;
        $this->nickname = $nickname;
        $this->member = $member;
    }
}


