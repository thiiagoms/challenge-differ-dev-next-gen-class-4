<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mappers\Identity\User;

use App\Domain\Identity\User\User as UserEntity;
use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Str;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use Carbon\Carbon;

abstract class UserMapper
{
    public static function toDomainEntity(LaravelUserModel $model): UserEntity
    {
        return new UserEntity(
            name: new Str($model->name),
            email: new Email(new Str($model->email)),
            password: new Password(password: new Str($model->password), hashed: false),
            id: new Id($model->id),
            emailConfirmedAt: $model->email_verified_at
                ? $model->email_verified_at->toDateTimeImmutable()
                : null,
            createdAt: $model->created_at->toDateTimeImmutable(),
            updatedAt: $model->updated_at->toDateTimeImmutable()
        );
    }

    public static function toPersistenceModel(UserEntity $entity): LaravelUserModel
    {
        $model = new LaravelUserModel;

        $model->id = empty($entity->getId()) ? null : $entity->getId()->getValue();
        $model->name = $entity->getName()->getValue();
        $model->email = $entity->getEmail()->getValue();
        $model->password = $entity->getPassword()->getValue();
        $model->created_at = Carbon::createFromImmutable($entity->getCreatedAt());
        $model->updated_at = Carbon::createFromImmutable($entity->getUpdatedAt());
        $model->email_verified_at = empty($entity->getEmailConfirmedAt())
            ? null
            : Carbon::createFromImmutable($entity->getEmailConfirmedAt());

        return $model;
    }
}
