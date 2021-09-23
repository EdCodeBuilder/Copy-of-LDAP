<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del permiso a crear separado por guiones y debe ser único. Example: 'crear-equipamiento-parque'
 * @bodyParam title string required Título o descripción del permiso máximo 191 caracteres. Example: Crear equipamientos de parques
 * @bodyParam entity_type string required Modelo o entidad a la que está asociado el permiso. Example: App\Models\Equipamiento
 */
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
