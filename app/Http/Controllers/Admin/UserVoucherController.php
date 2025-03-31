<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserVoucherRequest;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;

class UserVoucherController extends Controller
{
    /**
     * Hiển thị danh sách User Voucher.
     */
    public function index()
    {

        $User_vouchers = UserVoucher::with(['user', 'voucher'])->latest('id')->paginate(10);

        return view('admin.user_vouchers.index', compact('User_vouchers'));
    }

    /**
     * Hiển thị form thêm mới User Voucher.
     */
    public function create()
    {

        $users = User::all();

        $vouchers = Voucher::where('is_active', 1)->get();

        return view('admin.user_vouchers.create', compact('users', 'vouchers'));
    }

    /**
     * Lưu thông tin User Voucher vào database.
     */

    public function store(UserVoucherRequest $request)
    {
        try {
            $data = $request->validated();


            if (!empty($data['user_ids']) && is_array($data['user_ids'])) {
                $insertData = [];
                $userIds = [];
                foreach ($data['user_ids'] as $userId) {
                    if (!UserVoucher::where('user_id', $userId)
                        ->where('voucher_id', $data['voucher_id'])
                        ->exists()) {
                        $userIds[] = $userId;
                        $insertData[] = [
                            'user_id' => $userId,
                            'voucher_id' => $data['voucher_id'],
                            'usage_count' => $data['usage_count'] ?? 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($insertData)) {

                    $vouchers = UserVoucher::insert($insertData);

                    Toastr::success('', 'Thêm mới User Voucher thành công!');
                    return redirect()->route('admin.user-vouchers.index');
                } else {
                    return redirect()->back()->with('error', 'Tất cả người dùng đã nhận voucher này.');
                }
            }

            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một người dùng.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }





    /**
     * Hiển thị form chỉnh sửa User Voucher.
     */
    public function edit(UserVoucher $User_voucher)
    {
        $users = User::all();
        $vouchers = Voucher::all();
        return view('admin.user_vouchers.edit', compact('User_voucher', 'users', 'vouchers'));
    }

    /**
     * Cập nhật User Voucher.
     */
    public function update(UserVoucherRequest $request, UserVoucher $User_voucher)
    {
        try {
            $data = $request->validated();

            $User_voucher->update($data);

            return redirect()->route('admin.user-vouchers.index')->with('success', 'Cập nhật User Voucher thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    /**
     * Xóa User Voucher.
     */
    public function destroy(UserVoucher $User_voucher)
    {
        try {
            $User_voucher->delete();
            return redirect()->route('admin.user-vouchers.index')->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
