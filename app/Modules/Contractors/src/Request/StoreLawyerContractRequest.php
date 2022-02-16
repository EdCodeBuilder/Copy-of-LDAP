<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLawyerContractRequest extends FormRequest
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
            'contract_type_id' =>  'required|numeric|exists:mysql_contractors.contract_types,id',
            'contract_year' =>  'required|numeric|min:2019|max:2999',
            'contract' =>  [
                'required',
                'string',
                /*
                Rule::unique('mysql_contractors.contracts')->where(function ($query) {
                    $contract_number = str_pad($this->get('contract'), 4, '0', STR_PAD_LEFT);
                    $contract = toUpper("IDRD-CTO-{$contract_number}-{$this->get('contract_year')}");
                    return $query
                        ->where('contract_type_id', $this->get('contract_type_id'))
                        ->where('contract', $contract);
                })
                */
            ],
            'start_date'    =>  'required|date|date_format:Y-m-d|before:final_date',
            'final_date'    =>  'required|date|date_format:Y-m-d|after:start_date',
            'start_suspension_date'    =>  'nullable|required_if:contract_type_id,3|date|date_format:Y-m-d|before:final_suspension_date',
            'final_suspension_date'    =>  'nullable|required_if:contract_type_id,3|date|date_format:Y-m-d|after:start_suspension_date',
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
            'start_suspension_date'    =>  'fecha de inicio de suspención',
            'final_suspension_date'    =>  'fecha de terminación de suspención',
            'total' =>  'valor del contrato o adición',
        ];
    }
}
