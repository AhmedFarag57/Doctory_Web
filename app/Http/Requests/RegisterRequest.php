<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => [
                'required',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
            'ssn' => 'required|string|min:14|max:14',
            'gender' => 'required',
            'phone_number' => 'required|min:11|max:11',
            'date_of_birth' => 'required',
            'isDoctor' => 'required',
        ];
    }
}
