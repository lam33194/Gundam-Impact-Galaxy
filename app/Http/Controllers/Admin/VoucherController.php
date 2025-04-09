<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Http\Requests\VoucherRequest;
use PhpParser\Node\Stmt\TryCatch;

class VoucherController extends Controller
{
    private const PATH_VIEW = 'admin.vouchers.';
    public function index(Request $request)
    {
        $search = $request->input('search');

        $vouchers = Voucher::when($search, function ($query, $search) {

            return $query->where('code', 'like', '%' . $search . '%');
        })->latest('id')->paginate(5);

        return view(self::PATH_VIEW . __FUNCTION__, compact('vouchers', 'search'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }
    public function store(VoucherRequest $request)
    {
           try
           {
              $code = $request->code ?? strtoupper(Str::random(10));
              while(Voucher::where('code',$code)->exists())
              {
                    $code = strtoupper(Str::random(10));
              }

              $data = $request->all();

              $data['code'] = $code;
              $data['discount'] = $data['discount'] ?? 0;
              $vouchers = Voucher::create($data);
              session()->forget('voucher_code');
              Toastr::success('',"them ma giam gia thanh cong");
              return redirect()->route('admin.vouchers.index');


           }
           catch(\Throwable $th)
           {
              session()->flash('voucher_code', $request->code);
              return redirect()->back()->with('error','da xay ra loi'.$th->getMessage());
           }
    }
    public function show(Voucher $voucher){
        return view(self::PATH_VIEW. __FUNCTION__, compact('voucher'));
    }
    public function edit(Voucher $voucher)
    {
    $voucher->start_date_time = $voucher->start_date_time instanceof \Carbon\Carbon
    ? $voucher->start_date_time
    : \Carbon\Carbon::parse($voucher->start_date_time);
    return view('admin.vouchers.edit',compact('voucher'));
    }
    public function update(VoucherRequest $request , Voucher $voucher )
    {       
        try {
            $data = $request->all();
               $data['discount'] = (int) str_replace(',','', $request->input('discount'));
               $voucher -> update($data);
               return redirect()->route('admin.vouchers.index')->with('success','cap nhat ma giam gia thanh cong');
        } catch (\Throwable $th) {
           return redirect()->back()->with('errror','da xay ra loi'. $th->getMessage());
        }
        

    }
}