<?php

namespace App\Presentation\Http\Api\V1\Circulation\Reservation\Register\Controller;

use App\Application\Circulation\Reservation\UseCases\Register\DTO\RegisterReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Register\RegisterReservation;
use App\Application\Identity\User\Exception\UserNotFoundException;
use App\Application\Inventory\StoredBook\Exception\StoredBookNotFoundException;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Register\Request\RegisterReservationApiRequest;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Shared\Resource\ReservationResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterReservationApiController extends Controller
{
    public function __construct(private readonly RegisterReservation $action) {}

    /**
     * @throws StoredBookNotFoundException
     * @throws UserNotFoundException
     */
    public function __invoke(RegisterReservationApiRequest $request): JsonResponse
    {
        $dto = RegisterReservationDTO::fromRequest($request);

        $reservation = $this->action->reserve($dto);

        return response()->json(
            data: ['data' => ReservationResource::make($reservation)],
            status: Response::HTTP_CREATED
        );
    }
}
