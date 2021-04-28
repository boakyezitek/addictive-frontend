<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Rules\ValidatePassword;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'username' => 'nullable|string|min:3',
            'email' => ['nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id)
            ],
            'notification' => 'nullable|boolean'
        ];
    }
}
