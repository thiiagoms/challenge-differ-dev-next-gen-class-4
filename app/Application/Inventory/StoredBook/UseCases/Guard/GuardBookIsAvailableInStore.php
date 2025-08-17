<?php

namespace App\Application\Inventory\StoredBook\UseCases\Guard;

use App\Application\Inventory\StoredBook\Exception\StoredBookNotFoundException;
use App\Domain\Inventory\StoredBook\Repository\Find\FindStoredBookByIdRepositoryInterface;
use App\Domain\Shared\ValueObject\Id;

class GuardBookIsAvailableInStore
{
    public function __construct(private readonly FindStoredBookByIdRepositoryInterface $repository) {}

    /**
     * @throws StoredBookNotFoundException
     */
    public function verify(Id $id): void
    {
        $bookIsAvailable = $this->repository->find($id);

        if (empty($bookIsAvailable)) {
            throw StoredBookNotFoundException::create();
        }
    }
}
