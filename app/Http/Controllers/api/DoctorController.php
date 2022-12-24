<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

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
        $doctors = Doctor::all();

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
        $request->validate([
            'user_id' =>'required|numeric',
            'clinic_address' =>'required|string|max:255',
            'certifications' =>'required|string|max:255',
            'session_price' => 'required|numeric'
        ]);

        $doctor = Doctor::create($request->all());

        return $this->success($doctor, 'Doctor creatde successfully');
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

        if(isNull($doctor)){
            $this->error('Doctor with this ID does not exist');
        }

        $data = $request->validate([
            'clinic_address' => 'required|string|max:255',
            'certifications' => 'required|string|max:255',
            'session_price' => 'required|numeric'
        ]);

        $doctor->update([
            'clinic_address' => $data['clinic_address'],
            'certifications' => $data['certifications'],
            'session_price' => $data['session_price']
        ]);

        return $this->success($doctor, 'Doctor updated successfully');
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
}
