<?php

namespace App\Application\Identity\User\Exception;

use App\Application\Shared\Exception\NotFoundException;

class UserNotFoundException extends NotFoundException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(): self
    {
        return new self(message: 'User not found');
    }
}
