<?php

namespace Tests\Unit\Application\Inventory\StoredBook\UseCases\Guard;

use App\Application\Inventory\StoredBook\Exception\StoredBookNotFoundException;
use App\Application\Inventory\StoredBook\UseCases\Guard\GuardBookIsAvailableInStore;
use App\Domain\Inventory\StoredBook\Repository\Find\FindStoredBookByIdRepositoryInterface;
use App\Domain\Inventory\StoredBook\StoredBook;
use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GuardBookIsAvailableInStoreTest extends TestCase
{
    private Id $id;

    private FindStoredBookByIdRepositoryInterface|MockObject $repository;

    private GuardBookIsAvailableInStore $guardBookIsAvailableInStore;

    protected function setUp(): void
    {
        $this->id = new Id(rand(1, 100));

        $this->repository = $this->createMock(FindStoredBookByIdRepositoryInterface::class);

        $this->guardBookIsAvailableInStore = new GuardBookIsAvailableInStore(repository: $this->repository);
    }

    /**
     * @throws StoredBookNotFoundException
     */
    #[Test]
    public function itShouldNotThrowExceptionWhenStoredBookExistsInDatabase(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('find')
            ->with($this->id)
            ->willReturn(
                new StoredBook(
                    bookId: new Id(rand(1, 100)),
                    id: $this->id,
                )
            );

        $this->guardBookIsAvailableInStore->verify($this->id);
    }

    /**
     * @throws StoredBookNotFoundException
     */
    #[Test]
    public function itShouldThrowExceptionWhenStoredBookDoesNotExistsInDatabase(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('find')
            ->with($this->id)
            ->willReturn(null);

        $this->expectException(StoredBookNotFoundException::class);
        $this->expectExceptionMessage('Stored book not found');

        $this->guardBookIsAvailableInStore->verify($this->id);
    }
}
