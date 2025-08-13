<?php

declare(strict_types=1);

namespace App\Domain\Identity\User;

use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Str;
use DateTimeImmutable;

final class User
{
    private readonly DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        private Str $name,
        private Email $email,
        private Password $password,
        private readonly ?Id $id = null,
        private readonly ?DateTimeImmutable $emailConfirmedAt = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $now = new DateTimeImmutable;

        $this->createdAt = $createdAt ?? $now;
        $this->updatedAt = $updatedAt ?? $now;
    }

    public function getId(): ?Id
    {
        return $this->id;
    }

    public function getName(): Str
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getEmailConfirmedAt(): ?DateTimeImmutable
    {
        return $this->emailConfirmedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeNameTo(Str $name): void
    {
        $this->name = $name;
        $this->touch();
    }

    public function changeEmailTo(Email $email): void
    {
        $this->email = $email;
        $this->touch();
    }

    public function changePasswordTo(Password $password): void
    {
        $this->password = $password;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable;
    }
}
