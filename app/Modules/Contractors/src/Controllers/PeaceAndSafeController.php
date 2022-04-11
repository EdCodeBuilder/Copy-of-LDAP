<?php


namespace App\Modules\Contractors\src\Controllers;


use Adldap\AdldapInterface;
use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Modules\Contractors\src\Exports\WareHouseExportTemplate;
use App\Modules\Contractors\src\Jobs\VerificationCode;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Request\ConsultPeaceAndSafeRequest;
use App\Modules\Contractors\src\Request\PeaceAndSafeRequest;
use App\Modules\Orfeo\src\Models\Informed;
use App\Modules\Orfeo\src\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelQRCode\Facades\QRCode;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class PeaceAndSafeController extends Controller
{
    /**
     * @var AdldapInterface
     */
    private $ldap;

    /**
     * @var null
     */
    private $user;

    /**
     * Initialise common request params
     * @param AdldapInterface $ldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        parent::__construct();
        $this->ldap = $ldap;
    }

    /**
     * @param Request $request
     * @param $type
     * @return Certification
     */
    public function saveInDatabase(Request $request, $type)
    {
        // try {
            $contract = format_contract($request->get('contract'), $request->get('year'));
            $contractor = Contractor::query()
                ->where('document', $request->get('document'))
                ->whereHas('contracts', function ($query) use ($contract) {
                    return $query->where('contract', $contract)->latest();
                })->with([
                    'contracts' => function($query) use ($contract) {
                        return $query->where('contract', $contract)->latest()->first();
                    }
                ])->firstOrFail();

            return Certification::firstOrCreate(
                [
                    'document'  =>  $contractor->document,
                    'contract'  =>  $contract,
                    'type'      =>  $type
                ],
                [
                    'expires_at'    => isset($contractor->contracts[0]->final_date) ? $contractor->contracts[0]->final_date : null,
                    'name'      =>  $contractor->full_name,
                    'virtual_file'      =>  toUpper($request->get('virtual_file')),
                    'contractor_id'      =>  $contractor->id,
                ]
            );
            /*
        } catch (Exception $exception) {
            return $this->error_response(
                'No se encuentra el usuario con los parámetros establecidos',
                422,
                $exception->getMessage()
            );
        }
            */


        /*
        $certification = Certification::where('document', $request->get('document'))
            ->where('contract', $contract)
            ->where('type', $type)
            ->first();
        if (isset($certification->id)) {
            return $certification;
        }
        $certification = new Certification;
        $certification->fill($request->validated());

        $name = toUpper($request->get('name'));
        $surname = toUpper($request->get('surname'));
        $contractor = Contractor::where('document', $request->get('document'))->first();
        if (isset($contractor->id)) {
            $name = $contractor->name;
            $surname = $contractor->surname;
        } else {
            $user = \App\Models\Security\User::where('document', $request->get('document'))->first();
            if (isset($user->id)) {
                $name = $user->name;
                $surname = $user->surname;
            }
        }
        $certification->name = "{$name} {$surname}";
        $certification->contract = $contract;
        $certification->type = $type;
        $certification->save();
        return $certification;
        */
    }

    /**
     * Display a listing of the resource.
     *
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|string
     */
    public function index(PeaceAndSafeRequest $request)
    {
        try {
            $certification = $this->saveInDatabase($request, 'SYS');
            if (isset($certification->token)) {
                return $this->generateCertificate($certification);
            }
            return $this->generateCertificate($certification);
        } catch (Exception $exception) {

            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    __('contractor.not_found'),
                    422
                );
            }
            return $this->error_response(
                __('contractor.error'),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ConsultPeaceAndSafeRequest $request
     * @return string
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     */
    public function validation(ConsultPeaceAndSafeRequest $request)
    {
        try {
            $contract_number = str_pad($request->get('contract'), 4, '0', STR_PAD_LEFT);
            $contract = "IDRD-CTO-{$contract_number}-{$request->get('year')}";
            $certification = Certification::query()->when(
                $request->has('token') &&
                ($request->get('token') != "" || !is_null($request->get('token')))
                && str_starts_with($request->get('token'), 'SYS'),
                function ($query) use ($request) {
                    return $query->where('token', $request->get('token'));
                },
                function ($query) use ($contract, $request) {
                    return $query->where('contract', 'like', "%{$contract}%")
                        ->where('document', $request->get('document'))
                        ->where('token', 'like', 'SYS-%');
                }
            )->firstOrFail();
            $virtual_file = $certification->virtual_file;
            $complete_text = $virtual_file
                ? ", número de contrato: <b>{$certification->contract}</b> y número de expediente: <b>{$virtual_file}</b>"
                : " y número de contrato: <b>{$certification->contract}</b>";
            $text = $this->createText(
                $certification->name,
                $certification->document,
                $complete_text,
                $certification->username,
                isset($certification->username)
            );
            return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output('I', 'PAZ_Y_SALVO.pdf');
        } catch (Exception $exception) {
            return $this->error_response(
                __('contractor.invalid'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                env('APP_DEBUG') == true ? $exception->getMessage() : null
            );
        }
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    public function show($token)
    {
        try {
            $certification = Certification::where('token', $token)->firstOrFail();

            $area = null;

            switch ($certification->type) {
                case 'ALM':
                    $area = 'Almacén';
                    break;
                case 'SYS':
                    $area = 'Sistemas';
                    break;
                case 'TRB':
                    $area = 'Contabilidad';
                    break;
            }

            $message = $certification->type == "TRB"
                ? __("contractor.valid_trib", [
                    'date'      => $certification->created_at->format('Y-m-d H:i:s'),
                    'name'      => $certification->name,
                    'document'  => $certification->document,
                    'contract'  => $certification->contract,
                    'area'      => $area
                ])
                : __('contractor.valid_token', [
                    'date'      => $certification->created_at->format('Y-m-d H:i:s'),
                    'name'      => $certification->name,
                    'document'  => $certification->document,
                    'contract'  => $certification->contract,
                    'area'      => $area
                ]);

            return $this->success_message([
                'id'        =>  $certification->id,
                'message' => $message
            ]);
        } catch (Exception $exception) {
            return $this->error_response(
                __('contractor.invalid_token', ['token' => $token]),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|string
     */
    public function wareHouse(PeaceAndSafeRequest $request)
    {
        try {
            $certification = $this->saveInDatabase($request, 'ALM');
            /*
            if (
                // isset($certification->token) &&
                isset($certification->expires_at) &&
                $certification->expires_at->subDays(5)->gte( now() )
            ) {
                return $this->createWarehouseCert($certification);
            }
            */
            $http = new Client();
            $response = $http->post("http://66.70.171.168/api/contractors-portal/oracle-count", [
                'json' => [
                    'document' => $request->get('document'),
                ],
                'headers' => [
                    'Accept'    => 'application/json',
                    'Content-type' => 'application/json'
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            if ( isset( $data['data'] ) && is_numeric($data['data']) && $data['data'] > 0 ) {
                return $this->error_response($data);
            }
            return $this->createWarehouseCert($certification);
        } catch (Exception $exception) {

            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    __('contractor.not_found'),
                    422
                );
            }

            return $this->error_response(
                __('contractor.error'),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse
     */
    public function sendWareHouseNotification(PeaceAndSafeRequest $request)
    {
        try {
            $contract = format_contract($request->get('contract'), $request->get('year'));
            $contractor = Contractor::where('document', $request->get('document'))
                ->whereHas('contracts', function ($query) use ($contract) {
                    return $query->where('contract', $contract);
                })->with([
                    'contracts' => function($query) use ($contract) {
                        return $query->where('contract', $contract)->latest()->first();
                    }
                ])->firstOrFail();

            $certification = Certification::where([
                                            ['document',   $contractor->document],
                                            ['contract',   $contract],
                                            ['type'    ,   'ALM'],
                                        ])->firstOrFail();

            $this->dispatch(new VerificationCode($contractor, $certification));
            return $this->success_message(
                mask_email($contractor->email)
            );
        } catch (Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    __('contractor.not_found'),
                    422
                );
            }

            return $this->error_response(
                __('contractor.error'),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param $code
     * @return JsonResponse
     */
    public function validateCode(Request $request, $code)
    {
        try {
            $certification = Certification::where('code', $code)->firstOrFail();
            $certification->code = null;
            $certification->save();
            return $this->success_message( $this->getWareHouseData($request) );
        } catch (Exception $exception) {
            return $this->error_response(
                __('contractor.invalid_code'),
                422
            );
        }
    }

    /**
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse
     */
    public function paginateData(PeaceAndSafeRequest $request)
    {
        try {
            return $this->success_message( $this->getWareHouseData($request) );
        } catch (Exception $exception) {
            return $this->error_response(
                __('contractor.error'),
                422
            );
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getWareHouseData(Request $request)
    {
        $page = $request->has('page') ? $request->get('page') : 1;
        $http = new Client();
        $response = $http->post("http://66.70.171.168/api/contractors-portal/oracle", [
            'json' => [
                'document' => $request->get('document'),
            ],
            'headers' => [
                'Accept'    => 'application/json',
                'Content-type' => 'application/json'
            ],
            'query' => [
                'per_page'    => $this->per_page,
                'page' => $page
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function countWareHouse(Request $request)
    {
        try {
            $certification = $this->saveInDatabase($request, 'ALM');
            /*
            if (isset($certification->token)) {
                return $this->createWarehouseCert($certification);
            }
            */
            $http = new Client();
            $response = $http->post("http://66.70.171.168/api/contractors-portal/oracle-count", [
                'json' => [
                    'document' => $request->get('document'),
                ],
                'headers' => [
                    'Accept'    => 'application/json',
                    'Content-type' => 'application/json'
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            if ( isset( $data['data'] ) && is_numeric($data['data']) && $data['data'] > 0 ) {
                return $this->error_response($data);
            }
            return $this->createWarehouseCert($certification);
        } catch (Exception $exception) {
            return $this->error_response(
                __('contractor.error'),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function excelWare(PeaceAndSafeRequest $request)
    {
        try {
            $contractor = Contractor::where('document', $request->get('document'))->first();
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
            // return (new WareHouseExport($collections))->download('INVENTARIO_ALMACEN.xlsx', Excel::XLSX);
            $writer = new WareHouseExportTemplate($contractor, $collections);
            /*
            $date = date('d-m-y-'.substr((string)microtime(), 1, 8));
            $date = str_replace(".", "", $date);
            $filename = "export_".$date.".xlsx";
            $content = file_get_contents($filename);
            unlink($filename);
            */

            $response = response()->streamDownload(function() use ($writer) {
                $writer->create()->save('php://output');
            });
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="TRASLADO_ELEMENTOS.xlsx"');
            return $response->send();

        } catch (Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    __('contractor.not_found'),
                    422
                );
            }
            return $this->error_response(
                __('contractor.error'),
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Certification $certification
     * @return string
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     */
    public function createWarehouseCert(Certification $certification)
    {
        $day = intval(now()->format('d'));
        $day = $day > 1 ? "a los <b>$day</b> días" : "al <b>primer</b> día";
        $month = intval(now()->format('m'));
        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $m = isset($months[$month]) ? $months[$month] : toLower(now()->format('M'));
        $year = now()->format('Y');
        $virtual_file = $certification->virtual_file;
        $complete_text = $virtual_file
            ? "<b>{$certification->contract}</b> y número de expediente: <b>{$certification->virtual_file}</b>"
            : "<b>{$certification->contract}</b>";

        $name = $certification->name;

        $text  = "<p>Que, una vez revisado en el módulo de Activos Fijos del Sistema de Información Administrativo y ";
        $text .= "Financiero <b>SEVEN</b> del Instituto Distrital de Recreación y Deporte - IDRD,  el Almacenista General ";
        $text .= "de la Entidad certifica que el(la) señor(a): <b>$name</b>. identificado(a) con cédula de ciudadanía ";
        $text .= "No. <b>{$certification->document}</b>, no tiene, ningún elemento o activo, bajo su cargo.</p>";
        $text .= "<p>Se expide el presente certificado de cumplimiento de entrega de bienes por parte de contratistas a ";
        $text .= "solicitud del peticionario con el fin de realizar los trámites administrativos correspondientes con ";
        $text .= "motivo de la <b>TERMINACIÓN O CESIÓN DE CONTRATO DE PRESTACION DE SERVICIOS Y/O DE APOYO ";
        $text .= "A LA GESTIÓN No.</b> $complete_text.</p>";
        $text .= "<p></p>";
        $text .= "<p>Presente certificación, firmada en <b>Bogotá D.C.</b> $day del mes de <b>$m</b> de <b>$year</b>.</p>";
        $text .= "<p></p>";
        $text.= "<small><b>NOTA:</b> Lo anterior en cumplimiento con lo previsto en el Manual de Procedimientos Administrativos y Contables para el ";
        $text.= "manejo y control de los bienes en las Entidades de Gobierno Distritales, adoptado mediante la Resolución No. 001 del 30 ";
        $text.= "de septiembre de 2019 expedida por la Secretaria de Hacienda de Bogotá. </small>";


        return $this->getPDF('PAZ_Y_SALVO_ALMACEN.pdf', $text, $certification)->Output('I', 'PAZ_Y_SALVO_ALMACEN.pdf');
    }

    /**
     * @param Certification $certification
     * @return JsonResponse|string
     */
    public function generateCertificate(Certification $certification)
    {
        try {
            $user = User::where('usua_doc', $certification->document)->first();

            // TODO: Check date in other platform
            $contract_query = Contract::query()
                ->where('contract', $certification->contract)
                ->where('contractor_id', $certification->contractor_id)
                ->get(['final_date']);

            $name = $certification->name;
            $document = $certification->document;
            $contract = $certification->contract;
            $virtual_file = $certification->virtual_file;
            $expires_at = $certification->expires_at;

            $complete_text = $virtual_file
                ? ", número de contrato: <b>{$contract}</b> y número de expediente: <b>{$virtual_file}</b>"
                : " y número de contrato: <b>{$contract}</b>";

            if ($this->doesntHaveOrfeo($user)) {
                if ($this->doesntHaveLDAP($document, 'postalcode')) {
                    $sub_day = Carbon::parse($expires_at)->subDay();
                    if (isset($expires_at) && now()->lessThanOrEqualTo( $sub_day )) {
                        return $this->error_response(
                            __('contractor.contract_date', ['date' => $sub_day]),
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            __('contractor.no_accounts')
                        );
                    }
                    $text = $this->createText($name, $document, $complete_text);
                    return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output();
                }
                if ($this->cantCreateDocument($expires_at)) {
                    $date = $this->getExpireDate($expires_at);
                    return $this->error_response(
                        __('contractor.contract_date', ['date' => $date]),
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        __('contractor.only_email')
                    );
                }
                if ($this->hasLDAP($document, 'postalcode')) {
                    $this->disableLDAP();
                    $certification->username = $this->user->getFirstAttribute('samaccountname');
                    $certification->save();
                    $text = $this->createText(
                        $certification->name,
                        $certification->document,
                        $complete_text,
                        $certification->username,
                        false
                    );
                    return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output('I', 'PAZ_Y_SALVO.pdf');
                }
            }

            $username = isset($user->usua_login) ? $user->usua_login : 0;
            if ($this->hasLDAP($username) ) {
                if (
                    // $this->accountIsActive() &&
                    $this->cantCreateDocument($expires_at)
                ) {
                    $date = $this->getExpireDate($expires_at);
                    return $this->error_response(
                        __('contractor.contract_date', ['date' => $date]),
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        __('contractor.with_accounts')
                    );
                }
            }
            $certification->username = isset($this->user)
                    ? $this->user->getFirstAttribute('samaccountname')
                    : $username;
            $certification->save();
            $total = $this->hasUnprocessedData($user->usua_codi);
            if ( $total['total'] > 0 ) {
                array_push($total, ['result' => $this->cantCreateDocument($expires_at)]);
                return $this->error_response(
                    trans_choice('contractor.orfeo_exception', $total['total'], ['count' => $total['total']]),
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $total
                );
            }
            /*
             * Disable Orfeo and LDAP Account
            */
            $certification->expires_at = null;
            $certification->save();
            $user->usua_esta = 0;
            $user->saveOrFail();
            $this->disableLDAP();
            $text = $this->createText(
                $certification->name,
                $certification->document,
                $complete_text,
                toUpper($username)
            );
            return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output('I', 'PAZ_Y_SALVO.pdf');
        } catch (Exception $e) {
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param $value
     * @param string $attribute
     * @return boolean
     */
    public function hasLDAP($value, $attribute = 'samaccountname')
    {
        $this->user = $this->ldap->search()->findBy($attribute, toLower($value));
        return isset( $this->user->samaccountname );
    }

    /**
     * @param $value
     * @param string $attribute
     * @return boolean
     */
    public function doesntHaveLDAP($value, string $attribute = 'samaccountname'): bool
    {
        return !$this->hasLDAP($value, $attribute);
    }

    /**
     * @param $user
     * @return bool
     */
    public function hasOrfeo($user)
    {
        return isset($user->usua_login);
    }

    /**
     * @param $user
     * @return bool
     */
    public function doesntHaveOrfeo($user)
    {
        return !isset($user->usua_login);
    }

    /**
     * @param $id
     * @return array
     */
    public function hasUnprocessedData($id)
    {
        $data = DB::connection('pgsql_orfeo')
            ->table('radicado')
            ->select(DB::raw("carpeta.carp_desc AS folder, COUNT(*) AS filed_count"))
            ->leftJoin('carpeta', 'carpeta.carp_codi', '=', 'radicado.carp_codi')
            ->where('radicado.radi_usua_actu', $id)
            ->groupBy('carpeta.carp_codi')
            ->get()->toArray();
        $informed = Informed::query()->where('usua_codi', $id)->count();
        if ($informed > 0) {
            array_push($data, ['folder' => 'Informados', 'filed_count' => $informed]);
        }
        $collect = collect($data);
        $total = $collect->sum('filed_count');
        return [
            'folders'   =>  $data,
            'total'     => $total,
        ];
    }

    /**
     * @return bool
     */
    public function accountIsExpired()
    {
        return !$this->accountIsActive();
    }

    /**
     * @return bool
     */
    public function accountIsActive()
    {
        return isset($this->user) && $this->user->isActive();
    }

    /**
     * @param null $expires_at
     * @return bool
     */
    public function canCreateDocument($expires_at = null)
    {
        if (isset($this->user)) {
            $exp_day_account = ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires'));
            $expires_at = isset($expires_at) ? $expires_at : $exp_day_account;
            return now()->startOfDay()->greaterThanOrEqualTo(Carbon::parse($expires_at)->startOfDay());
        }
        return false;
    }

    /**
     * @param null $expires_at
     * @return bool
     */
    public function cantCreateDocument($expires_at = null)
    {
        return ! $this->canCreateDocument($expires_at);
    }

    /**
     * @param null $expires_at
     * @return string|null
     */
    public function getExpireDate($expires_at = null)
    {
        if (isset($this->user)) {
            $exp_day_account = ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires'));
            $expires_at = isset($expires_at) ? $expires_at : $exp_day_account;
            return Carbon::parse($expires_at)->startOfDay()->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * @param $username
     * @param string $ous
     * @return JsonResponse
     */
    public function enableLDAP($username, $ous = 'OU=AREA DE SISTEMAS,OU=SUBDIRECCION ADMINISTRATIVA Y FINANCIERA,OU=ORGANIZACION IDRD')
    {
        try {
            if ($this->hasLDAP($username)) {
                // Get a new account control object for the user.
                $ac = $this->user->getUserAccountControlObject();
                // Mark the account as normal (512).
                $ac->accountIsNormal();
                // Set the account control on the user and save it.
                $this->user->setUserAccountControl(512);
                // Move user to new OU
                $this->user->move($ous);
                // Add two days for expiration date
                $this->user->setAccountExpiry(now()->addDay()->timestamp);
                // Sets the option to disable forcing a password change at the next logon.
                $this->user->setFirstAttribute('pwdlastset', -1);
                // Save the user.
                $this->user->save();
                return $this->success_message('Usuario activado y listo para usar', 200, 200, [
                    'expires_at'    => now()->addDay()
                ]);
            }
            return $this->error_response('No se encuentra usuario LDAP');
        } catch (Exception $exception) {
            return $this->error_response('No se encuentra usuario LDAP', 422, $exception->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function disableLDAP()
    {
        try {
            if (isset($this->user)) {
                // Find inactive OU
                $ou = $this->ldap->search()->ous()->find('INACTIVOS');
                // Get a new account control object for the user.
                $ac = $this->user->getUserAccountControlObject();
                // Mark the account as disabled (514).
                $ac->accountIsDisabled();
                // Set the account control on the user and save it.
                $this->user->setUserAccountControl($ac);
                // Add two days for expiration date
                $this->user->setAccountExpiry(now()->timestamp);
                // Sets the option to force the password change at the next logon.
                $this->user->setFirstAttribute('pwdlastset', 0);
                // Save the user.
                $this->user->save();
                // Move user to new OU
                return $this->user->move($ou);
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function disableLDAPManual($username)
    {
        try {
            // Find inactive OU
            $ou = $this->ldap->search()->ous()->find('INACTIVOS');
            // Find user
            $this->user = $this->ldap->search()->findBy('samaccountname', toLower($username));
            // Get a new account control object for the user.
            $ac = $this->user->getUserAccountControlObject();
            // Mark the account as disabled (514).
            $ac->accountIsDisabled();
            // Set the account control on the user and save it.
            $this->user->setUserAccountControl($ac);
            // Add two days for expiration date
            $this->user->setAccountExpiry(now()->timestamp);
            // Sets the option to force the password change at the next logon.
            $this->user->setFirstAttribute('pwdlastset', 0);
            // Save the user.
            $this->user->save();
            // Move user to new OU
            return $this->user->move($ou);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $name
     * @param $document
     * @param $contract_info
     * @param null $username
     * @param bool $hasOrfeo
     * @return string
     */
    public function createText($name, $document, $contract_info, $username = null, $hasOrfeo = true)
    {
        $day = intval(now()->format('d'));
        $day = $day > 1 ? "a los <b>{$day}</b> días" : "al <b>primer</b> día";
        $month = intval(now()->format('m'));
        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $m = isset($months[$month]) ? $months[$month] : toLower(now()->format('M'));
        $year = now()->format('Y');

        // NEW TEXT
        $text = "<p>Que revisados los buzones habilitados por el Instituto Distrital de Recreación y Deporte - IDRD al ";
        $text .= "señor(a) <b>$name</b>, identificado(a) con la cédula de ciudadanía No. <b>$document</b>, se encontró que el ";
        $text .= "(la) contratista a la fecha de expedición de la presente certificación no tiene en el buzón correos o ";
        $text .= "documentos pendientes por tramitar o descargar del Sistema de Gestión Documental <b>\"ORFEO\"</b> e ";
        $text .= "institucional utilizados y/o administrados por la entidad. Así mismo se inactivan los demás servicios ";
        $text .= "de correo institucional, sistemas de información de apoyo y misional y de red, cuando aplique.</p>";
        $text .= "<p>Se expide el presente certificado a solicitud del (la) contratista en la inactivación de cuentas de ";
        $text .= "usuario autorizado por el IDRD y utilizados por la solicitante, con el fin de realizar los trámites ";
        $text .= "administrativos con motivo de la <b>TERMINACIÓN O CESIÓN DE CONTRATO DE PRESTACION DE ";
        $text .= "SERVICIOS Y/O DE APOYO A LA GESTIÓN No.</b> {$contract_info}.</p>";
        $text .= "<p></p>";
        $text .= "<p>Presente certificación, firmada en <b>Bogotá D.C.</b> $day del mes de <b>$m</b> de <b>$year</b>.</p>";
        $text .= "<p></p>";
        $text.= "<small><b>NOTA:</b> Lo anterior en cumplimiento de la Cláusula segunda. - Obligaciones Generales del Anexo clausulas adicionales del ";
        $text.= "Contrato Electrónico de Prestación de Servicios Profesionales y de Apoyo a la Gestión en especial los numerales: 3, 7, 8, 14 ";
        $text.= "y 23 y memorando Rad. 20203000123583 de febrero 24 de 2020 expedido por la Subdirección Administrativa y Financiera.</small>";

        return $text;
    }

    /**
     * @param $file
     * @param $text
     * @param Certification $certification
     * @param string $orientation
     * @param string $unit
     * @param string $size
     * @return FPDF
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     */
    public function getPDF($file, $text, Certification $certification, $orientation = 'L', $unit = 'mm', $size = 'Letter')
    {
        $pdf = new FPDF($orientation, $unit, $size);

        $pdf->SetStyle("p","Helvetica","N",10,"0,0,0",15);
        $pdf->SetStyle("small","Helvetica","N",8,"0,0,0",0);
        $pdf->SetStyle("h1","Helvetica","N",14,"0,0,0",0);
        $pdf->SetStyle("a","Helvetica","BU",9,"0,0,0", 15);
        $pdf->SetStyle("pers","Helvetica","I",0,"0,0,0");
        $pdf->SetStyle("place","Helvetica","U",0,"0,0,0");
        $pdf->SetStyle("b","Helvetica","B",0,"0,0,0");
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile(storage_path("app/templates/{$file}"));
        // import page 1
        $tplId = $pdf->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $pdf->useTemplate($tplId, 0, 0, null, null, true);
        // Creation date and time
        $created_at = isset($certification->updated_at) ? $certification->updated_at->format('Y-m-d H:i:s') : null;
        $pdf->SetFont('Helvetica', 'B');
        $pdf->SetFontSize(8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(30, 38);
        $pdf->Cell(160,10, utf8_decode('Fecha de solicitud original: '.$created_at),0,0,'L');
        // Document Text
        $pdf->SetFont('Helvetica');
        $pdf->SetFontSize(10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetLeftMargin(30);
        $pdf->SetRightMargin(25);
        $pdf->SetXY(30, 75);
        $pdf->WriteTag(160, 5, utf8_decode($text));
        // Footer QR and document authentication
        $pdf->SetXY(30, 108);
        $name = isset( $certification->token ) ? $certification->token : $certification->type.'-'.Str::random(9);
        $path = env('APP_ENV') == 'local'
            ? env('APP_PATH_DEV')
            : env('APP_PATH_PROD');
        $url = "https://sim.idrd.gov.co/{$path}/es/validar-documento?validate=$name";
        QrCode::url($url)
            ->setErrorCorrectionLevel('H')
            ->setSize(10)
            ->setOutfile(storage_path("app/templates/{$name}.png"))
            ->png();
        $file = storage_path("app/templates/{$name}.png");
        $pdf->Image($file, 30, 195, 50, 50);
        $pdf->SetXY(80, 220);
        $pdf->SetFontSize(8);
        $x = 'La autenticidad de este documento se puede validar a través del enlace inferior.';
        $pdf->Write(5 , utf8_decode($x));
        $pdf->SetXY(80, 225);
        $pdf->Cell(30, 5, utf8_decode('O escaneando el código QR desde un dispositivo móvil.'));
        $pdf->SetXY(80, 235);
        $pdf->SetFontSize(12);
        $pdf->Cell(30, 5, utf8_decode('Código de verificación: '.$name));
        $pdf->SetFontSize(8);
        $pdf->SetXY(32, 245);
        $pdf->Write(5, $url, $url);
        if (Storage::disk('local')->exists("templates/{$name}.png")) {
            Storage::disk('local')->delete("templates/{$name}.png");
        }
        if (!isset( $certification->token )) {
            $certification->token = $name;
        }
        $certification->increment('downloads');
        $certification->save();
        return $pdf;
    }

    /*
    public function sample()
    {
        $contract = 'IDRD-CTO-0933-2021';
        $virtual_file = null;
        $complete_text = $virtual_file
            ? "<b>{$contract}</b> y número de expediente: <b>{$virtual_file}</b>"
            : "<b>{$contract}</b>";
        $text = $this->createText('DANIEL ALEJANDRO PRADO MENDOZA', 1073240539, $complete_text, 'daniel.prado');

        return $this->getPDF('PAZ_Y_SALVO.pdf', $text, new Certification)->Output('I', 'PAZ_Y_SALVO.pdf');
        // return $this->createWarehouseCert( Certification::find(70) );
    }
    */
}
