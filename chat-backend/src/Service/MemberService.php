<?php

namespace App\Service;

use App\Entity\Member;
use App\Repository\ChatRepository;
use App\Repository\MemberRepository;
use App\Repository\MessageRepository;
use App\Request\DTO\MemberToChatDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class MemberService
{
    private $em;
    private $chatService;

    public function __construct(
        EntityManagerInterface $em,
        ChatService $chatService,
        MemberRepository $memberRepository,
        MessageRepository $messageRepository
    ) {
        $this->em = $em;
        $this->chatService = $chatService;
    }

    /**
     * Метод создает участника чата.
     *
     * @param MemberToChatDto $memberToChatDto
     *
     * @return Member
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function create(MemberToChatDto $memberToChatDto): Member {
        $chat = $this->chatService->get($memberToChatDto->chat, $memberToChatDto->nickname);

        $member = new Member();
        $member->setNickname($memberToChatDto->member);
        $member->setChat($chat);

        $this->em->persist($member);
        $this->em->flush();

        return $member;
    }

}
