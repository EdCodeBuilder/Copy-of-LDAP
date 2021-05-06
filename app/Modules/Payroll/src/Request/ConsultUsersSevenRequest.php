<?php


namespace App\Modules\Payroll\src\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsultUserSevenRequest extends FormRequest
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
            // 'username'          => 'required|string',
            'year'          => 'required|numeric|min:1900|max:'.now()->year,
            'month'          => 'required|numeric|min:1|max:12',
            'file_docs'          => 'required',
            // 'docs'          => 'required',
            // 'month'   => 'nullable|date|date_format:Y-m-d',
            // 'ou'                => 'nullable|string',
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
            'year'  =>  'año liquidación',
            'month'  =>  'mes liquidción',
            'file_docs'  =>  'archivo documentos',
        ];
    }
}
