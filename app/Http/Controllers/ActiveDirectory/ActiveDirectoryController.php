<?php

namespace App\Http\Controllers\ActiveDirectory;

use Adldap\AdldapInterface;
use App\Models\Security\User;
use App\Modules\Contractors\src\Request\EnableLDAPRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ActiveDirectoryController extends Controller
{

    /**
     * @var AdldapInterface
     */
    private $ldap;

    /**
     * Initialise common request params
     * @param AdldapInterface $ldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        parent::__construct();
        $this->ldap = $ldap;
    }

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

    /**
     * Sync SIM users id in LDAP database
     *
     * @return JsonResponse
     */
    public function sync()
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                if ( isset( $user->document ) ) {
                    $sim = DB::connection('mysql_sim')->table('idrdgov_simgeneral.persona')
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

    /**
     * @param EnableLDAPRequest $request
     * @return mixed
     */
    public function enableLDAPUser(EnableLDAPRequest $request)
    {
        try {
            $user = $this->ldap->search()->findByOrFail('samaccountname', toLower($request->get('username')));
            $ou = $request->has('ou') && ( !is_null($request->get('ou')) || $request->get('ou') != '' )
                ? $request->get('ou')
                : 'OU=AREA DE SISTEMAS,OU=SUBDIRECCION ADMINISTRATIVA Y FINANCIERA';
            // Additional OU
            $ou .= ",OU=ORGANIZACION IDRD,DC=adidrd,DC=local";
            // Get a new account control object for the user.
            $ac = $user->getUserAccountControlObject();
            // Mark the account as enabled (512 normal account).
            $ac->accountIsNormal();
            // Set the account control on the user and save it.
            $user->setUserAccountControl(512);
            // Set expiration date adding 2 days
            $user->setAccountExpiry(
                Carbon::parse(
                    $request->get('expiration_date')
                )->endOfDay()->timestamp
            );
            // Save the user.
            $user->save();
            // Move user to new group
            $user->move($ou);
            return $this->success_message('Se ha activado el usuario satisfactoriamente.');
        } catch (\Exception $exception) {
            return $this->error_response($exception->getMessage());
        }
    }
}
