<?php

namespace Tests\Feature\Infrastructure\Persistence\Repository\Inventory\StoredBook\Find;

use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\Book as LaravelBookModel;
use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;
use App\Infrastructure\Persistence\Repository\Inventory\StoredBook\Find\EloquentFindStoredBookByIdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloquentFindStoredBookByIdRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function itShouldReturnStoredBookWhenIdExistsInDatabase(): void
    {
        $book = LaravelBookModel::factory()->create();

        $storedBookFactory = LaravelStoredBookModel::factory()->create(['book_id' => $book->id]);

        $id = new Id($storedBookFactory->id);

        $repository = new EloquentFindStoredBookByIdRepository;

        $storedBook = $repository->find($id);

        $this->assertEquals($id->getValue(), $storedBook->getId()->getValue());
        $this->assertEquals($book->id, $storedBook->getBookId()->getValue());
    }

    #[Test]
    public function itShouldReturnNullWhenIdDoesNotExistInDatabase(): void
    {
        $id = new Id(rand(900, 1001));

        $repository = new EloquentFindStoredBookByIdRepository;

        $storedBook = $repository->find($id);

        $this->assertNull($storedBook);
    }
}
