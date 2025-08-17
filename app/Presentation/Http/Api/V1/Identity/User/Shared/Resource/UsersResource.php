<?php

namespace App\Presentation\Http\Api\V1\Identity\User\Shared\Resource;

use App\Domain\Identity\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    public function toArray(Request $request)
    {
        /** @var User $users */
        $user = $this->resource;

        return UserResource::make($user);
    }
}
