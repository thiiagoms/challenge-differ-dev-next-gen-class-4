<?php

namespace App\Presentation\Http\Api\V1\Circulation\Reservation\Return\Request;

use Illuminate\Foundation\Http\FormRequest;

class ReturnReservationApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reservation_id' => [
                'required',
                'integer',
            ],
            'return_date' => [
                'required',
                'string',
            ],
        ];
    }
}
