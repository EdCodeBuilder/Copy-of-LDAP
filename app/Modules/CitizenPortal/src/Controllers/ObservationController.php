<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Resources\ObservationResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ObservationController extends Controller
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
    public function index(Profile $profile)
    {
        return $this->success_response(
            ObservationResource::collection($profile->observations()->latest()->paginate($this->per_page))
        );
    }

    /**
     * @param $profile
     * @param Observation $observation
     * @return JsonResponse
     */
    public function show($profile, Observation $observation)
    {
        $observation->read_at = now();
        $observation->save();
        return $this->success_message(
            __('validation.handler.success')
        );
    }
}
