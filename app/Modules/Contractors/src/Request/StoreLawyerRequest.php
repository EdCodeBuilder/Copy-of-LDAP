<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLawyerRequest extends FormRequest
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
            'document'  =>  'required|numeric|unique:mysql_contractors.contractors,document',
            'name'  =>  'required|string|min:3|max:191',
            'surname'   =>  'required|string|min:3|max:191',
            'email' =>  'required|email',
            'contract_type_id' =>  'required|numeric|exists:mysql_contractors.contract_types,id',
            'contract' =>  [
                'required',
                'string',
                Rule::unique('mysql_contractors.contracts')->where(function ($query) {
                    return $query
                            ->where('contract_type_id', $this->get('contract_type_id'))
                            ->where('contract', $this->get('contract'));
                })
            ],
            'start_date'    =>  'required|date|date_format:Y-m-d|before:final_date',
            'final_date'    =>  'required|date|date_format:Y-m-d|after:start_date',
            'total' =>  'required|numeric',
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
            'contract_type_id'  =>  'tipo de trámite',
            'contract'    =>  'contrato',
            'start_date'    =>  'fecha tentativa de inicio del contrato',
            'final_date'    =>  'fecha tentativa de terminación del contrato',
            'total' =>  'valor del contrato o adición',
        ];
    }
}
