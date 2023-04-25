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
            ->select([
                'doctors.*',
                'users.name'
            ])
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->where('accepted', 1)
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
            'password' => 'required|min:8|max:255|confirmed',
            'phone' => 'nullable|min:11|max:11',
            'session_price' => 'required|numeric'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isDoctor' => true,
        ]);

        $user->doctor()->create([
            'session_price' => $request->session_price,
            'phone' => $request->phone
        ]);

        $user->assignRole('Doctor');

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
            'password' => 'required|min:8|max:255',
            'phone' => 'nullable|min:11|max:11',
            'session_price' => 'required|numeric'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $user->doctor()->update([
            'session_price' => $request->session_price,
            'phone' => $request->phone
        ]);

        return $this->success(null, 'Doctor updated successfully');
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

        $count = DB::table('appointments')
            ->select('doc_id')
            ->where('doc_id', $id)
            ->count('doc_id');

        $data = [
            'count' => $count
        ];

        return $this->success($data);


    }

    public function doctorTimeStore(Request $request, $id) : JsonResponse
    {   
        $this->validate($request, [
            'dates' => 'required',
            'timesFrom'=> 'required',
            'timesTo' => 'required',
        ]);
        
        
        

        return $this->success($request->dates, 'The times recorded successfully');
    }

    public function doctortime($id) : JsonResponse  
    {
        $date = date("Y-m-d");

        $times= DB::table('doctor_time')
            ->select([
                'doctor_time.id',
                'doctor_time.time_from',
                'doctor_time.time_to',
                'doctor_time.date'
            ])
            ->where('doc_id', '=', $id)
            ->where('reserved', '=', 0)
            ->where('date', $date)
            ->get();

        return $this->success($times);
    }
}
