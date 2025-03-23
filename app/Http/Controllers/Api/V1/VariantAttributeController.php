<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VariantAttributeResource;
use App\Models\VariantAttribute;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class VariantAttributeController extends Controller
{
    use ApiResponse;

    public function index()
    {
        // auto eager load ko cần query param
        $variantAttributes = VariantAttribute::with('variantValues')->get();

        return $this->ok('Lấy danh sách thuộc tính biến thể thành công', [
            'variantAttributes' => VariantAttributeResource::collection($variantAttributes),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:variant_attributes'
        ], [
            'name.required' => 'Vui lòng nhập tên thuộc tính',
            'name.string'   => 'Tên thuộc tính không hợp lệ',
            'name.max'      => 'Tên thuộc tính không được vượt quá :max ký tự',
            'name.unique'   => 'Thuộc tính ' . $request->input('name') . ' đã tồn tại',
        ]);

        $variantAttribute = VariantAttribute::create($validatedData);

        $variantAttribute->load('variantValues');

        return $this->created('Tạo thuộc tính thành công', [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function show(string $id)
    {
        $variantAttribute = VariantAttribute::with('variantValues')->find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        return $this->ok("Lấy thông tin thuộc tính thành công", [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $variantAttribute = VariantAttribute::find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $hasVariants = $variantAttribute->variantValues()
            ->whereHas('variants', function ($query) {
                $query->where('variants.id', '>', 0);
            })
            ->exists();

        if ($hasVariants) return $this->conflict('Không thể cập nhật vì có biến thể chứa thuộc tính này');

        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:variant_attributes,name,' . $id
        ], [
            'name.required' => 'Vui lòng nhập tên thuộc tính',
            'name.string'   => 'Tên thuộc tính không hợp lệ',
            'name.max'      => 'Tên thuộc tính không được vượt quá :max ký tự',
            'name.unique'   => 'Thuộc tính ' . $request->input('name') . ' đã tồn tại',
        ]);

        $variantAttribute->update($validatedData);

        $variantAttribute->load('variantValues');

        return $this->ok('Cập nhật thuộc tính thành công', [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function destroy(string $id)
    {
        $variantAttribute = VariantAttribute::find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        // C1: dễ đọc nhưng hiệu suất kém hơn, phải eager load $variantAttribute::with('variantValues.variants')
        // foreach ($variantAttribute->variantValues as $value) {
        //     if ($value->variants()->exists()) return ...
        // }

        // C2 dùng query builder
        $hasVariants = $variantAttribute->variantValues()
            ->whereHas('variants', function ($query) {
                $query->where('variants.id', '>', 0);
            })
            ->exists();

        if ($hasVariants) return $this->conflict('Không thể xóa vì có biến thể chứa thuộc tính này');

        $variantAttribute->delete();

        return $this->no_content();
    }
}
