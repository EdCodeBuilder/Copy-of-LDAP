<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\SuperCade;
use App\Modules\Passport\src\Request\StoreSuperCadeRequest;
use App\Modules\Passport\src\Resources\SuperCadeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SuperCadeController extends Controller
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
            SuperCadeResource::collection(
                $this->setQuery(SuperCade::query(), 'i_pk_id')
                    ->when(isset($this->query), function ($query) {
                        return $query->where('vc_nombre', 'like', "%{$this->query}%");
                    })
                    ->active()
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
            SuperCadeResource::collection(
                $this->setQuery(SuperCade::query(), 'i_pk_id')
                    ->when(isset($this->query), function ($query) {
                        return $query->where('vc_nombre', 'like', "%{$this->query}%");
                    })
                    ->paginate( $this->per_page )
            ),
            Response::HTTP_OK,
            [
                'headers'   => SuperCadeResource::headers()
            ]
        );
    }

    public function store(StoreSuperCadeRequest $request)
    {
        $form = new SuperCade();
        $form->vc_nombre = $request->get('name');
        $form->i_estado = 1;
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    public function update(StoreSuperCadeRequest $request, SuperCade $cade)
    {
        $cade->vc_nombre = $request->get('name');
        $cade->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    public function status(SuperCade $cade)
    {
        $cade->i_estado = !$cade->i_estado;
        $cade->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    public function destroy(SuperCade $cade)
    {
        $cade->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
