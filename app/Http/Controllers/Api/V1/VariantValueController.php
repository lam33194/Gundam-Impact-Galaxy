<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VariantValueResource;
use App\Models\VariantAttribute;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VariantValueController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'variants',
        'variantAttribute',
    ];

    public function index(Request $request, string $attribute_id)
    {
        $variantAttribute = VariantAttribute::find($attribute_id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $attributeValues = $variantAttribute->variantValues;

        $this->loadRelations($attributeValues, $request, true);

        return $this->ok("Danh sách giá trị thuộc tính $variantAttribute->name", [
            'attributeValues' => VariantValueResource::collection($attributeValues),
        ]);
    }

    public function store(Request $request, string $attribute_id)
    {
        $variantAttribute = VariantAttribute::find($attribute_id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $validatedData = $request->validate([
            'value' => 'required|string|max:50|' .
                Rule::unique('variant_values', 'value')->where('variant_attribute_id', $attribute_id),
        ], [
            'value.required' => 'Vui lòng nhập giá trị thuộc tính',
            'value.string'   => 'Giá trị thuộc tính không hợp lệ',
            'value.unique'   => 'Giá trị ' . $request->input('name') . ' đã tồn tại',
            'value.max'      => 'Giá trị không được vượt quá :max ký tự',
        ]);

        $attributeValue = $variantAttribute->variantValues()->create($validatedData);

        $this->loadRelations($attributeValue, $request, true);

        return $this->created('Tạo giá trị thuộc tính thành công', [
            'attributeValue' => new VariantValueResource($attributeValue),
        ]);
    }

    public function show(Request $request, string $attribute_id, string $value_id)
    {
        $variantAttribute = VariantAttribute::find($attribute_id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $attributeValue = $variantAttribute->variantValues()->find($value_id);

        if (!$attributeValue) return $this->not_found("Giá trị không tồn tại hoặc không thuộc về thuộc tính này");

        $this->loadRelations($attributeValue, $request, true);
        
        return $this->ok("Lấy thông tin giá trị thuộc tính thành công", [
            'attributeValue' => new VariantValueResource($attributeValue),
        ]);
    }

    public function update(Request $request, string $attribute_id, string $value_id)
    {
        $variantAttribute = VariantAttribute::find($attribute_id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $attributeValue = $variantAttribute->variantValues()->find($value_id);

        if (!$attributeValue) return $this->not_found("Giá trị không tồn tại hoặc không thuộc về thuộc tính này");

        if ($attributeValue->variants()->exists()) return $this->conflict('Không thể cập nhật vì có biến thể chứa thuộc tính này');

        // unique: [variant_attribute_id - value]
        $request->validate([
            'value' => "required|string|max:50|" . Rule::unique('variant_values', 'value')->where('variant_attribute_id', $attribute_id)->ignore($value_id),
        ], [
            'value.required' => 'Vui lòng nhập giá trị thuộc tính',
            'value.string'   => 'Giá trị thuộc tính không hợp lệ',
            'value.max'      => 'Giá trị không được vượt quá :max ký tự',
            'value.unique'   => 'Giá trị ' . $request->input('name') . ' đã tồn tại',
        ]);

        $attributeValue->update([
            'variant_attribute_id' => $attribute_id,
            'value' => $request->input('value'),
        ]);

        $this->loadRelations($attributeValue, $request, true);

        return $this->ok('Cập nhật giá trị thuộc tính thành công', [
            'attributeValue' => new VariantValueResource($attributeValue),
        ]);
    }

    public function destroy(Request $request, string $attribute_id, string $value_id)
    {
        $variantAttribute = VariantAttribute::find($attribute_id);

        if (!$variantAttribute) return $this->not_found("Thuộc tính không tồn tại");

        $attributeValue = $variantAttribute->variantValues()->find($value_id);

        if (!$attributeValue) return $this->not_found("Giá trị không tồn tại hoặc không thuộc về thuộc tính này");

        if ($attributeValue->variants()->exists()) return $this->conflict('Không thể xóa vì có biến thể chứa thuộc tính này');

        $attributeValue->delete();

        return $this->no_content();
    }
}
