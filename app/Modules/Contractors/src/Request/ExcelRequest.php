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
            'start_date' => 'required_without_all:contract,document|date|before_or_equal:final_date',
            'final_date' => 'required_without_all:contract,document|date|after_or_equal:start_date',
            'contract'  =>  'nullable|numeric',
            'document'  =>  'nullable|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'start_date' => 'fecha inicial',
            'final_date' => 'fecha final',
            'contract'  =>  'número de contrato',
            'document'  =>  'número de documento',
        ];
    }
}
