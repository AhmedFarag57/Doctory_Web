<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * 
     * Get Users except yourself
     * 
     * @return JsonRepsonse
     */
    public function index() : JsonResponse {

        $users = User::where('id', '!=', auth()->user()->id)->get();

        return $this->success($users);
    }

    public function blockUser($id)
    {
        $user = User::find($id);
        
        $user->update([
            'blocked' => !$user->blocked
        ]);

        return redirect()->back();
    }

}
