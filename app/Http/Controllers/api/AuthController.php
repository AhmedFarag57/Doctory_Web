<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     *
     * Register a User
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse {

        // Validate the data from the request
        // App/Http/Request/RegisterRequest
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);


        $user = User::create($data);


        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([

            'user' => $user,
            'token' => $token->plainTextToken,

        ], 'User has been register successfully.', 201);
    }

    /**
     *
     * Logins a User
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse {

        // Validate the data from the request
        // App/Http/Request/LoignRequest
        $data = $request->validated();

        // Check the valid email first
        $user = User::where('email', $data['email'])->first();

        // Check the password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->error('Invalid Email or Password', 422);
        }

        $model = null;

        if($user->hasRole('Doctor')){
            $model = Doctor::where('user_id', $user->id)->first();
        }
        else if($user->hasRole('Patient')){
            $model = Patient::where('user_id', $user->id)->first();
        }


        $token = $user->createToken(User::USER_TOKEN);


        return $this->success([

            'user' => $user,
            'model' => $model,
            'token' => $token->plainTextToken,

        ], 'Login successfully', 201);
    }


    /**
     *
     * Logins a User with token
     *
     * @return JsonResponse
     */
    public function loginWithToken(): JsonResponse {

        return $this->success(auth()->user(), 'Login successfully', 201);
    }

    /**
     *
     * Logouts a User
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse {

        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout successfully');
    }
}
