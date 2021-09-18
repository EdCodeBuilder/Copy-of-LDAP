<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Request\ActivityRequest;
use App\Modules\CitizenPortal\src\Resources\ActivityResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ActivityController extends Controller
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
                    'model'     => Activity::class
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
        $this->middleware(Roles::actions(Activity::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Activity::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Activity::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(Activity::query(), (new Activity)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('activity_name', 'like', "%$this->query%");
            })
            ->orderBy((new Activity)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ActivityResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => ActivityResource::headers()
            ]
        );
    }

    /**
     * @param ActivityRequest $request
     * @return JsonResponse
     */
    public function store(ActivityRequest $request)
    {
        $model = new Activity();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param ActivityRequest $request
     * @param Activity $activity
     * @return JsonResponse
     */
    public function update(ActivityRequest $request, Activity $activity)
    {
        $attrs = $activity->fillModel($request->validated());
        $activity->fill($attrs);
        $activity->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Activity $activity
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
