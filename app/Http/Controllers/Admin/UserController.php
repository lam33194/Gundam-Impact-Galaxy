<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Alert;
use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserAddresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function store(StoreUserRequest $request)
    {
        $image = null;
        try {
            DB::transaction(function () use ($request, &$image) {
                $dataUser = $request->user;
                $dataAddress = $request->address;

                $dataUser['is_active'] = isset($dataUser['is_active']);
                $dataUser['password'] = Hash::make($dataUser['password']);

                if ($request->hasFile('user.avatar')) {
                    $image = Storage::put('users', $request->file('user.avatar'));
                    $dataUser['avatar'] = $image;
                }
                $user = User::query()->create($dataUser);

                $dataAddress['user_id'] = $user->id;
                $address = UserAddresses::create($dataAddress);
            }, 3);

            Toastr::success(null, 'Thêm user thành công');
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            Alert::error(null, $th->getMessage());
            Log::error($th->getMessage());

            if (Storage::exists($image)) {
                Storage::delete($image);
            }
        }
    }

    public function show(User $user)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('user'));
    }

    public function edit(User $user)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->user;
            $data['is_active'] = $request->has('user.is_active');
            $data['password'] = $request->filled('user.password') ?
                Hash::make($request->user['password']) : $user->password;

            if ($request->hasFile('user.avatar')) {
                $data['avatar'] = Storage::put('users', $request->file('user.avatar'));
            }

            $imageOld = $user->avatar;

            $user->update($data);

            if ($request->hasFile('user.avatar') && Storage::exists($imageOld)) {
                Storage::delete($imageOld);
            }
            Toastr::success(null, 'Cập nhật user thành công');
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error(null, $th->getMessage());
            Log::error($th->getMessage());
        }
    }

    
}
