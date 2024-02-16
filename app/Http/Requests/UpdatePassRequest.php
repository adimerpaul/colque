<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassRequest extends FormRequest
{
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

            'password' => 'required|min:8|max:30|confirmed',

        ];
    }
}
