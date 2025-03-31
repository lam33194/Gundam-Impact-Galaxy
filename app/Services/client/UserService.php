<?php

namespace App\Services\Client;

use App\Models\User;


class UserService
{

    public function storeUserService($data)
    {
        return User::query()->create($data);
    }


    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
