<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|min:5|required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:12|numeric|unique:users,mobile',
            'password' => 'string|required|confirmed'
        ];
    }

    public function messages(){
        return [
            'mobile.digits' => 'Nambari ya simu inatikiwa tarakimu 12 tu.'
        ];
    }
}
