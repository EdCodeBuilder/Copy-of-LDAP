<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Request\StoreEpsRequest;
use App\Modules\Passport\src\Resources\EpsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EpsController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            EpsResource::collection(
                $this->setQuery(Eps::query(), 'i_pk_id')
                    ->when(isset($this->query), function ($query) {
                        return $query->where('vc_nombre', 'like', "%{$this->query}%");
                    })
                    ->get()
            )
        );
    }

    /**
     * @return JsonResponse
     */
    public function table()
    {
        return $this->success_response(
            EpsResource::collection(
                $this->setQuery(Eps::query(), 'i_pk_id')
                    ->when(isset($this->query), function ($query) {
                        return $query->where('vc_nombre', 'like', "%{$this->query}%");
                    })
                    ->paginate( $this->per_page )
            ),
            Response::HTTP_OK,
            [
                'headers'   => EpsResource::headers()
            ]
        );
    }

    public function store(StoreEpsRequest $request)
    {
        $form = new Eps();
        $form->vc_nombre = $request->get('name');
        $form->i_estado = 1;
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    public function update(StoreEpsRequest $request, Eps $eps)
    {
        $eps->vc_nombre = $request->get('name');
        $eps->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    public function destroy(Eps $eps)
    {
        $eps->i_estado = 0;
        $eps->save();
        $eps->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
