<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VariantAttributeResource;
use App\Models\VariantAttribute;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;

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
            'name.required' => 'Tên thuộc tính không được để trống',
            'name.unique'   => 'Thuộc tính ' . $request->input('name') . ' đã tồn tại',
            'name.max'      => 'Tên thuộc tính quá dài',
        ]);

        $variantAttribute = VariantAttribute::create($validatedData);

        return $this->created('Tạo thuộc tính thành công', [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function show(string $id)
    {
        $variantAttribute = VariantAttribute::find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $variantAttribute->load('variantValues');

        return $this->ok("Lấy thông tin thuộc tính thành công", [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $variantAttribute = VariantAttribute::find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:variant_attributes,name,' . $id
        ], [
            'name.required' => 'Tên thuộc tính không được để trống',
            'name.unique'   => 'Thuộc tính ' . $request->input('name') . ' đã tồn tại',
            'name.max'      => 'Tên thuộc tính quá dài',
        ]);

        $variantAttribute->update($validatedData);

        return $this->ok('Cập nhật thuộc tính thành công', [
            'variantAttribute' => new VariantAttributeResource($variantAttribute),
        ]);
    }

    public function destroy(string $id)
    {
        $variantAttribute = VariantAttribute::find($id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $variantAttribute->delete();

        return $this->no_content();
    }
}
