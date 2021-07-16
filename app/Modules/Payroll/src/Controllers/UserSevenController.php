<?php


namespace App\Modules\Payroll\src\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;  
use App\Modules\Payroll\src\Resources\UserSevenResource;
use App\Modules\Payroll\src\Models\UserSeven;
use App\Modules\Payroll\src\Models\CertificateCompliance;
use App\Modules\Payroll\src\Request\CertificateComplianceRequest;
use App\Modules\Payroll\src\Exports\CertificateComplianceExportTemplate;
use Tightenco\Collect\Support\Collection;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Exception;
use GuzzleHttp\Client;


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
    public function prueba(Request $request)
    {
        $collections = collect($request->contractorsList);
        return  $this->success_message(
            count($collections)
        );
    }
    public function getUserSevenList(Request $request)
    {
        //$data = [];
        /* $data = UserSeven::query()
                ->when(true, function ($query) use ($request) {
                  return $query->whereIn('TER_CODI', $request->listDocuments);
                })
                ->orderBy('TER_NOCO')
                ->paginate(10000); */
        $data = UserSeven::query()
                ->whereIn('TER_CODI', $request->listDocuments)
                ->orderBy('TER_NOCO')
                ->paginate(10000);
        return  $this->success_response(
            UserSevenResource::collection( $data )
        );
    }
    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function consultUserSevenList(Request $request)
    {
        try {
            $http = new Client();
            $response = $http->post("http://66.70.171.168/api/payroll/getUserSevenList", [
                'json' => [
                    'listDocuments' => $request->get('listDocuments'),
                ],
                'headers' => [
                    'Accept'    => 'application/json',
                    'Content-type' => 'application/json'
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return  response()->json($data);
            /* if ( isset( $data['data'] ) && count($data['data']) > 0 ) {
                return $this->error_response($data);
            } */
            //return $this->createWarehouseCert($certification);
        } catch (Exception $exception) {
            return $this->error_response(
                'No podemos realizar la consulta en este momento, por favor intente más tarde.',
                422,
                $exception->getMessage()
            );
        }
    }
    /**
     * @param CertificateComplianceRequest $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function excelCertificateCompliance(CertificateComplianceRequest $request)
    {
        try {
            $certificate = new CertificateCompliance;
            $certificate->supervisor = $request->get('supervisorName');
            $certificate->component = $request->get('supervisorComponent');
            $certificate->funding_source = $request->get('fundingSource');
            $certificate->entry = $request->get('entry');
            $certificate->total_pay = $request->get('totalPay');
            $certificate->settlement_period = $request->get('settlementPeriod');
            
            //dd($certificate);
            
            /* 'component',
            'funding_source',
            'entry',
            'total_pay',
            'settlement_period', */

            /* $contractor = Contractor::where('document', $request->get('document'))->first();
            $http = new Client();
            $response = $http->post("http://66.70.171.168/api/contractors-portal/oracle-excel", [
                'json' => [
                    'document' => $request->get('document'),
                ],
                'headers' => [
                    'Accept'    => 'application/json',
                    'Content-type' => 'application/json'
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $collections = isset($data['data']) ? collect($data['data']) : collect([]);
            $writer = new WareHouseExportTemplate($contractor, $collections); */
            //$collections = isset($request->get('contractorsList')) ? collect($request->get('contractorsList')) : collect([]);
            $collections = collect($request->get('contractorsList'));
            //dd($certificate->supervisor);
            //dd($request->get('contractorsList'));
            
            $writer = new CertificateComplianceExportTemplate($certificate, $collections );
            
            $response = response()->streamDownload(function() use ($writer) {
                $writer->create()->save('php://output');
            });
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="CERTIFICADO_CUMPLIMIENTO_COLECTIVO.xlsx"');
            return $response->send();

        } catch (Exception $exception) {
            return $this->error_response(
                'No podemos realizar la consulta en este momento, por favor intente más tarde.',
                422,
                $exception->getMessage()
            );
        }
    }

}
