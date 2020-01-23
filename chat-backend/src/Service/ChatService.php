<?php

namespace App\Service;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Repository\ChatRepository;
use App\Repository\MemberRepository;
use App\Repository\MessageRepository;
use App\Request\DTO\CreateChatDto;
use App\Request\DTO\MessageDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use RuntimeException;

class ChatService
{
    private $em;
    private $chatRepository;
    private $memberRepository;
    private $messageRepository;

    /**
     * ChatService constructor.
     *
     * @param EntityManagerInterface $em
     * @param ChatRepository         $chatRepository
     * @param MemberRepository       $memberRepository
     * @param MessageRepository      $messageRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        ChatRepository $chatRepository,
        MemberRepository $memberRepository,
        MessageRepository $messageRepository
    ) {
        $this->em = $em;
        $this->chatRepository = $chatRepository;
        $this->memberRepository = $memberRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Получить историю сообщений из чата.
     *
     * @param string $code
     * @param string $nickname
     *
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getMessages(string $code, string $nickname): array
    {
        return $this->messageRepository->findBy(
            [
                'chat' => $this->get($code, $nickname),
            ],
            [
                'createdAt' => 'asc',
            ]
        );
    }

    /**
     * Получить информацию о чате.
     *
     * @param string $code
     * @param string $nickname
     *
     * @return Chat|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function get(string $code, string $nickname): Chat
    {
        if ($nickname === '') {
            throw new RuntimeException('Nickname не может быть пустой строкой.');
        }

        return $this->chatRepository->findForNickname($nickname, $code);
    }

    /**
     * Отправка сообщения в чат.
     *
     * @param MessageDto $dto
     *
     * @return Message
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function send(MessageDto $dto): Message
    {
        $chat = $this->get($dto->chat, $dto->nickname);

        $member = $this->memberRepository->findOneBy(
            [
                'chat' => $chat,
                'nickname' => $dto->nickname,
            ]
        );

        $message = new Message();
        $message->setChat($chat);
        $message->setCreatedBy($member);
        $message->setBody($dto->body);

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * @param string $chat
     * @param string $nickname
     * @param int    $status
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function changeStatus(string $chat, string $nickname, int $status)
    {
        $chat = $this->get($chat, $nickname);

        $member = $this->memberRepository->findOneBy(
            [
                'chat' => $chat,
                'nickname' => $nickname,
            ]
        );

        $member->setStatus($status);
        $this->em->persist($member);
        $this->em->flush();
    }

    /**
     * Создание чата.
     *
     * @param CreateChatDto $chatDto
     *
     * @return Chat
     */
    public function create(CreateChatDto $chatDto): Chat
    {

        $chat = new Chat();
        $chat->setCode($this->generateCode(rand(8, 32)));
        $chat->setTitle($chatDto->title);

        $author = new Member();
        $author->setChat($chat);
        $author->setNickname($chatDto->nickname);

        $chat->setCreatedBy($author);
        $chat->addMember($author);

        $this->em->persist($author);
        $this->em->persist($chat);
        $this->em->flush();

        return $chat;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateCode(int $length): string
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
}
