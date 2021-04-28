<?php

namespace App\Http\Requests\Api\V1\Installations;

use Illuminate\Foundation\Http\FormRequest;

class InstallationStoreRequest extends FormRequest
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
            'app_id' => 'required|string',
            'app_version' => 'required|string',
            'device_type' => 'required|string|in:ios,android',
            'locale' => 'required|string|max:20',
            'timezone' => 'required|timezone',
            'os_version' => 'nullable|string',
            'device_brand' => 'nullable|string',
        ];
    }
}
