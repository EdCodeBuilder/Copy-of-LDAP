<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Request\StatusRequest;
use App\Modules\CitizenPortal\src\Resources\StatusResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatusController extends Controller
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
                    'model'     => Status::class
                ],
                ['model' => CitizenSchedule::class, 'actions' => 'status'],
                ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                [
                    'actions'   => ['status'],
                    'model'     => File::class
                ],
                [
                    'actions'   => ['status', 'view_or_manage', 'validator'],
                    'model'     => Profile::class
                ],
            ], true, true)
        )
            ->only('index');
        $this->middleware(Roles::actions(Status::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Status::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Status::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->setQuery(Status::query(), (new Status)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('name', 'like', "%$this->query%");
            })
            ->when($request->has('for_profile'), function ($query) {
                return $query->profile();
            })
            ->when($request->has('for_subscription'), function ($query) {
                return $query->subscription();
            })
            ->when($request->has('for_files'), function ($query) {
                return $query->files();
            })
            ->orderBy((new Status)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            StatusResource::collection(
                (int) $this->per_page > 0
                ? $query->paginate( $this->per_page )
                : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => StatusResource::headers()
            ]
        );
    }

    /**
     * @param StatusRequest $request
     * @return JsonResponse
     */
    public function store(StatusRequest $request)
    {
        $model = new Status();
        $model->fill($request->validated());
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param StatusRequest $request
     * @param Status $status
     * @return JsonResponse
     */
    public function update(StatusRequest $request, Status $status)
    {
        $status->fill($request->validated());
        $status->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Status $status
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
