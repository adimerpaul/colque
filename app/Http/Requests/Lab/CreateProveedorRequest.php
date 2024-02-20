<?php

namespace App\Http\Requests\Lab;

use App\Models\Lab\Proveedor;
use Illuminate\Foundation\Http\FormRequest;

class CreateProveedorRequest extends FormRequest
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
        return Proveedor::$rules;
    }
}
