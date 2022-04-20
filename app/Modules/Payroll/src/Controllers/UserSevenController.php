<?php


namespace App\Modules\Payroll\src\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;  
use App\Modules\Payroll\src\Resources\UserSevenResource;
use App\Modules\Payroll\src\Models\UserSeven;
use App\Modules\Payroll\src\Models\Person;
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
    public function getUserSevenList(Request $request)
    {
        //$data = [];
        $data = UserSeven::query()
                ->whereIn('TER_CODI', $request->listDocuments)
                ->orderBy('TER_NOCO')
                ->paginate(10000);
        return  $this->success_response(
            UserSevenResource::collection( $data )
        );
    }
    public function getPerson(Request $request)
    {
        //$data = [];
        $data = Person::where('Cedula', $request->identification)->first();
        return  response()->json($data);
    }
    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function consultUserSevenList(Request $request)
    {
        try {
            $http = new Client();
            //crear registro DNS publico(registro A) 
            //$response = $http->post("http://kubernet.gov.co/api/payroll/getUserSevenList", [
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
            $certificate->supervisor_name = $request->get('supervisorName');
            $certificate->supervisor_identification = $request->get('supervisorIdentification');
            $certificate->supervisor_profession = $request->get('supervisorProfession');
            $certificate->observations = $request->get('observations');
            $certificate->funding_source = $request->get('fundingSource');
            $certificate->entry = $request->get('entry');
            $certificate->total_pay = $request->get('totalPay');
            $certificate->settlement_period = $request->get('settlementPeriod');
            $collections = collect($request->get('contractorsList'));
            $supervisorSupportList = collect($request->get('supervisorSupportList'));
            $writer = new CertificateComplianceExportTemplate($certificate, $collections, $supervisorSupportList );
            
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
