<?php

namespace App\Serializer\Normalizer;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Response\ViolationsResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MemberNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Member $object */
        return [
            'id' => $object->getId(),
            'nickname' => $object->getNickname(),
            'createdAt' => $object->getCreatedAt(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Member;
    }
}
