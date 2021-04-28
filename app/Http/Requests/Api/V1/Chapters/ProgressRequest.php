<?php

namespace App\Http\Requests\Api\V1\Chapters;

use Illuminate\Foundation\Http\FormRequest;

class ProgressRequest extends FormRequest
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
            'timestamp' => 'required|integer',
            'finished'  => 'nullable|boolean'
        ];
    }
}
