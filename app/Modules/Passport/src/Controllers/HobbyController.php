<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Hobby;
use App\Modules\Passport\src\Request\StoreHobbyRequest;
use App\Modules\Passport\src\Resources\HobbyResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class HobbyController extends Controller
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
            HobbyResource::collection(
                $this->setQuery(Hobby::query(), 'i_pk_id')
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
            HobbyResource::collection(
                $this->setQuery(Hobby::query(), 'i_pk_id')
                    ->when(isset($this->query), function ($query) {
                        return $query->where('vc_nombre', 'like', "%{$this->query}%");
                    })
                    ->paginate($this->per_page)
            ),
            Response::HTTP_OK,
            [
                'headers'   => HobbyResource::headers()
            ]
        );
    }

    /**
     * @param StoreHobbyRequest $request
     * @return JsonResponse
     */
    public function store(StoreHobbyRequest $request)
    {
        $form = new Hobby();
        $form->vc_nombre = $request->get('name');
        $form->i_estado = 1;
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param StoreHobbyRequest $request
     * @param Hobby $hobby
     * @return JsonResponse
     */
    public function update(StoreHobbyRequest $request, Hobby $hobby)
    {
        $hobby->vc_nombre = $request->get('name');
        $hobby->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Hobby $hobby
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Hobby $hobby)
    {
        $hobby->i_estado = 0;
        $hobby->save();
        $hobby->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
