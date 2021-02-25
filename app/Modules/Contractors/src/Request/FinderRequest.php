<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinderRequest extends FormRequest
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
            'document'  => 'required|numeric|exists:mysql_contractors.contractors,document',
        ];
    }

    public function messages()
    {
        return [
            'document.exists'   => 'El número de documento no existe en la base de datos puede continuar con la creación del usuario.'
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
        ];
    }
}
