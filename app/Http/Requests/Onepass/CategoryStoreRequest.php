<?php

namespace App\Http\Requests\Onepass;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:2',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Имя',
            'image' => 'Картинка',
        ];
    }
}
