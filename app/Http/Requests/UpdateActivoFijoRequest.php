<?php

namespace App\Http\Requests;

use App\Models\Activo\ActivoFijo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivoFijoRequest extends FormRequest
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
        $rules = ActivoFijo::rules(false);

        return $rules;
    }
}
