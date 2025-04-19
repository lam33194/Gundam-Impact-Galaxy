<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Helper\Toastr;
use App\Http\Requests\UserVoucherRequest;
use App\Http\Requests\V1\UserUpdateRequest;
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
        return view('admin.user_vouchers.create', compact('users','vouchers'));
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
                    Toastr::success('', 'Thêm mới User Voucher thành công');
                    return redirect()->route('admin.user_vouchers.index');
                }else{
                    return redirect()->bach()->with('eror','tất cả người dùng đã nhận voucher này');
                }
            }
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một người dùng.');
               
            
              }catch (\Throwable $th) {
                
              return redirect()->back()->with('error','đã xảy ra lỗi' . $th->getMessage());
              }
    }
    public function edit(UserVoucher $User_voucher){
        $users = User::all();
        $vouchers = Voucher::all();
        return view('admin.user_vouchers.edit', compact('User_voucher','users','vouchers'));
        
    }
    public function update(UserVoucherRequest $request ,UserVoucher $User_voucher){
        try {
            $data = $request->validated();
            $User_voucher->update($data);
            return redirect()->route('admin.user_vouchers.index')->with('success','cập nhật user voucher thành công');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Đã xảy ra lỗi' . $th->getMessage());
        }
    }
    public function destroy(UserVoucher $User_voucher){
        try {
            $User_voucher ->delete();
            return redirect()->route('admin.user_vouchers.index')->with('success','xóa User voucher thành công');
        } catch (\Throwable $th) {
          return back()->with('error','xóa bị lỗi'.$th->getMessage());
        }
    }

}
