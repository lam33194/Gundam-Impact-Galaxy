<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait StorageFile
{
    /**
     * Xóa file từ storage dựa trên tên cột và instance của model
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $column Tên cột chứa đường dẫn file (thumbnail, avatar, ...)
     */
    protected function delete_storage_file($model, string $column): void
    {
        $path = $model->$column;

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}