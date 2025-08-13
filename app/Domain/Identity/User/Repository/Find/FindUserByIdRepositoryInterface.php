<?php

namespace App\Domain\Identity\User\Repository\Find;

use App\Domain\Identity\User\User;
use App\Domain\Shared\ValueObject\Id;

interface FindUserByIdRepositoryInterface
{
    public function find(Id $id): ?User;
}
