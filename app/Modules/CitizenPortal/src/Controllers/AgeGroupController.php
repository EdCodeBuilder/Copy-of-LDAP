<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\AgeGroup;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Request\ActivityRequest;
use App\Modules\CitizenPortal\src\Request\AgeGroupRequest;
use App\Modules\CitizenPortal\src\Resources\ActivityResource;
use App\Modules\CitizenPortal\src\Resources\AgeGroupResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AgeGroupController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(
            Roles::canAny([
                [
                    'actions'   => 'view_or_manage',
                    'model'     => AgeGroup::class
                ],
                ['model' => CitizenSchedule::class, 'actions' => 'status'],
                ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                [
                    'actions'   => 'view_or_manage',
                    'model'     => Schedule::class
                ],
            ], true, true)
        )
            ->only('index');
        $this->middleware(Roles::actions(AgeGroup::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(AgeGroup::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(AgeGroup::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(AgeGroup::query(), (new AgeGroup)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('grupo_etario', 'like', "%$this->query%");
            })
            ->orderBy((new AgeGroup)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            AgeGroupResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => AgeGroupResource::headers()
            ]
        );
    }

    /**
     * @param AgeGroupRequest $request
     * @return JsonResponse
     */
    public function store(AgeGroupRequest $request)
    {
        $model = new AgeGroup();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param AgeGroupRequest $request
     * @param AgeGroup $group
     * @return JsonResponse
     */
    public function update(AgeGroupRequest $request, AgeGroup $group)
    {
        $attrs = $group->fillModel($request->validated());
        $group->fill($attrs);
        $group->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param AgeGroup $group
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(AgeGroup $group)
    {
        $group->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
