<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Stage;
use App\Modules\CitizenPortal\src\Request\StageRequest;
use App\Modules\CitizenPortal\src\Resources\StageResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StageController extends Controller
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
                    'model'     => Stage::class
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
        $this->middleware(Roles::actions(Stage::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Stage::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Stage::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(Stage::query(), (new Stage)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('scenario_name', 'like', "%$this->query%");
            })
            ->orderBy((new Stage)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            StageResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => StageResource::headers()
            ]
        );
    }

    /**
     * @param StageRequest $request
     * @return JsonResponse
     */
    public function store(StageRequest $request)
    {
        $model = new Stage();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param StageRequest $request
     * @param Stage $stage
     * @return JsonResponse
     */
    public function update(StageRequest $request, Stage $stage)
    {
        $attrs = $stage->fillModel($request->validated());
        $stage->fill($attrs);
        $stage->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Stage $stage
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Stage $stage)
    {
        $stage->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
