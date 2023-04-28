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

        if($data['app'] == 'doctor')
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'isDoctor' => true,
            ]);
            $user->doctor()->create();
            $user->assignRole('Doctor');
            return $this->success(null, 'Doctor created successfully');
        }
        else if($data['app'] == 'patient')
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'isDoctor' => false,
            ]);
            $user->patient()->create();
            $user->assignRole('Patient');
            return $this->success(null, 'Patient created successfully');
        }

        return $this->error('Error in creating User');
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

        if($request->app == 'doctor' && $user->hasRole('Doctor'))
        {
            $model = Doctor::where('user_id', $user->id)->first();
        }
        else if($request->app == 'patient' && $user->hasRole('Patient'))
        {
            $model = Patient::where('user_id', $user->id)->first();
        }
        else
        {
            return $this->error('You can\'t login with this email to this app');
        }

        $token = $user->createToken(User::USER_TOKEN);

        unset($user->roles);

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
