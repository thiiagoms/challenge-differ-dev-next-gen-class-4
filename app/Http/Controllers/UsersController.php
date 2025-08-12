<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function getAll(): JsonResponse
    {
        $users = User::all();

        return response()->json($users);
    }
}
