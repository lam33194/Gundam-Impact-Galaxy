<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        return response()->json([
            'message' => 'OK',
            'status' => 200,
            'data' => [
                'users' => UserResource::collection($users)
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'message' => 'OK',
            'status' => 200,
            'data' => [
                'user' => new UserResource($user),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 404,
            ], 404);
        }
 
        $user->delete();
         
        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);
    }
}
