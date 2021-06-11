<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PeaceAndSafeRequest extends FormRequest
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
            /*
            'name'          => 'required|string',
            'surname'       => 'required|string',
            */
            'document'      => 'required|numeric',
            'contract'      => 'required|numeric',
            'year'          => 'required|numeric|min:1900|max:'.now()->year,
            'virtual_file'  => 'nullable|string',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'document'  =>  'número de documento',
            'name'  =>  'nombres',
            'surname'  =>  'apellidos',
            'contract'    =>  'contrato',
            'year'    =>  'año de contrato',
            'virtual_file'    =>  'expediente virtual',
        ];
    }
}
