<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Helper\Toastr;
use App\Http\Requests\UserVoucherRequest;
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
    public function store(UserVoucherRequest $request)
    {
              try {
                $data = $request->validated();
                  if(!empty($data['user_ids'])&& is_array($data['user_ids'])){
                  $insertData = [];
                  $userIds = [];
                  foreach($data['user_ids'] as $userId)  {
                    if(!UserVoucher::where('user_id', $userId)
                    ->where('voucher_id')
                    ->exists())
                  {
                    $userIds[] = $userId;
                    $insertData[] = 
                    [

                        'user_id' => $userId,
                        'voucher_id' => $data['voucher_id'],
                        'usage_count' =>$data['usage_count'] ?? 1,
                        'created_at' => now(),
                        'updated_at'=> now()


                    ];
                  } 
                }
                if(!empty($insertData)){
                    $vouchers = UserVoucher::insert($insertData);
                    Toastr::success('', 'Thêm mới User thành công');
                    return redirect()->route('admin.user_voucher.index');
                }else{
                    return redirect()->bach()->with('eror','tất cả người dùng đã nhận voucher này');
                }
            }
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một người dùng.');
               
            
              }catch (\Throwable $th) {
                
              return redirect()->back()->with('error','đã xảy ra lỗi' . $th->getMessage());
              }
    }

}
