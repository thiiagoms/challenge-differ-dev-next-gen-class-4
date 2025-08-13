<?php

namespace Tests\Unit\Domain\Catalog\Book;

use App\Domain\Catalog\Book\Book;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    #[Test]
    public function itShouldCreateABook(): void
    {
        $book = new Book(
            title: new Str('Hello World Title'),
            author: new Str('John Doe'),
        );

        $this->assertEquals('Hello World Title', $book->getTitle()->getValue());
        $this->assertEquals('John Doe', $book->getAuthor()->getValue());

        $this->assertNull($book->getIsbn());
        $this->assertNull($book->getId());
    }
}
