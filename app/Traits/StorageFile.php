<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait StorageFile
{
    /**
     * Xóa file từ storage dựa trên trường của model
     * @param mixed $model Instance của model
     * @param string $field Tên trường chứa đường dẫn file (thumbnail, avatar, ...)
     */
    protected function delete_storage_file($model, string $column): void
    {
        $path = $model->$column;

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}