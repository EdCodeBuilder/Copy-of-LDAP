<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(...Roles::all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>  'required|string|min:3|max:191',
            'description'   =>  'required|string|min:3|max:2500',
            'btn_text'      =>  'required|string|min:3|max:25',
            'flex'           =>  'required|numeric|between:6,12',
            'lottie'        =>  [
                Rule::requiredIf(function () {
                    return ! $this->hasFile('src');
                }),
            ],
            'src'           =>  [
                Rule::requiredIf(function () {
                    return ! $this->has('lottie');
                }),
                'nullable',
                'image',
            ],
            'to'    => [
                Rule::requiredIf(function () {
                    return ! $this->has('href');
                }),
            ],
            'href'    => [
                Rule::requiredIf(function () {
                    return ! $this->has('to');
                }),
                'nullable',
                'url',
            ],
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
            'title'         =>  __('passport.validations.title'),
            'description'   =>  __('passport.validations.description'),
            'btn_text'      =>  __('passport.validations.btn_text'),
            'flex'           =>  __('passport.validations.flex'),
            'lottie'        =>  __('passport.validations.lottie'),
            'src'           =>  __('passport.validations.src'),
            'to'            =>  __('passport.validations.to'),
            'href'          =>  __('passport.validations.href'),
        ];
    }
}
