<?php

namespace App\Http\Controllers\ActiveDirectory;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class ActiveDirectoryController extends Controller
{
    /**
     * Sync single user or all users from LDAP to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            ini_set('memory_limit', -1);

            $params = $request->has('username')
                ? ['--no-interaction', '--filter' => "(samaccountname={$request->get('username')})"]
                : ['--no-interaction'];

            Artisan::call('adldap:import', $params);
            return $this->success_message(
                Artisan::output(),
                Response::HTTP_OK
            );
        } catch ( \Exception $exception ) {
            return $this->error_response(
                __('validation.handler.ldap_fail'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
