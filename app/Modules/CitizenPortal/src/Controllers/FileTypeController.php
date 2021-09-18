<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\FileType;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Request\FileTypeRequest;
use App\Modules\CitizenPortal\src\Resources\FileTypeResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FileTypeController extends Controller
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
                    'model'     => FileType::class
                ],
                ['model' => CitizenSchedule::class, 'actions' => 'status'],
                ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                [
                    'actions'   => ['status'],
                    'model'     => File::class
                ],
                [
                    'actions'   => ['view_or_manage'],
                    'model'     => Profile::class
                ],
                [
                    'actions'   => ['status'],
                    'model'     => Profile::class
                ],
            ], true, true)
        )
            ->only('index');
        $this->middleware(Roles::actions(FileType::class, 'view_or_manage'))
            ->only('index');
        $this->middleware(Roles::actions(FileType::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(FileType::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(FileType::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(FileType::query(), (new FileType)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('name', 'like', "%$this->query%");
            })
            ->orderBy((new FileType)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            FileTypeResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => FileTypeResource::headers()
            ]
        );
    }

    /**
     * @param FileTypeRequest $request
     * @return JsonResponse
     */
    public function store(FileTypeRequest $request)
    {
        $model = new FileType();
        $model->fill($request->validated());
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param FileTypeRequest $request
     * @param FileType $type
     * @return JsonResponse
     */
    public function update(FileTypeRequest $request, FileType $type)
    {
        $type->fill($request->validated());
        $type->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param FileType $type
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(FileType $type)
    {
        $type->delete();
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
