<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index() : View
    {
        $doctors = Doctor::where('accepted', 1)->orderBy('id', 'asc')->paginate(10);
        return view('backend.admin.doctors.index')->with('doctors', $doctors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() : View
    {
        return view('backend.admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:255',
            'phone' => 'nullable|min:11|max:11',
            'session_price' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isDoctor' => true,
        ]);

        if($request->hasFile('profile_picture')){
            $profile = Str::slug($request->name) . '-' . $user->id . '.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        }
        else {
            $profile = 'avatar.png';
        }

        $user->update([
            'profile_picture' => $profile
        ]);

        $user->doctor()->create([
            'session_price' => $request->session_price,
            'phone' => $request->phone
        ]);

        $user->assignRole('Doctor');

        return redirect()->route('doctors.request.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor =  Doctor::find($id);
        return view('backend.admin.doctors.show')->with('doctor', $doctor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doctor = Doctor::find($id);

        return view('backend.admin.doctors.edit')->with('doctor', $doctor);
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

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,'.$doctor->user_id,
            'session_price' => 'required|numeric',
            'phone' => 'nullable|min:11|max:11',
        ]);

        $user = User::findOrFail($doctor->user_id);

        if($request->hasFile('profile_picture')){
            $profile = Str::slug($request->name) . '-' . $user->id . '.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        }
        else {
            $profile = $user->profile_picture;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $profile
        ]);


        $user->doctor()->update([
            'phone' => $request->phone,
            'session_price' => $request->session_price
        ]);

        return redirect()->route('doctors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $user = User::findOrFail($doctor->user_id);

        $user->doctor()->delete();

        $user->removeRole('Doctor');
        if ($user->delete()) {
            if($user->profile_picture != 'avatar.png') {
                $image_path = public_path() . '/images/profile/' . $user->profile_picture;
                if (is_file($image_path) && file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        return redirect()->route('doctors.index');
    }
}
