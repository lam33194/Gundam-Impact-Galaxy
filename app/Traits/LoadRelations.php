<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait LoadRelations
{
    /**
     * Load relations based on 'include' query parameter.
     *
     * @param mixed $model
     * @param Request $request
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

            foreach ($queryRelations as $relation) {
                
                // Nếu nhập tên quan hệ không hợp lệ, chuyển đến vòng lặp tiếp theo
                if (!in_array($relation, $this->validRelations)) {
                    continue;
                }

                if ($isInstance) {
                    $model->loadMissing($relation);
                } else {
                    // Eager load
                    $model->with($relation);
                }
            }
        }
    }
}