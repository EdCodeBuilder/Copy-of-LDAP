<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
class ValidacionUsuarioRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function rules(){
        return ["document"=>"required|numeric", "birthdate"=>"required|date|date_format:Y-m-d", "year"=>"required|numeric|date_format:Y"];
    }
}
