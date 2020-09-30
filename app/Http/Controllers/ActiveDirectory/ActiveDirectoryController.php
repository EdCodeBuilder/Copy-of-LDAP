<?php

namespace App\Http\Controllers\ActiveDirectory;

use App\Models\Security\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ActiveDirectoryController extends Controller
{
    /**
     * Sync single user or all users from LDAP to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request)
    {
        try {
            ini_set('memory_limit', -1);

            $params = $request->has('username')
                ? ['--no-interaction', '--filter' => "(samaccountname={$request->get('username')})"]
                : ['--no-interaction'];

            Artisan::call('adldap:import', $params);
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_OK,
                Response::HTTP_OK,
                Artisan::output()
            );
        } catch ( \Exception $exception ) {
            return $this->error_response(
                __('validation.handler.ldap_fail'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function sync()
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                if ( isset( $user->document ) ) {
                    $sim = DB::connection('mysql_ldap')->table('idrdgov_simgeneral.persona')
                                                    ->where('Cedula', $user->document)
                                                    ->first();
                    if ( isset( $sim->Id_Persona ) ) {
                        $user->sim_id = $sim->Id_Persona;
                        $user->save();
                    }
                }
            }
        });

        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_OK
        );
    }
}
