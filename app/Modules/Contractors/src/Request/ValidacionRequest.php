<?php


namespace App\Modules\Contractors\src\Request;


use Illuminate\Foundation\Http\FormRequest;
class ValidacionRequest extends FormRequest{
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
        return ["document"=>"required|numeric", "year"=>"required|numeric", "code"=>"required|numeric"];
    }
}
