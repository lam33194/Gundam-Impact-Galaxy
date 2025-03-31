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
    private const LOGIN_MESSAGE = 'Đăng nhập thành công';
    private const LOGOUT_MESSAGE = 'Đăng xuất thành công';
    public function showFormLogin()
    {
        return view(self::PATH_VIEW);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            $redirectRoute = $user->isAdmin() ? 'admin.dashboard' : 'login';

            Toastr::success(null, self::LOGIN_MESSAGE);

            return redirect()->route($redirectRoute);
        }

        return back()->withErrors(['email' => __('auth.failed')])->onlyInput('email');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        Toastr::success(null, self::LOGOUT_MESSAGE);
        return redirect()->route('login');
    }
}
