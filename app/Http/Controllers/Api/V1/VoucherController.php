<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    use ApiResponse, LoadRelations;

    // protected $validRelations = [
    //     'user',
    // ];

    public function index(Request $request)
    {
        $vouchers = Voucher::latest()->get();

        return $this->ok('Lấy danh sách vouchers thành công', $vouchers);
    }

    public function show(string $code)
    {
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) return $this->not_found('Không tìm thấy voucher');

        return $this->ok('Lấy chi tiết voucher thành công', $voucher);
    }
}
