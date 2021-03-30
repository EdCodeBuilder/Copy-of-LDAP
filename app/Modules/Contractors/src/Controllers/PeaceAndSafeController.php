<?php


namespace App\Modules\Contractors\src\Controllers;


use Adldap\AdldapInterface;
use Adldap\Utilities;
use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\ActiveRecordResource;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Request\ConsultPeaceAndSafeRequest;
use App\Modules\Contractors\src\Request\EnableLDAPRequest;
use App\Modules\Contractors\src\Request\PeaceAndSafeRequest;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Models\Informed;
use App\Modules\Orfeo\src\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * Display a listing of the resource.
     *
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse|string
     */
    public function index(PeaceAndSafeRequest $request)
    {
        $contract_number = str_pad($request->get('contract'), 4, '0', STR_PAD_LEFT);
        $contract = toUpper("IDRD-CTO-{$contract_number}-{$request->get('year')}");
        $certification = Certification::where('document', $request->get('document'))
                                      ->where('contract', $contract)
                                      ->first();
        if (isset($certification->id)) {
            return $this->generateCertificate($certification);
        }
        $certification = new Certification;
        $certification->fill($request->validated());
        $name = toUpper($request->get('name'));
        $surname = toUpper($request->get('surname'));
        $certification->name = "{$name} {$surname}";
        $certification->contract = $contract;
        $certification->save();
        return $this->generateCertificate($certification);
    }

    public function show(ConsultPeaceAndSafeRequest $request)
    {
        $contract_number = str_pad($request->get('contract'), 4, '0', STR_PAD_LEFT);
        $contract = toUpper("IDRD-CTO-{$contract_number}-{$request->get('year')}");
        $certification = Certification::query()->when(
            $request->has('token'),
            function ($query) use ($request) {
                return $query->where('token', $request->get('token'));
            },
            function ($query) use ($contract, $request) {
                return $query->where('contract', $contract)
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
                if ($this->accountIsActive()) {
                    return $this->error_response(
                        "El Servicio de Paz y Salvo del Área de Sistemas estará disponible posterior al vencimiento de su contrato.",
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
                $new_expire_date = ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires') );
                if ($this->accountIsActive() && !(isset($expires_at) && abs( $expires_at->diffInDays($new_expire_date) ) <= 3)) {
                    return $this->error_response(
                        "El Servicio de Paz y Salvo del Área de Sistemas estará disponible posterior al vencimiento de su contrato.",
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        'Usuario con cuenta de ORFEO y LDAP'
                    );
                }
            }
            $certification->username = $this->user->getFirstAttribute('samaccountname');
            $certification->name = toUpper($this->user->getFirstAttribute('givenname').' '.$this->user->getFirstAttribute('sn'));
            $certification->save();
            $total = $this->hasUnprocessedData($user->usua_codi);
            if ( $total > 0 ) {
                $certification->expires_at = ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires') );
                $certification->save();
                return $this->error_response("Para generar el paz y salvo de sistemas debe tener sus bandejas de Orfeo en cero, actualmente cuenta con {$total} radicado(s) sin procesar.");
            }
            /*
             * Disable Orfeo and LDAP Account
            */
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
     * @return bool
     */
    public function hasUnprocessedData($id)
    {
        $filed = Filed::query()->where('radi_usua_actu', $id)->count();
        $informed = Informed::query()->where('usua_codi', $id)->count();
        return (int) $filed + (int) $informed;
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

        $text = "<p>Que, dando cumplimiento a lo estipulado en el memorando con número de radicado <b>20203000123583</b> de febrero 24 de 2020, expedido por la <b>Subdirección Administrativa y Financiera</b> e informado a todas las dependencias, se debe verificar que el <b>Sistema de Gestión Documental - Orfeo -</b> no tenga radicados pendientes de trámite y esté al día al momento de finalizar contrato para contratistas y/o desvinculación, traslado ó encargo para los servidores públicos del <b>IDRD</b>.</p>";
        $text.= "<p>Por lo anterior y una vez verificado en el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a) <b>{$name}</b>, identificado(a) con cédula de ciudadanía No. <b>{$document}</b>{$contract_info}, ";
        if ($username && $hasOrfeo) {
            $text.= "a la fecha <b>NO</b> tiene radicados pendientes de trámite y se procede a inactivar el usuario: <b>{$username}</b>.</p>";
        } elseif ($username && !$hasOrfeo) {
            $text.= "se certifica que <b>NO</b> se creó cuenta de acceso en aplicativo Orfeo durante el término su contrato y se procede a inactivar el usuario: <b>{$username}</b>.</p>";
        } else {
            $text.=  "se certifica que <b>NO</b> se creó cuenta de acceso en aplicativo Orfeo durante el término su contrato.</p>";
        }
        $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";
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

        $pdf->SetStyle("p","Helvetica","N",12,"0,0,0",15);
        $pdf->SetStyle("h1","Helvetica","N",18,"0,0,0",0);
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
        $pdf->SetFont('Helvetica', 'B');
        $pdf->SetFontSize(8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(30, 38);
        $pdf->Cell(160,10, 'Fecha: '.now()->format('Y-m-d H:i:s'),0,0,'L');
        // Document Text
        $pdf->SetFont('Helvetica');
        $pdf->SetFontSize(11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetLeftMargin(30);
        $pdf->SetRightMargin(25);
        $pdf->SetXY(30, 75);
        $pdf->WriteTag(160, 5, utf8_decode($text));
        // Footer QR and document authentication
        $pdf->SetXY(30, 108);
        $name = isset( $certification->token ) ? $certification->token : Str::random(9);
        $url = "https://sim.idrd.gov.co/portal-comtratista/validacion-documento/$name";
        QrCode::url($url)
            ->setErrorCorrectionLevel('H')
            ->setSize(10)
            ->setOutfile(storage_path("app/templates/{$name}.png"))
            ->png();
        $file = storage_path("app/templates/{$name}.png");
        $pdf->Image($file, 30, 200, 50, 50);
        $pdf->SetXY(80, 220);
        $pdf->SetFontSize(8);
        $x = 'La autenticidad de este documento se puede validar a través de:';
        $pdf->Write(5 , utf8_decode($x));
        $pdf->SetXY(80, 225);
        $pdf->Write(5, $url, $url);
        $pdf->SetXY(80, 230);
        $pdf->Cell(30, 5, utf8_decode('O escaneando el código QR desde un dispositivo móvil.'));
        if (Storage::disk('local')->exists("templates/{$name}.png")) {
            Storage::disk('local')->delete("templates/{$name}.png");
        }
        if (!isset( $certification->token )) {
            $certification->token = $name;
            $certification->save();
        }
        return $pdf;
    }
}
