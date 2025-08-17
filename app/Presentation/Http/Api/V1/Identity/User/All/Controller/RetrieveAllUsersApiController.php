<?php

namespace App\Presentation\Http\Api\V1\Identity\User\All\Controller;

use App\Application\Identity\User\UseCases\All\ReturnAllUsers;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Api\V1\Identity\User\Shared\Resource\UsersResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class RetrieveAllUsersApiController extends Controller
{
    public function __construct(private readonly ReturnAllUsers $action) {}

    public function __invoke(): JsonResponse
    {
        $users = $this->action->all();

        return response()->json(
            data: ['data' => UsersResource::collection($users)],
            status: Response::HTTP_OK
        );
    }
}
