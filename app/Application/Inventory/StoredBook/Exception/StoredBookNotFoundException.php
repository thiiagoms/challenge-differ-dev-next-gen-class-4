<?php

namespace App\Application\Inventory\StoredBook\Exception;

use App\Application\Shared\Exception\NotFoundException;

final class StoredBookNotFoundException extends NotFoundException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(): self
    {
        return new self(message: 'Stored book not found');
    }
}
