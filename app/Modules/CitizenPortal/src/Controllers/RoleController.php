<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRoleRequest;
use App\Http\Requests\Auth\UpdateRoleRequest;
use App\Http\Resources\Auth\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;

class RoleController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('role:superadmin')
            ->only('index', 'store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $roles = Role::query()->where('name', 'like', '%citizen-portal%')->get();
        return $this->success_response(
            RoleResource::collection( $roles )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $name = substr( toLower($request->get('name')), 0, 15 ) === "citizen-portal-"
            ? toLower($request->get('name'))
            : toLower("citizen-portal-{$request->get('name')}");
        BouncerFacade::role()->firstOrCreate([
            'name'  =>  $name,
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
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $name = substr( toLower($request->get('name')), 0, 15 ) === "citizen-portal-"
            ? toLower($request->get('name'))
            : toLower("citizen-portal-{$request->get('name')}");
        $role->fill([
            'name'  =>  $name,
            'title' =>  $request->get('title')
        ]);
        $role->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
