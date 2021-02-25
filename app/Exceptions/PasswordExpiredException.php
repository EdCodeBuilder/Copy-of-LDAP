<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class PasswordExpiredException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        Log::debug('Password Expired or Account Inactive');
    }
}
