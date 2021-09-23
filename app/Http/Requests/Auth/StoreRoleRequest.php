<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del rol a crear separado por guiones y debe ser único. Example: 'administrador-parque'
 * @bodyParam title string required Título o descripción del rol máximo 191 caracteres. Example: Administrador de parques
 */
class StoreRoleRequest extends FormRequest
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
            'name'  =>  'required|string|max:191|unique:mysql_ldap.roles,name',
            'title' =>  'required|string|max:191',
        ];
    }
}
