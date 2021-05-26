<?php


namespace App\Modules\Contractors\src\Controllers;


use Adldap\AdldapInterface;
use Adldap\Utilities;
use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\ActiveRecordResource;
use App\Modules\Contractors\src\Exports\WareHouseExport;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Request\ConsultPeaceAndSafeRequest;
use App\Modules\Contractors\src\Request\EnableLDAPRequest;
use App\Modules\Contractors\src\Request\PeaceAndSafeRequest;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Models\Informed;
use App\Modules\Orfeo\src\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelQRCode\Facades\QRCode;
use Maatwebsite\Excel\Excel;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Tightenco\Collect\Support\Collection;

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
        $contract_number = str_pad($request->get('contract'), 4, '0', STR_PAD_LEFT);
        $contract = toUpper("IDRD-CTO-{$contract_number}-{$request->get('year')}");
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
    }

    /**
     * Display a listing of the resource.
     *
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|string
     */
    public function index(PeaceAndSafeRequest $request)
    {
        $certification = $this->saveInDatabase($request, 'SYS');
        return $this->generateCertificate($certification);
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
                $request->has('token') && ($request->get('token') != "" || !is_null($request->get('token'))),
                function ($query) use ($request) {
                    return $query->where('token', $request->get('token'));
                },
                function ($query) use ($contract, $request) {
                    return $query->where('contract', 'like', "%{$contract}%")
                        ->where('document', $request->get('document'));
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
                __('validation.handler.resource_not_found'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
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
            return $this->success_message($certification);
        } catch (Exception $exception) {
            return $this->error_response(
                "No se encuentró ningún certificado válido para el token {$token}.",
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
            if (isset($certification->token)) {
                return $this->createWarehouseCert($certification);
            }
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
            $data = json_decode($response->getBody()->getContents(), true);
            if ( isset( $data['data'] ) && count($data['data']) > 0 ) {
                return $this->error_response($data);
            }
            return $this->createWarehouseCert($certification);
        } catch (Exception $exception) {
            return $this->error_response(
                'No podemos realizar la consulta en este momento, por favor intente más tarde.',
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelWare(PeaceAndSafeRequest $request)
    {
        try {
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
            return (new WareHouseExport($collections))->download('INVENTARIO_ALMACEN.xlsx', Excel::XLSX);
        } catch (Exception $exception) {
            return $this->error_response(
                'No podemos realizar la consulta en este momento, por favor intente más tarde.',
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
        $day = $day > 1 ? "a los {$day} días" : "al primer día";
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
            ? ", número de contrato: <b>{$certification->contract}</b> y número de expediente: <b>{$certification->virtual_file}</b>"
            : " y número de contrato: <b>{$certification->contract}</b>";

        $name = $certification->name;

        $text  = "<p>Que, en cumplimiento a lo previsto en el numeral 2.2 del Manual de Procedimientos Administrativos y Contables ";
        $text .= "para el manejo y control de los bienes en las Entidades de Gobierno Distritales, adoptado mediante la Resolución ";
        $text .= "001 del 30 de septiembre de 2019 expedida por el Contador General de Bogotá, donde se establece como estrategia de ";
        $text .= "control y buena gestión, que el servidor público al momento del retiro y el contratista al finalizar su relación ";
        $text .= "contractual, entregue a través de los documentos establecidos, los elementos que tenía a su cargo; para la generación ";
        $text .= "del certificado de recibo a satisfacción por parte del área competente, en concordancia con lo dispuesto en el Código General ";
        $text .= "Disciplinario Ley 1952 del 28 de enero de 2019, que deroga la Ley 734 de 2002, a partir del 01 de julio de 2021 y los lineamientos ";
        $text .= "internos emitidos por la Subdirección Administrativa y Financiera en lo que respecta a los traslados o reintegros de bienes al Almacén General, ";
        $text .= "se evidencia: </p>";
        $text .= "<p>Que, una vez revisado en el módulo de Almacén e Inventarios del Sistema Administrativo y Financiero –SEVEN– de la Entidad los datos del(la) funcionario(a): ";
        $text .= "<b>{$name}</b> identificado(a) con cédula de ciudadanía No. <b>{$certification->document}</b>{$complete_text}, ";
        $text .= "no tiene a la fecha, ningún elemento o activo, bajo su cargo. </p>";
        $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";


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
                    $text = $this->createText($name, $document, $complete_text);
                    return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output();
                }
                if ($this->accountIsActive() && $this->cantCreateDocument($expires_at)) {
                    $date = $this->getExpireDate($expires_at);
                    return $this->error_response(
                        "El Servicio de Paz y Salvo del Área de Sistemas estará disponible a partir de la fecha {$date}.",
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        'Usuario sin cuenta de ORFEO pero con cuenta institucional'
                    );
                }
                if ($this->hasLDAP($document, 'postalcode')) {
                    $this->disableLDAP();
                    $certification->name = toUpper($this->user->getFirstAttribute('givenname').' '.$this->user->getFirstAttribute('sn'));
                    $certification->username = $this->user->getFirstAttribute('samaccountname');
                    $certification->save();
                    $text = $this->createText(
                        $certification->name,
                        $this->user->getFirstAttribute('postalcode'),
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
                    $this->accountIsActive() &&
                    $this->cantCreateDocument($expires_at)
                ) {
                    $date = $this->getExpireDate($expires_at);
                    return $this->error_response(
                        "El Servicio de Paz y Salvo del Área de Sistemas estará disponible a partir de la fecha {$date}.",
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        'Usuario con cuenta de ORFEO y LDAP'
                    );
                }
            }
            $certification->username = $this->user->getFirstAttribute('samaccountname');
            $certification->name = toUpper($this->user->getFirstAttribute('givenname').' '.$this->user->getFirstAttribute('sn'));
            $certification->save();
            $total = $this->hasUnprocessedData($user->usua_codi);
            if ( $total['total'] > 0 ) {
                $certification->expires_at = ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires') );
                $certification->save();
                array_push($total, ['result' => $this->cantCreateDocument($expires_at)]);
                return $this->error_response(
                    "Para generar el paz y salvo de sistemas debe tener sus bandejas de Orfeo en cero, actualmente cuenta con {$total['total']} radicado(s) sin procesar.",
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
                $this->user->getFirstAttribute('postalcode'),
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
            return Carbon::parse($expires_at)->startOfDay()->equalTo(now()->startOfDay());
        }
        return false;
    }

    public function cantCreateDocument($expires_at = null)
    {
        return ! $this->canCreateDocument($expires_at);
    }

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
        } catch (Exception $exception) {
            return false;
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
        $day = $day > 1 ? "a los {$day} días" : "al primer día";
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
        $text = "<p>Que, en cumplimiento a lo previsto en la Ley 594 del 14 de julio de 2000 <pers>\"Ley General de Archivos\"</pers>, ";
        $text .= "que relaciona las responsabilidades que tienen los servidores públicos mientras cumplen su función y aún después de ";
        $text .= "que estos hayan finalizado su relación con las instituciones, así como las implicaciones jurídicas, disciplinarias, ";
        $text .= "civiles y penales, por la gestión o manejo documental, en concordancia con lo dispuesto en el Código General ";
        $text .= "Disciplinario Ley 1952 del 28 de enero de 2019, que deroga la Ley 734 de 2002, a partir del 01 de julio de 2021 y los ";
        $text .= "lineamientos internos emitidos por la Subdirección Administrativa y financiera, donde se contempla la verificación del ";
        $text .= "Sistema de Gestión Documental - Orfeo, para constatar la inexistencia de radicados pendientes de trámite al momento de la ";
        $text .= "terminación del contrato para contratistas y/o desvinculación, traslado ó encargo para los servidores públicos del IDRD, se evidencia: </p>";
        $text .= "<p>Que, una vez revisado el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a):  <b>{$name}</b>, ";
        $text .= "identificado(a) con cédula de ciudadanía No. <b>{$document}</b>{$contract_info}, ";
        if ($username) {
            $text .= "no tiene a la fecha, documentos en soporte papel y/o electrónico de archivo ni de trámite a su cargo; y no tiene comunicaciones oficiales pendientes por entregar, ";
            $text .= "recibir, tramitar, organizar o finalizar, de acuerdo al reporte generado por el Sistema de Gestión Documental y se procede en consecuencia, a ";
            $text .= "inactivar el usuario de acceso <b>{$username}</b> a todos los aplicativos utilizados o administrados por la entidad de los cuales se requirió ";
            $text .= "ingreso entre otros como: correo institucional, sistemas administrativos y financieros.</p>";
        } else {
            $text .= "no tiene a la fecha cuenta de acceso a los aplicativos utilizados o administrados por la entidad.</p>";
        }

        $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a la novedad de: <b>TERMINACIÓN DE CONTRATO.</b></p>";

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
        $created_at = isset($certification->created_at) ? $certification->created_at->format('Y-m-d H:i:s') : null;
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
        $pdf->Image($file, 30, 200, 50, 50);
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
        $pdf->SetXY(32, 248);
        $pdf->Write(5, $url, $url);
        if (Storage::disk('local')->exists("templates/{$name}.png")) {
            Storage::disk('local')->delete("templates/{$name}.png");
        }
        if (!isset( $certification->token )) {
            $certification->token = $name;
            $certification->save();
        }
        return $pdf;
    }

    /*
    public function sample()
    {
        $contract = 'IDRD-CTO-0933-2021';
        $virtual_file = '2897348973497E';
        $complete_text = $virtual_file
            ? ", número de contrato: <b>{$contract}</b> y número de expediente: <b>{$virtual_file}</b>"
            : " y número de contrato: <b>{$contract}</b>";
        $text = $this->createText('DANIEL ALEJANDRO PRADO MENDOZA', 1073240539, $complete_text, 'daniel.prado');
        return $this->getPDF('PAZ_Y_SALVO_ALMACEN.pdf', $text, new Certification)->Output('I', 'PAZ_Y_SALVO_ALMACEN.pdf');
    }
    */
}
