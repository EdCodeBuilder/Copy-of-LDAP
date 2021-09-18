<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePermissionRequest;
use App\Http\Requests\Auth\UpdatePermissionRequest;
use App\Http\Resources\Auth\AbilityResource;
use App\Models\Security\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Ability;
use Tightenco\Collect\Support\Collection;

class PermissionController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('role:superadmin')
            ->only('index', 'models', 'store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $abilities = Ability::query()
            ->where('name', 'like', '%citizen-portal')
            ->whereNull('entity_id')
            ->get();
        return $this->success_response(
            AbilityResource::collection( $abilities )
        );
    }

    /**
     * @return JsonResponse
     */
    public function models()
    {
        return $this->success_message(
            $this->getModels(app_path('/Modules/CitizenPortal/src/Models'))
        );
    }

    /**
     * @param $path
     * @return \Illuminate\Support\Collection|Collection
     */
    public function getModels($path) {
        $models = collect(File::allFiles($path))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('%s%s',
                    'App\Modules\CitizenPortal\src\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                return [
                    'id'    => $class,
                    'name'  => __("citizen.classes.{$class}")
                ];
            })
            ->filter(function ($item) {
                return $item['name'] != '';
            });

        return $models->merge([ ['id' => User::class, 'name' => 'Usuarios'], ['id' => Audit::class, 'name' => 'Auditoria'], ])->values();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request)
    {
        $name = str_ends_with($request->get('name'), 'citizen-portal')
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-citizen-portal");

        BouncerFacade::ability()->createForModel($request->get('entity_type'), [
            'name'  => $name,
            'title' =>  $request->get('title')
        ]);
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @param Ability $permission
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Ability $permission)
    {
        $name = str_ends_with($request->get('name'), 'citizen-portal')
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-citizen-portal");

        $permission->forceFill([
            'name'  => $name,
            'title' =>  $request->get('title'),
            'entity_type' => $request->get('entity_type'),
        ]);
        $permission->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ability $permission
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Ability $permission)
    {
        $permission->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
