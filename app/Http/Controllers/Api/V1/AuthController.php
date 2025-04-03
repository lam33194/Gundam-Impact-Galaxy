<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->only(['email', 'password', 'name']);

        $user = User::create($validatedData);

        return response()->json([
            $user, 
            $user->createToken('token-register')->plainTextToken
        ]);
    }
}
