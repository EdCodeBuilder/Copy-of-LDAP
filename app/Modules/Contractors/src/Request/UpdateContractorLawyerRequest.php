<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractorLawyerRequest extends FormRequest
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
            'document_type_id'  =>  'required|numeric|exists:mysql_ldap.document_types,id',
            'document'  =>  'required|numeric|unique:mysql_contractors.contractors,document,'.$this->route('contractor')->id.',id',
            'name'  =>  'required|string|min:3|max:191',
            'surname'   =>  'required|string|min:3|max:191',
            'email' =>  'required|email',
            'phone' =>  'required|numeric',
            'notify'    => 'nullable'
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
            'document_type_id'  =>  'tipo de documento',
            'document'  =>  'número de documento',
            'name'  =>  'nombres',
            'surname' =>  'apellidos',
            'email' =>  'correo personal',
            'phone' =>  'telefono de contácto',
        ];
    }
}
