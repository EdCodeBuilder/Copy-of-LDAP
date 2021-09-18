<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isA('superadmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  =>  'required|string|max:191|unique:mysql_ldap.abilities,name,'.$this->route('permission')->id.',id',
            'title' =>  'required|string|max:191',
            'entity_type' =>  'required|string|max:191',
        ];
    }
}
