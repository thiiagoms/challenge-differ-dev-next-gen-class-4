<?php

namespace App\Domain\Inventory\StoredBook\Repository\Find;

use App\Domain\Inventory\StoredBook\StoredBook;
use App\Domain\Shared\ValueObject\Id;

interface FindStoredBookByIdRepositoryInterface
{
    public function find(Id $id): ?StoredBook;
}
