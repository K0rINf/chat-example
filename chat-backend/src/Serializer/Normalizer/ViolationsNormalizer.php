<?php

namespace App\Serializer\Normalizer;

use App\Response\ViolationsResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ViolationsNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var ViolationsResponse $object */
        return $object->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ViolationsResponse;
    }
}
