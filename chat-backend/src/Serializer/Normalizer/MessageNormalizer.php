<?php

namespace App\Serializer\Normalizer;

use App\Entity\Chat;
use App\Entity\Message;
use App\Response\ViolationsResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MessageNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Message $object */
        return [
            'id' => $object->getId(),
            'body' => $object->getBody(),
            'createdAt' => $object->getCreatedAt(),
            'updatedAt' => $object->getUpdatedAt(),
            'author' => $object->getCreatedBy()->getNickname(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Message;
    }
}
