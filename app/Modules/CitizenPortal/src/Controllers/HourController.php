<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Request\HourRequest;
use App\Modules\CitizenPortal\src\Resources\HourResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class HourController extends Controller
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
                    'model'     => Hour::class
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
        $this->middleware(Roles::actions(Hour::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Hour::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Hour::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(Hour::query(), (new Hour)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('name_daily_schedule', 'like', "%$this->query%");
            })
            ->orderBy((new Hour)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            HourResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => HourResource::headers()
            ]
        );
    }

    /**
     * @param HourRequest $request
     * @return JsonResponse
     */
    public function store(HourRequest $request)
    {
        $model = new Hour();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param HourRequest $request
     * @param Hour $hour
     * @return JsonResponse
     */
    public function update(HourRequest $request, Hour $hour)
    {
        $attrs = $hour->fillModel($request->validated());
        $hour->fill($attrs);
        $hour->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Hour $hour
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Hour $hour)
    {
        $hour->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
