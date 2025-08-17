<?php

namespace App\Presentation\Http\Api\V1\Identity\User\Shared\Resource;

use App\Domain\Identity\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->getId()?->getValue(),
            'name' => $user->getName()->getValue(),
            'email' => $user->getEmail()->getValue(),
            'email_confirmed_at' => $user->getEmailConfirmedAt()?->format('Y-m-d H:i:s'),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
