<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Rupi;
use App\Modules\Parks\src\Request\RupiRequest;
use App\Modules\Parks\src\Resources\RupiResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RupiController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $park
     * @return JsonResponse
     */
    public function index($park): JsonResponse
    {
        $data = Park::with('rupis')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            return $this->success_response(
                RupiResource::collection( $data->rupis )
            );
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $park
     * @param RupiRequest $request
     * @return JsonResponse
     */
    public function store($park, RupiRequest $request): JsonResponse
    {
        $data = Park::with('rupis')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            $data->rupis()->create([
               'Rupi'   => $request->get('name')
            ]);
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RupiRequest $request
     * @param $park
     * @param Rupi $rupi
     * @return JsonResponse
     */
    public function update(RupiRequest $request, $park, Rupi $rupi): JsonResponse
    {
        $rupi->fill([
            'Rupi'  => $request->get('name')
        ]);
        $rupi->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $park
     * @param Rupi $rupi
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Rupi $rupi): JsonResponse
    {
        $rupi->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
