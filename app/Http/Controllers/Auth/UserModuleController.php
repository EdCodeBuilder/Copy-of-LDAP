<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Auth\ModuleResource;
use App\Models\Security\ActivityAccess;
use App\Models\Security\IncompatibleAccess;
use App\Models\Security\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserModuleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if ( auth()->user()->sim_id ) {
            $data = Module::active()->whereIn('id', function ($query) {
                $query->select('Id_Modulo')->whereIn('Id_Actividad', function ($q) {
                    $q->select('Id_Actividad')->where('Id_Persona', auth()->user()->sim_id)->from('idrdgov_simgeneral.actividad_acceso');
                })->from('idrdgov_simgeneral.actividades');
            })->with([
                'incompatible_access.permission'    => function ($q) {
                    return $q->where('Id_Persona', auth()->user()->sim_id);
                }
            ])->paginate( $this->per_page );
            return $this->success_response(ModuleResource::collection($data));
        }
        return $this->success_message([]);
    }
}
