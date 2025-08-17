<?php

namespace App\Presentation\Http\Api\V1\Reservation\Cost\Controller;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Cost\GetReservationCost;
use App\Domain\Shared\ValueObject\Id;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Api\V1\Reservation\Cost\Request\ReservationCostApiRequest;
use App\Presentation\Http\Api\V1\Reservation\Cost\Resource\ReservationCostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReservationCostApiController extends Controller
{
    public function __construct(private readonly GetReservationCost $action) {}

    /**
     * @throws ReservationNotFoundException
     */
    public function __invoke(ReservationCostApiRequest $request): JsonResponse
    {
        $id = new Id($request->input('reservation_id'));

        $cost = $this->action->cost($id);

        return response()->json(
            data: ['data' => ReservationCostResource::make($cost)],
            status: Response::HTTP_OK
        );
    }
}
