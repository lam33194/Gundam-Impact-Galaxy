<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait LoadRelations
{
    /**
     * Load relations based on 'include' query parameter.
     *
     * @param LengthAwarePaginator | Eloquent\Builder | Eloquent\Model $model
     * @param \Illuminate\Foundation\Http\FormRequest | Request $request
     * @param bool $loadMissing
     * @return void
     */
    protected function loadRelations($model, Request $request, bool $isInstance = false)
    {
        // Kiểm tra xem controller có khai báo $validRelations không
        if (!isset($this->validRelations)) {
            throw new \Exception('Controller sử dụng trait này phải khai báo thuộc tính $validRelations');
        }

        // Eager load relations nếu có param 'include' trên URL
        if ($request->has('include')) {

            // Gộp chuỗi thành mảng (vd: ?include=reviews,abc => ['reviews','abc'])
            $queryRelations = explode(',', $request->query('include'));

            // Lọc các quan hệ hợp lệ và giữ nguyên cấu trúc lồng nhau
            $validRequestedRelations = array_filter($queryRelations, function ($relation) {
                return in_array($relation, $this->validRelations);
            });

            if (!empty($validRequestedRelations)) {
                if ($isInstance) {
                    $model->load($validRequestedRelations);
                } else {
                    $model->with($validRequestedRelations);
                }
            }
        }
    }
}