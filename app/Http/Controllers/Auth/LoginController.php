<?php

namespace App\Http\Controllers\Auth;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    private const PATH_VIEW = 'auth.login';
    
    public function showFormLogin()
    {
        return view(self::PATH_VIEW);
    }
}
