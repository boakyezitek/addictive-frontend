<?php

namespace App\Http\Requests\Api\V1\Bookmarks;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'nullable|string',
            'from' => 'required|integer',
            'to' => 'nullable|integer',
            'reaction' => 'nullable|string'
        ];
    }
}
