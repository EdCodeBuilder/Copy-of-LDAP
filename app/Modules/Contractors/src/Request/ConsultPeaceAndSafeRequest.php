<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsultPeaceAndSafeRequest extends FormRequest
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
            'document'      => 'required_with:year,contract|nullable|numeric',
            'contract'      => 'required_with:year,document|nullable|numeric',
            'year'          => 'required_with:contract,document|nullable|numeric|min:1900|max:'.now()->year,
            'token'         => 'required_without:contract,year|nullable|string|exists:mysql_contractors.certifications,token',
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
            'document'    =>  'número de documento',
            'contract'    =>  'contrato',
            'year'    =>  'año de contrato',
            'token'    =>  'código de verificación',
        ];
    }
}
