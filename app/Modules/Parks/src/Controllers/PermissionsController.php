<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePermissionRequest;
use App\Http\Requests\Auth\UpdatePermissionRequest;
use App\Http\Resources\Auth\AbilityResource;
use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Ability;

class PermissionsController extends Controller
{
    /**
     * Initialise common request params
     */
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
        $abilities = Ability::query()
            ->where('name', 'like', '%-'.Roles::IDENTIFIER)
            ->whereNull('entity_id')
            ->get();
        return $this->success_response(
            AbilityResource::collection( $abilities )
        );
    }

    public function models()
    {
        return $this->success_message(
            $this->getModels(app_path('/Modules/Parks/src/Models'))
        );
    }

    public function getModels($path) {
        $models = collect(File::allFiles($path))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('%s%s',
                    'App\Modules\Parks\src\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                return [
                    'id'    => $class,
                    'name'  => __("parks.classes.{$class}")
                ];
            })
            ->filter(function ($item) {
               return $item['name'] != '';
            });

        return $models->merge([ ['id' => User::class, 'name' => __("parks.classes.".User::class)], ['id' => Audit::class, 'name' => __("parks.classes.".Audit::class)], ])->values();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request)
    {
        $name = explode('-', toLower($request->get('name')));
        $name = end($name);
        $name = $name == Roles::IDENTIFIER
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-".Roles::IDENTIFIER);

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
        $name = explode('-', toLower($request->get('name')));
        $name = end($name);
        $name = $name == Roles::IDENTIFIER
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-".Roles::IDENTIFIER);

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
