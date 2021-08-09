<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
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
            'name'      =>  'required|string|min:3|max:45',
            'comment'   =>  'required|string|min:3|max:2500',
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
            'name' =>  __('passport.validations.name'),
            'comment' =>  __('passport.validations.comment'),
        ];
    }
}
