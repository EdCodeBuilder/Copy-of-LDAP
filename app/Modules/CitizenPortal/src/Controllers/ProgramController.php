<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Request\ProgramRequest;
use App\Modules\CitizenPortal\src\Resources\ProgramResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProgramController extends Controller
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
                    'model'     => Program::class
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
        $this->middleware(Roles::actions(Program::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(Program::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Program::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(Program::query(), 'id')
            ->when(isset($this->query), function ($query) {
                return $query->where('program_name', 'like', "%$this->query%");
            })
            ->orderBy((new Program)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ProgramResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => ProgramResource::headers()
            ]
        );
    }

    /**
     * @param ProgramRequest $request
     * @return JsonResponse
     */
    public function store(ProgramRequest $request)
    {
        $model = new Program();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param ProgramRequest $request
     * @param Program $program
     * @return JsonResponse
     */
    public function update(ProgramRequest $request, Program $program)
    {
        $attrs = $program->fillModel($request->validated());
        $program->fill($attrs);
        $program->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Program $program
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Program $program)
    {
        $program->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
