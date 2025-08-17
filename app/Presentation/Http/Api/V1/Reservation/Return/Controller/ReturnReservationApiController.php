<?php

namespace App\Presentation\Http\Api\V1\Reservation\Return\Controller;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Return\DTO\ReturnReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Return\ReturnReservation;
use App\Domain\Circulation\Reservation\Exception\ReturnReservationDateIsInvalidException;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Api\V1\Reservation\Return\Request\ReturnReservationApiRequest;
use App\Presentation\Http\Api\V1\Reservation\Shared\Resource\ReservationResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReturnReservationApiController extends Controller
{
    public function __construct(private readonly ReturnReservation $action) {}

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws ReservationNotFoundException
     * @throws InvalidReservationStatusTransitionException
     */
    public function __invoke(ReturnReservationApiRequest $request): JsonResponse
    {
        $dto = ReturnReservationDTO::fromRequest($request);

        $reservation = $this->action->complete($dto);

        return response()->json(
            data: ['data' => ReservationResource::make($reservation)],
            status: Response::HTTP_ACCEPTED
        );
    }
}
