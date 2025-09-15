<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class BadRequestException extends Exception
{
    private ConstraintViolationListInterface $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;

        parent::__construct('Bad request.');
    }

    public function toArray(): array
    {
        $errors = [];
        foreach ($this->constraintViolationList as $violation) {
            if (!isset($errors[$violation->getPropertyPath()])) {
                $errors[$violation->getPropertyPath()] = [];
            }

            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
