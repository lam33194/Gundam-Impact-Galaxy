<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Http\Requests\VoucherRequest;

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
}