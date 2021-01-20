<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Rupi;
use App\Modules\Parks\src\Models\Story;
use App\Modules\Parks\src\Request\RupiRequest;
use App\Modules\Parks\src\Request\StoryRequest;
use App\Modules\Parks\src\Resources\RupiResource;
use App\Modules\Parks\src\Resources\StoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
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
        $data = Park::with('story')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            return $this->success_response(
                StoryResource::collection( $data->story )
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
    public function store($park, StoryRequest $request): JsonResponse
    {
        $data = Park::with('story')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();

        if ($data) {
            $data->story()->create([
               'Subtitulo'   => $request->get('title'),
               'Parrafo'   => $request->get('text'),
            ]);
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoryRequest $request
     * @param $park
     * @param Story $story
     * @return JsonResponse
     */
    public function update(StoryRequest $request, $park, Story $story): JsonResponse
    {
        $story->fill([
            'Subtitulo'   => $request->get('title'),
            'Parrafo'   => $request->get('text'),
        ]);
        $story->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $park
     * @param Rupi $story
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Story $story): JsonResponse
    {
        $story->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
