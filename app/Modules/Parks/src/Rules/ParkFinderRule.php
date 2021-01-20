<?php

namespace App\Modules\Parks\src\Rules;

use Illuminate\Contracts\Validation\Rule;

class ParkFinderRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return request()->has('locality_id') || request()->has('type_id') || request()->has('query');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.handler.invalid_request');
    }
}
