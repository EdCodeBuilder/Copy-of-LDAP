<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class ExcelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isA(...Roles::all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'passport'  => 'nullable|numeric',
            'find_old'  => 'nullable',
            'start_date'  => 'nullable|date|before_or_equal:final_date',
            'final_date'  => 'nullable|date|after_or_equal:start_date',
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
            'passport' =>  __('passport.validations.passport'),
            'start_date'  => __('passport.validations.start_date'),
            'final_date'  => __('passport.validations.final_date'),
            'find_old'  => __('passport.validations.find_old'),
        ];
    }
}
