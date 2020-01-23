<?php

namespace App\Controller;

use App\Request\DTO\CreateChatDto;
use App\Request\DTO\MemberToChatDto;
use App\Request\DTO\MessageDto;
use App\Response\ViolationsResponse;
use App\Service\ChatService;
use App\Service\MemberService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use \Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChatController extends AbstractController
{
    private $validator;
    private $chatService;
    private $memberService;

    /**
     * ChatController constructor.
     *
     * @param ValidatorInterface $validator
     * @param ChatService        $chatService
     * @param MemberService      $memberService
     */
    public function __construct(
        ValidatorInterface $validator,
        ChatService $chatService,
        MemberService $memberService
    )
    {
        $this->validator = $validator;
        $this->chatService = $chatService;
        $this->memberService = $memberService;
    }


    /**
     * @Route("/chat", name="chat_create", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $chatDto = new CreateChatDto(
            $request->get('nickname', ''),
            $request->get('title', '')
        );

        $errors = $this->validator->validate($chatDto);
        if ($errors->count() > 0) {
            return $this->json(new ViolationsResponse($errors), Response::HTTP_BAD_REQUEST);
        }

        try {
            $chatEntity = $this->chatService->create($chatDto);
        } catch (Exception $exception) {
            return $this->json(['server' => 'Ошибка создания чата'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($chatEntity);
    }

    /**
     * @Route("/chat/{code}", name="chat", methods={"GET"})
     * @param Request $request
     *
     * @param string  $code
     *
     * @return JsonResponse
     */
    public function getChat(Request $request, string $code): JsonResponse
    {
        try {
            $chatEntity = $this->chatService->get($code, $request->get('nickname', ''));
        } catch (NonUniqueResultException $exception) {
            return $this->json(['server' => 'Незивестная ошибка'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (NoResultException $exception) {
            return $this->json(['server' => 'Чат не найден'], Response::HTTP_NOT_FOUND);
        } catch (RuntimeException $exception) {
            return $this->json(['server' => 'Чат не найден'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($chatEntity);
    }

    /**
     * @Route("/chat/{code}/messages", name="chat_messages", methods={"GET"})
     * @param Request $request
     *
     * @param string  $code
     *
     * @return JsonResponse
     */
    public function getChatMessages(Request $request, string $code): JsonResponse
    {
        try {
            $messages = $this->chatService->getMessages($code, $request->get('nickname', ''));
        } catch (NonUniqueResultException $exception) {
            return $this->json(['server' => 'Незивестная ошибка'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (NoResultException $exception) {
            return $this->json(['server' => 'Чат не найден'], Response::HTTP_NOT_FOUND);
        } catch (RuntimeException $exception) {
            return $this->json(['server' => 'Чат не найден'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($messages);
    }

    /**
     * @Route("/chat/{code}/members", name="chat_member_add", methods={"POST"})
     * @param Request $request
     *
     * @param string  $code
     *
     * @return JsonResponse
     */
    public function addMember(Request $request, string $code): JsonResponse
    {
        $memberToChatDto = new MemberToChatDto(
            $request->get('chat', ''),
            $request->get('nickname', ''),
            $request->get('member', '')
        );

        $errors = $this->validator->validate($memberToChatDto);
        if ($errors->count() > 0) {
            return $this->json(new ViolationsResponse($errors), Response::HTTP_BAD_REQUEST);
        }

        try {
            $member = $this->memberService->create($memberToChatDto);
        } catch (NonUniqueResultException $exception) {
            return $this->json(['server' => 'Незивестная ошибка'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (NoResultException $exception) {
            return $this->json(['server' => 'Чат не найден'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return $this->json(['server' => 'Ошибка создания участника'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($member);
    }
}
