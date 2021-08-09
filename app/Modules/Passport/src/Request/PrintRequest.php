<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class PrintRequest extends FormRequest
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
            'passports'  => 'required|array',
            'passports.*.passport'  => 'required|numeric',
            'passports.*.cade_id'   => 'required|numeric|exists:mysql_passport.tbl_supercades,i_pk_id',
            'passports.*.denounce'  => 'nullable|string|max:80',
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
            'passports' =>  __('passport.validations.passport'),
            'passports.*.passport'  => __('passport.validations.passport'),
            'passports.*.cade_id'  => __('passport.validations.supercade_name'),
            'passports.*.denounce'  => __('passport.validations.denounce'),
        ];
    }
}
