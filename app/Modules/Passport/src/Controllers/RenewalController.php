<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Exports\Excel as ExcelRaw;
use App\Modules\Passport\src\Exports\RenewalExport;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Models\Renew;
use App\Modules\Passport\src\Models\RenewalView;
use App\Modules\Passport\src\Request\ExcelRequest;
use App\Modules\Passport\src\Request\StoreCompanyRequest;
use App\Modules\Passport\src\Resources\CompanyResource;
use App\Modules\Passport\src\Resources\EpsResource;
use App\Modules\Passport\src\Resources\RenewalResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;

class RenewalController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    /**
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->success_response(
            RenewalResource::collection(
                RenewalView::with('user')
                    ->when(isset($this->query), function ($query) {
                        return $query->where(function ($q) {
                            return $q->where('passport_id', 'like', "%{$this->query}%")
                                ->orWhere('user_cade_name', 'like', "%{$this->query}%")
                                ->orWhere('user_cade_document', 'like', "%{$this->query}%")
                                ->orWhere('denounce', 'like', "%{$this->query}%")
                                ->orWhere('supercade', 'like', "%{$this->query}%");
                        });
                    })
                    ->when($this->column && $this->order, function ($query) {
                        return $query->orderBy($this->column, $this->order);
                    })
                    ->paginate($this->per_page)
            ),
            Response::HTTP_OK,
            [
                'headers'   => RenewalResource::headers(true)
            ]
        );
    }

    /**
     * @param ExcelRequest $request
     * @return JsonResponse
     */
    public function excel(ExcelRequest $request)
    {
        $file = ExcelRaw::raw(new RenewalExport($request), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('passport.validations.renewals')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
        );
        return $this->success_message($response);
    }

    /**
     * @param Renew $renewal
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Renew $renewal)
    {
        $renewal->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
