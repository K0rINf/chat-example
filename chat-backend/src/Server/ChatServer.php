<?php

namespace App\Server;

use App\Entity\Member;
use App\Request\DTO\MessageDto;
use App\Service\ChatService;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *
 * Class Chat
 * @package App\Server
 */
class ChatServer implements MessageComponentInterface
{
    protected $clients;
    protected $chats = [];
    private $io;
    private $validator;
    private $chatService;
    private $serializer;
    /**
     * @var int
     */
    private $port;

    /**
     * Chat constructor.
     *
     * @param int                 $port
     * @param ValidatorInterface  $validator
     * @param SerializerInterface $serializer ,
     * @param ChatService         $chatService
     */
    public function __construct(
        int $port,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ChatService $chatService
    ) {
        $this->port = $port;
        $this->validator = $validator;
        $this->chatService = $chatService;
        $this->serializer = $serializer;
        $this->clients = new SplObjectStorage;
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        foreach ($this->chats as $chat => $splStorage) {
            if ($splStorage->contains($conn)) {
                $splStorage->detach($conn);
            }
        }

        $this->io->writeln('close ');
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->io->writeln('Сообщение '.$msg);

        try {
            $messageDto = new MessageDto($msg);
        } catch (Exception $exception) {
            $this->io->writeln('ошибка создания DTO '.$msg);
            return null;
        }
        $errors = $this->validator->validate($messageDto);

        $chatCode = $messageDto->chat;

        $this->io->writeln('тип сообщения '.$messageDto->type);

        switch ($messageDto->type) {
            case MessageDto::TYPE_MESSAGE:

                if ($errors->count() == 0) {
                    $message = $this->chatService->send($messageDto);
                    $json = $this->serializer->serialize(
                        $message,
                        'json',
                        [
                            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                        ]
                    );
                    $this->io->writeln($json);

                    // отправляем всем кто подписан на чат
                    $this->io->writeln('Подписчики чата '.count($this->chats[$chatCode]));
                    if (isset($this->chats[$chatCode])) {
                        foreach ($this->chats[$chatCode] as $client) {
                            $client->send($json);
                        }
                    }
                }

                break;
            case MessageDto::TYPE_SUBSCRIBE:
                if (!isset($this->chats[$chatCode])) {
                    $this->chats[$chatCode] = new SplObjectStorage;
                }

                $this->chats[$chatCode]->attach($from);

                $this->io->writeln('Подписчики чата '.count($this->chats[$chatCode]));

                $this->chatService->changeStatus($messageDto->chat, $messageDto->nickname, Member::STATUS_ONLINE);

                break;
            case MessageDto::TYPE_UNSUBSCRIBE:
                if (isset($this->chats[$chatCode])) {
                    $this->chats[$chatCode]->detach($from);
                    $this->chatService->changeStatus($messageDto->chat, $messageDto->nickname, Member::STATUS_OFFLINE);
                }
                break;
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->io->writeln('Подключился клиент');
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setIO(SymfonyStyle $io)
    {
        $this->io = $io;
    }
}
