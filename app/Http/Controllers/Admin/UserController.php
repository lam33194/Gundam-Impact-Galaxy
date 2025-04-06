<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    private const VIEW_PATH = 'admin.users.';

    public function index()
    {
        $users = User::query()->latest('id')->paginate(10);

        if ($users->currentPage() > $users->lastPage()) {
            return redirect()->route('admin.users.index', parameters: ['page' => $users->lastPage()]);
        }

        return view(self::VIEW_PATH . __FUNCTION__, compact(['users']));
    }

    public function create()
    {
        return view(self::VIEW_PATH . __FUNCTION__);
    }
}
