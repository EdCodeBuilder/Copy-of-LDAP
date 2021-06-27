<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Request\StoreCompanyRequest;
use App\Modules\Passport\src\Resources\CompanyResource;
use App\Modules\Passport\src\Resources\EpsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            CompanyResource::collection(
                $this->setQuery( Company::query(), 'id' )
                    ->when($this->query, function ($query) {
                        return $query->where('company', 'like', "%$this->query%");
                    })
                    ->paginate($this->per_page)
            ),
            Response::HTTP_OK,
            [
                'headers'   => CompanyResource::headers()
            ]
        );
    }

    /**
     * @param StoreCompanyRequest $request
     * @return JsonResponse
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = new Company();
        $company->fill( $request->validated() );
        $company->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param StoreCompanyRequest $request
     * @param Company $company
     * @return JsonResponse
     */
    public function update(StoreCompanyRequest $request, Company $company)
    {
        $company->fill( $request->validated() );
        $company->save();
        return $this->success_message(__('validation.handler.success'));
    }

    /**
     * @param Company $company
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
