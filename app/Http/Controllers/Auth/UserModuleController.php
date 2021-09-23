<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Auth\ModuleResource;
use App\Models\Security\ActivityAccess;
use App\Models\Security\IncompatibleAccess;
use App\Models\Security\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @group Módulos
 *
 * Api para la visualización de los módulos asociados al usuario.
 */
class UserModuleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Módulos
     *
     * Módulos del Usuario
     *
     * Api para la visualización de los módulos asociados al usuario.
     *
     * @authenticated
     * @responseFile responses/modules.get.json
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = Module::active()
            ->when(auth('api')->user()->sim_id, function ($query) {
                return $query->whereIn('Id_Modulo', function ($query) {
                    $query->select('Id_Modulo')->whereIn('Id_Actividad', function ($q) {
                        $q->select('Id_Actividad')
                            ->where('Id_Persona', auth()->user()->sim_id)
                            ->from('idrdgov_simgeneral.actividad_acceso');
                    })->from('idrdgov_simgeneral.actividades');
                })->with([
                    'incompatible_access.permission'    => function ($q) {
                        return $q->where('Id_Persona', auth()->user()->sim_id);
                    }
                ]);
            })
            ->when(!auth('api')->user()->sim_id, function ($query) {
                return $query->where('Id_Modulo', '>', 99999);
            })
            ->paginate( $this->per_page );
        return $this->success_response(ModuleResource::collection($data));
    }
}
