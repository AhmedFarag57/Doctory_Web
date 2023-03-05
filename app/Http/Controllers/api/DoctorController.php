<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use function PHPUnit\Framework\isNull;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : JsonResponse
    {
        $doctors = DB::table('doctors')
            ->select('*')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->get();

        return $this->success($doctors);
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
            'password' => 'required|min:8|max:255',
            'ssn' => 'required|string|min:14|max:14',
            //'phone_number' => 'string|max:255',
            //'date_of_birth' => 'required|date',
            //'gender' => 'required|string|max:6|min:4',
            //'profile_picture' => 'nullable|string',
            'isDoctor' => 'required|boolean',
            //'clinic_address' =>'string|max:255',
            //'session_price' => 'required|numeric'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ssn' => $request->ssn,
            //'phone_number' => $request->phone_number,
            //'date_of_birth' => $request->date_of_birth,
            //'gender' => $request->gender,
            'isDoctor' => $request->isDoctor,
        ]);

        /*
        if($request->hasFile('profile_picture')){
            $profile = Str::slug($request->name) . '-' . $user->id . '.' . $request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);

            $user->update([
                'profile_picture' => $profile
            ]);
        }
        */

        $user->doctor()->create(/*[
           'clinic_address' => $request->clinic_address,
            'session_price' => $request->session_price,
        ]*/);

        //$user->assignRole('Doctor');

        return $this->success(null, 'Doctor created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : JsonResponse
    {
        $doctor = Doctor::find($id);

        if($doctor){
            $doctor = User::where('id', $doctor->user_id)->with('doctor')->get();
            return $this->success($doctor);
        }

        return $this->error('Doctor with this ID does not exist');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) : JsonResponse
    {
        $doctor = Doctor::find($id);
        $user = User::findOrFail($doctor->user_id);

        if(isNull($doctor)){
            $this->error('Doctor with this ID does not exist');
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'ssn' => 'required|string|min:14|max:14',
            'phone_number' => 'string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|max:6|min:4',
            'profile_picture' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'clinic_address' =>'string|max:255',
            'session_price' => 'required|numeric'
        ]);

        if($request->hasFile('profile_picture')){
            $profile = Str::slug($request->name) . '-' . $user->id . '.' . $request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        }
        else {
            $profile = $user->profile_picture;
        }

        $user->update([
           'name' => $request->name,
           'email' => $request->email,
           'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'profile_picture' => $profile,
        ]);

        $user->doctor()->update([
            'clinic_address' => $request->clinic_address,
            'session_price' => $request->session_price
        ]);

        return $this->success($user, 'Doctor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : JsonResponse
    {
        $doctor = Doctor::find($id);
        if(isNull($doctor)){
            $this->error('Doctor with this ID does not exist');
        }

        $doctor->destroy();

        return $this->success(null, 'Doctor deleted successfully');
    }

    /**
     * Search for the name
     *
     * @param  str  $Name
     * @return \Illuminate\Http\Response
     */
    public function search($Name) : JsonResponse
    {
        $doctor = Doctor::where('Name','like','%' .$Name. '%')->get();

        if($doctor){
            return $this->success($doctor);
        }

        return $this->error('Doctor with this ID does not exist');
    }

    /**
     * Count the number of appointments for a doctor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function appointmentsCount($id) : JsonResponse  {
        $tmp = DB::table('users')
            ->select('*')
            ->join('doctors', 'users.id', '=', 'doctors.user_id')
            ->where('users.id', '=', $id)
            ->first();


        $count = DB::table('appointments')
            ->select('doc_id')
            ->where('doc_id', '=', $tmp->id)
            ->count('doc_id');


        $data = [
            'count' => $count,
        ];

        return $this->success($data);

    }
}
