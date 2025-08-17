<?php

namespace App\Presentation\Http\Api\V1\Circulation\Reservation\Register\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterReservationApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'stored_book_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
