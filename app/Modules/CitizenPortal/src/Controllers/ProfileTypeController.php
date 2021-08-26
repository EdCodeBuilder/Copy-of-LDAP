<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\ProfileType;
use App\Modules\CitizenPortal\src\Request\ProfileTypeRequest;
use App\Modules\CitizenPortal\src\Resources\ProfileTypeResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProfileTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(Roles::actions(ProfileType::class, 'create'))
            ->only('store');
        $this->middleware(Roles::actions(ProfileType::class, 'update'))
            ->only('update');
        $this->middleware(Roles::actions(ProfileType::class, 'destroy'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->setQuery(ProfileType::query(), (new ProfileType)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('name', 'like', "%$this->query%");
            })
            ->orderBy((new ProfileType)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ProfileTypeResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => ProfileTypeResource::headers()
            ]
        );
    }

    /**
     * @param ProfileTypeRequest $request
     * @return JsonResponse
     */
    public function store(ProfileTypeRequest $request)
    {
        $model = new ProfileType();
        $model->fill($request->validated());
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param ProfileTypeRequest $request
     * @param ProfileType $type
     * @return JsonResponse
     */
    public function update(ProfileTypeRequest $request, ProfileType $type)
    {
        $type->fill($request->validated());
        $type->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param ProfileType $type
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(ProfileType $type)
    {
        $type->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
