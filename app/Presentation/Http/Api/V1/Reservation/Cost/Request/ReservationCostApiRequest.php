<?php

namespace App\Presentation\Http\Api\V1\Reservation\Cost\Request;

use Illuminate\Foundation\Http\FormRequest;

class ReservationCostApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reservation_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
