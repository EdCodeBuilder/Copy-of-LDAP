<?php

namespace App\Http\Requests\GlobalData;

use Illuminate\Foundation\Http\FormRequest;

class AnimationsRaquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file'  =>  [
                'required',
                'file',
                function($attribute, $value, $fail) {
                    $ext = $this->file('file')->getClientOriginalExtension();
                    if ($ext != 'json') {
                        $fail("El archivo debe ser en formato_ json.");
                    }
                }
            ],
        ];
    }
}
