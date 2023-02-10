<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventoFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
//        $tags        = $request->input('tag_arr', []);
//        $zeroFill    = $request->boolean('zeroFill');
//        $pickAllTags = $request->boolean('pickAllTags');

        return [
            'date_start' => 'date',
            'date_end' => 'after_or_equal:date_start',
            'sum_start' => 'integer|min:0',
            'sum_end'   => "integer|gte:sum_start",
            'tag' => 'nullable|array',
        ];
    }
}
