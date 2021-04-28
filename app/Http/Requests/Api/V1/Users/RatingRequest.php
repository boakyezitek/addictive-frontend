<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Rules\ValidatePassword;
use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
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
            'liked' => 'required|boolean'
        ];
    }

}
