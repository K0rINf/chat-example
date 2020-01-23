<?php

namespace App\Request\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateChatDto
{
    /**
     * @Assert\NotBlank
     */
    public $nickname;
    /**
     * @Assert\NotBlank
     */
    public $title;

    /**
     * CreateChatDto constructor.
     *
     * @param string $nickname
     * @param string $title
     */
    public function __construct(string $nickname, string $title)
    {
        $this->nickname = $nickname;
        $this->title = $title;
    }
}
