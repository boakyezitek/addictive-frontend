<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Rules\ValidatePassword;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'password' => ['required', 'confirmed', 'regex:+(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[,.;:#?!@$^%&*-]).{6,}+']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.different' => 'Une erreur est survenue, rentrez un nouveau mot de passe différent de l\'ancien',
        ];
    }
}
