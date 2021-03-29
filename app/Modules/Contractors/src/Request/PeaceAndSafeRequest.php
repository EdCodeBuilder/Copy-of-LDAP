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
            'name'          => 'required|string',
            'document'      => 'required|numeric',
            'contract'      => 'required|numeric',
            'year'          => 'required|date|date_format:Y',
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
            'contract'    =>  'contrato',
            'virtual_file'    =>  'expediente virtual',
        ];
    }
}
