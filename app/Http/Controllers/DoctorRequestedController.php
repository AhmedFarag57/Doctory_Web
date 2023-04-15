<?php

namespace App\Http\Controllers;

use App\Models\Doctor;

class DoctorRequestedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::where('accepted', 0)->orderBy('id', 'asc')->paginate(10);
        return view('backend.admin.doctors.request.index')->with('doctors', $doctors);
    }

    /**
     * Show specific doctor request
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = Doctor::find($id);
        return view('backend.admin.doctors.request.show')->with('doctor', $doctor);
    }

    /**
     * Accept a doctor request
     * 
     * @return \Illuminate\Http\Response
     */
    public function acceptRequest($id)
    {
        $doctor = Doctor::find($id);

        $doctor->update([
            'accepted' => true
        ]);

        return redirect()->route('doctors.index');
    }
}
