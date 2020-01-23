<?php

namespace App\Response;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsResponse
{
    private $errors;

    /**
     * JsonViolations constructor.
     *
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct(ConstraintViolationListInterface $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $errors = [];
        foreach ($this->errors as $error) {
            /** @var ConstraintViolation $error */
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errors;
    }
}
