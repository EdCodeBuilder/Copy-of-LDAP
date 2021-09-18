<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Origin;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Request\EnclosureRequest;
use App\Modules\Parks\src\Request\OriginRequest;
use App\Modules\Parks\src\Resources\EnclosureResource;
use App\Modules\Parks\src\Resources\OriginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class OriginController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Origin::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Origin::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Origin::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index($park)
    {
        try {
            $data = Park::with('history')
                ->where('Id_IDRD', $park)
                ->orWhere('Id', $park)
                ->firstOrFail();
            return isset($data->history)
                    ? $this->success_response(
                            new OriginResource($data->history)
                        )
                    : $this->success_message(null);
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OriginRequest $request
     * @return JsonResponse
     */
    public function store(OriginRequest $request, $park)
    {
        try {
            $data = Park::with('history')
                ->where('Id_IDRD', $park)
                ->orWhere('Id', $park)
                ->firstOrFail();

            $data->history()->create([
                'IdParque' => $request->get('park_id'),
                'Parrafo1' => $request->get('paragraph_1'),
                'Parrafo2' => $request->get('paragraph_2'),
                'imagen1' => $this->processFile($request, 'image_1'),
                'imagen2' => $this->processFile($request, 'image_2'),
                'imagen3' => $this->processFile($request, 'image_3'),
                'imagen4' => $this->processFile($request, 'image_4'),
                'imagen5' => $this->processFile($request, 'image_5'),
                'imagen6' => $this->processFile($request, 'image_6'),
            ]);
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_CREATED
            );
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OriginRequest $request
     * @param $park
     * @param Origin $origin
     * @return JsonResponse
     */
    public function update(OriginRequest $request, $park, Origin $origin)
    {
        $origin->Parrafo1 = $request->get('paragraph_1');
        $origin->Parrafo2 = $request->get('paragraph_2');
        $origin->imagen1 = $this->updateFile($origin, 'imagen1', $request, 'image_1');
        $origin->imagen2 = $this->updateFile($origin, 'imagen2', $request, 'image_2');
        $origin->imagen3 = $this->updateFile($origin, 'imagen3', $request, 'image_3');
        $origin->imagen4 = $this->updateFile($origin, 'imagen4', $request, 'image_4');
        $origin->imagen5 = $this->updateFile($origin, 'imagen5', $request, 'image_5');
        $origin->imagen6 = $this->updateFile($origin, 'imagen6', $request, 'image_6');
        $origin->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param $park
     * @param Origin $origin
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Origin $origin)
    {
        foreach (range(1, 6) as $value) {
            if (
                isset($origin->{"imagen$value"}) &&
                !is_null($origin->{"imagen$value"}) &&
                $origin->{"imagen$value"} != '' &&
                Storage::disk('public')->exists(Origin::IMAGES_PATH.'/'.$origin->{"imagen$value"})
            ) {
                Storage::disk('public')->delete(Origin::IMAGES_PATH.'/'.$origin->{"imagen$value"});
            }
        }
        $origin->forceDelete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param Request $request
     * @param $key
     * @return string|null
     */
    private function processFile(Request $request, $key) {
        if ($request->hasFile($key)) {
            $ext = $request->file($key)->getClientOriginalExtension();
            $name = random_img_name().".$ext";
            $request->file($key)->storeAs(Origin::IMAGES_PATH, $name, ['disk' => 'public']);
            return $name;
        }
        return null;
    }

    /**
     * @param Origin $origin
     * @param $key
     * @param Request $request
     * @param $requestKey
     * @return mixed|string|null
     */
    private function updateFile(Origin $origin, $key, Request $request, $requestKey) {
        if ($request->hasFile($requestKey) && isset($origin->{$key}) && ! is_null($origin->{$key}) && $origin->{$key} != '') {
            if (Storage::disk('public')->exists(Origin::IMAGES_PATH.'/'.$origin->{$key})) {
                Storage::disk('public')->delete(Origin::IMAGES_PATH.'/'.$origin->{$key});
            }
            return $this->processFile($request, $requestKey);
        }
        return isset($origin->{$key}) ? $origin->{$key} : null;
    }
}
