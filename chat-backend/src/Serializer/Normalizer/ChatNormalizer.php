<?php

namespace App\Serializer\Normalizer;

use App\Entity\Chat;
use App\Response\ViolationsResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ChatNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Chat $object */
        $members = [];
        foreach ($object->getMembers() as $member) {
            $members[] = [
                'id' => $member->getId(),
                'nickname' => $member->getNickname(),
                'createdAt' => $member->getCreatedAt(),
            ];
        }

        return [
            'id' => $object->getId(),
            'code' => $object->getCode(),
            'title' => $object->getTitle(),
            'author' => $object->getCreatedBy()->getNickname(),
            'createdAt' => $object->getCreatedAt(),
            'members' => $members,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Chat;
    }
}
