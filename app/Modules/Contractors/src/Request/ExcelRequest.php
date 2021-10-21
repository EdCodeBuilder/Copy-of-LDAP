<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExcelRequest extends FormRequest
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
            'start_date' => 'required_without_all:contract,document,doesnt_have_secop,doesnt_have_arl,doesnt_have_data|date|before_or_equal:final_date',
            'final_date' => 'required_without_all:contract,document,doesnt_have_secop,doesnt_have_arl,doesnt_have_data|date|after_or_equal:start_date',
            'contract'  =>  'nullable|numeric',
            'document'  =>  'nullable|numeric',
            'doesnt_have_secop'  =>  'nullable|string',
            'doesnt_have_arl'  =>  'nullable|string',
            'doesnt_have_data'  =>  'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'start_date' => 'fecha inicial',
            'final_date' => 'fecha final',
            'contract'  =>  'número de contrato',
            'document'  =>  'número de documento',
            'doesnt_have_secop'  =>  'no cuenta con secop',
            'doesnt_have_arl'  =>  'no cuenta con arl',
            'doesnt_have_data'  =>  'usuarios con información pendiente de actualizar',
        ];
    }
}
