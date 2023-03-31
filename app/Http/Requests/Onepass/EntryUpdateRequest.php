<?php

namespace App\Http\Requests\Onepass;

use Illuminate\Foundation\Http\FormRequest;

class EntryUpdateRequest extends FormRequest
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
            'category_id' => 'required|integer|min:1',
            'url' => 'required|string|min:2',

            'email' => 'nullable|email',
            'login' => 'nullable|string|min:2',
            'name'  => 'nullable|string|min:2|max:255',
            'phone' => 'nullable|string|min:2',

            'password' => 'required|string|confirmed|min:2',

            'note' => 'nullable|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'Категория',
            'url' => 'Урл',
            'email' => 'Емейл',
            'login' => 'Логин',
            'name'  => 'Имя',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'note' => 'Примечание',
        ];
    }
}
