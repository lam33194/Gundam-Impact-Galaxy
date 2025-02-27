<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($message, $statusCode = 200, $data = [])
    {
        return response()->json([
            'message' => $message,
            'status'  => 'success',
            'data'    => $data,
        ], $statusCode);
    }

    /** 
     * 400 (Bad Request) được sử dụng khi server không thể xử lý yêu cầu do lỗi từ phía client
     * EG cú pháp sai, dữ liệu không hợp lệ, hoặc thiếu thông tin cần thiết.  
     */
    protected function error($message, $statusCode = 400, $data = [])
    {
        return response()->json([
            'message' => $message,
            'status'  => 'error',
            'data'    => $data,
        ], $statusCode);
    }

    protected function ok($msg, $data = [])
    {
        return $this->success($msg, 200, $data);
    }

    // 201 (Created) tạo mới thành công tài nguyên
    protected function created($msg, $data = [])
    {
        return $this->success($msg, 201, $data);
    }

    // 204 (No content) thường dùng khi xóa tài nguyên, chắc thế
    protected function no_content()
    {
        return response()->json(null, 204);
    }
    
    // 404 (Not found) tài nguyên/endpoint ko tồn tại
    protected function not_found($msg)
    {
        return $this->error($msg, 404);
    }

    // 403 (Forbidden) ko có quyền truy cập tài nguyên
    protected function forbidden($msg)
    {
        return $this->error($msg, 403);
    }

    // 401 (Unauthorized): Dùng khi xác thực thất bại (sai email/password).
    protected function unauthorize($msg)
    {
        return $this->error($msg, 401);
    }
}
