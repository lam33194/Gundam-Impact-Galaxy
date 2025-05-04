<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->ok('Lấy danh sách thẻ thành công', Tag::all());
    }
}
