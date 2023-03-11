<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : JsonResponse
    {
        $patients = Patient::all();
        return $this->success($patients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => [
                'required',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
            //'phone_number' => 'string|max:255',
            //'date_of_birth' => 'required|date',
            //'gender' => 'required|string|max:6|min:4',
            'ssn' => 'required|string|min:14|max:14',
            //'profile_picture' => 'nullable|string',
            //'isDoctor' => 'required|boolean',
            //'fake_name' => 'string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //'phone_number' => $request->phone_number,
            //'date_of_birth' => $request->date_of_birth,
            //'gender' => $request->gender,
            //'isDoctor' => false,
            'ssn' => $request->ssn,
        ]);
/*
        if($request->hasFile('profile_picture')){
            
            $profile = Str::slug($request->name) . '-' . $user->id . '.' . $request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);

            $user->update([
                'profile_picture' => $profile
            ]);
        }
        

        $user->patient()->create([
            'fake_name' => $request->fake_name
        ]);

        //$user->assignRole('Patient');
*/
        return $this->success($user, 'Patient created successfully');
        
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
