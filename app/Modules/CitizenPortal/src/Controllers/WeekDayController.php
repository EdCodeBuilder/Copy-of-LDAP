<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Day;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Request\ActivityRequest;
use App\Modules\CitizenPortal\src\Request\WeekDayRequest;
use App\Modules\CitizenPortal\src\Resources\DayResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WeekDayController extends Controller
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
                    'model'     => Day::class
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
        $this->middleware(Roles::actions(Day::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Day::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Day::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(Day::query(), (new Day)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('name_weekdays_schedule', 'like', "%$this->query%");
            })
            ->orderBy((new Day)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            DayResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => DayResource::headers()
            ]
        );
    }

    /**
     * @param WeekDayRequest $request
     * @return JsonResponse
     */
    public function store(WeekDayRequest $request)
    {
        $model = new Day();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param WeekDayRequest $request
     * @param Day $day
     * @return JsonResponse
     */
    public function update(WeekDayRequest $request, Day $day)
    {
        $attrs = $day->fillModel($request->validated());
        $day->fill($attrs);
        $day->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Day $day
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Day $day)
    {
        $day->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
