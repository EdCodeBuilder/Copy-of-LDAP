<?php


namespace App\Modules\Payroll\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Payroll\src\Models\UserSeven;
use App\Modules\Payroll\src\Resources\UserSevenResource;
use Illuminate\Http\JsonResponse;

class UserSevenController extends Controller
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
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            UserSevenResource::collection(UserSeven::all())
        );
        // return $this->success_response(
        //     ContractTypeResource::collection(ContractType::all())
        // );
    }
    public function getUserSevenList(Request $request)
    {
        $data = UserSeven::all();
        return response()->json([
            'prueba' => $data
        ], 200);
        
        // $data = UserSeven::query()
        //         ->when($request->has('document'), function ($query) use ($request) {
        //           return $query->where('TER_NOCO', $request->get('document'));
        //         })->paginate($this->per_page);
        // return  $this->success_response(
        //     UserSevenResource::collection( $data )
        // );
    }
}
