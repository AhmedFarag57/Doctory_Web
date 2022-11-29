<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

use function PHPUnit\Framework\isNull;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Doctor::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' =>'required|numeric',
            'clinic_address' =>'required|string|max:255',
            'certification' =>'required|string|max:255',
            'session_price' => 'required|numeric'
        ]);

        return Doctor::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Doctor::find($id);
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
        $doctor = Doctor::find($id);

        if(isNull($doctor)){
            $this->error('The doctor does not exist');
        }

        $data = $request->validate([
            'clinic_address' => 'required|string|max:255',
            'certification' => 'required|string|max:255',
            'session_price' => 'required|numeric'
        ]);

        $doctor->update([
            'clinic_address' => $data['clinic_address'],
            'certification' => $data['certification'],
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
    public function destroy($id)
    {
        Doctor::destroy($id);
    }

    /**
     * Search for the name
     *
     * @param  str  $Name
     * @return \Illuminate\Http\Response
     */
    public function search($Name)
    {
        return Doctor::where('Name','like','%' .$Name. '%')->get();
    }
}
