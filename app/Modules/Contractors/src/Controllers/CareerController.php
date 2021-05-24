<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Contractors\src\Models\AcademicLevel;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractType;
use App\Modules\Contractors\src\Models\FileType;
use App\Modules\Contractors\src\Resources\AcademicLevelResource;
use App\Modules\Contractors\src\Resources\CareerResource;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\ContractTypeResource;
use App\Modules\Contractors\src\Resources\FileTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
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
    public function levels()
    {
        return $this->success_response(
            AcademicLevelResource::collection(AcademicLevel::all())
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(AcademicLevel $level)
    {
        $careers = $level->careers()
                ->when($this->query != null, function ($query) {
                    return $query->where('name', 'like', "%{$this->query}%");
                })->get();
        return $this->success_response(
            CareerResource::collection($careers)
        );
    }
}
