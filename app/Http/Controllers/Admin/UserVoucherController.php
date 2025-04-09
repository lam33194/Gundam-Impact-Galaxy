<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Helper\Toastr;
use App\Models\Voucher;
use App\Http\Requests\VoucherRequest;
use App\Models\UserVoucher;
use App\Models\User;
 class UserVoucherController extends Controller
{
    // hiển thị danh sách user.
    public function index(){
        $User_vouchers = UserVoucher::with(['user','voucher'])->latest('id')->paginate(10);
        return view('admin.user_vouchers.index', compact('User_vouchers'));
    }
    public function create(){
        $users = User::all();
        $vouchers = Voucher::where('is_active', 1)->get();
        return view('admin.use_vouchers.create', compact('users','vouchers'));
    }
}
