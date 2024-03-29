<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAvatarCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'avatar' => ['required', 'image', 'max:2048', 'dimensions:min_width:100,min_height:100,max_width:1920,max_height:1080'],
        ];
    }

    public function attributes()
    {
        return [
            'avatar' => 'Аватар'
        ];
    }
}
