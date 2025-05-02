<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddressStoreRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $addresses = auth('sanctum')->user()->addresses;

        return $this->created("Lấy danh sách địa chỉ giao hàng thành công", $addresses);
    }

    public function show(string $id)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $address = $user->addresses()->find($id);

        if (!$address) return $this->not_found('Địa chỉ không tồn tại');        

        return $this->ok("Lấy dữ liệu địa chỉ giao hàng thành công", $address);
    }

    public function store(AddressStoreRequest $request)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $data = $request->validated();

        if ($data['is_primary']) {
            $user->addresses()->update([
                'is_primary' => false,
            ]);
        }

        $address = $user->addresses()->create($data);

        return $this->created("Thêm địa chỉ giao hàng thành công", $address);
    }

    public function update(AddressStoreRequest $request, string $id)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $address = $user->addresses()->find($id);

        if (!$address) return $this->not_found('Địa chỉ không tồn tại');

        $data = $request->validated();

        if ($data['is_primary']) {
            $user->addresses()->update([
                'is_primary' => false,
            ]);
        }

        $address->update($data);

        return $this->ok('Cập nhật địa chỉ giao hàng thành công', $address);
    }

    public function destroy(string $id)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $address = $user->addresses()->find($id);

        if (!$address) return $this->not_found('Địa chỉ không tồn tại');

        $address->delete();

        return $this->no_content();
    }
}
