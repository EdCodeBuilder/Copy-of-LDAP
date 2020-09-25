<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CsrfCookieController extends Controller
{
    /**
     * Get XSRF-TOKEN in a cookie
     *
     * @return Response
     */
    public function show()
    {
        return response()->noContent();
    }
}
