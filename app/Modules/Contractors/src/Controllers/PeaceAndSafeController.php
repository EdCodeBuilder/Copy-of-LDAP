<?php


namespace App\Modules\Contractors\src\Controllers;


use Adldap\AdldapInterface;
use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\ActiveRecordResource;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Request\PeaceAndSafeRequest;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Models\Informed;
use App\Modules\Orfeo\src\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelQRCode\Facades\QRCode;

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

    public function pdf(\App\Models\Security\User $user)
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
        $text.= "<p>Por lo anterior y una vez verificado en el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a) <b>{$user->full_name}</b>, identificado(a) con cédula de ciudadanía No. <b>{$user->document}</b>, número de contrato: <b>068/2019</b> y número de expediente: <b>201980020110014E</b>, se certifica que <b>NO</b> se creó cuenta de acceso en aplicativo Orfeo durante el término su contrato.</p>";
        $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";
        return $this->getPDF('PAZ_Y_SALVO.pdf', $text, new Certification)->Output();
    }

    /**
     * Display a listing of the resource.
     *
     * @param PeaceAndSafeRequest $request
     * @return JsonResponse
     */
    public function index(PeaceAndSafeRequest $request)
    {
        try {
            $certification = new Certification;
            $certification->fill($request->validated());
            $user = User::where('usua_doc', $request->get('document'))->first();
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
            if (!isset($user->usua_login)) {
                if ($this->doesntHaveLDAP($request->get('document'), 'postalcode')) {
                    $name = toUpper($request->get('name'));
                    $text = "<p>Que, dando cumplimiento a lo estipulado en el memorando con número de radicado <b>20203000123583</b> de febrero 24 de 2020, expedido por la <b>Subdirección Administrativa y Financiera</b> e informado a todas las dependencias, se debe verificar que el <b>Sistema de Gestión Documental - Orfeo -</b> no tenga radicados pendientes de trámite y esté al día al momento de finalizar contrato para contratistas y/o desvinculación, traslado ó encargo para los servidores públicos del <b>IDRD</b>.</p>";
                    $text.= "<p>Por lo anterior y una vez verificado en el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a) <b>{$name}</b>, identificado(a) con cédula de ciudadanía No. <b>{$request->get('document')}</b>, número de contrato: <b>{$request->get('contract')}</b> y número de expediente: <b>{$request->get('virtual_file')}</b>, se certifica que no se creó cuenta de acceso en aplicativo Orfeo durante el término su contrato.</p>";
                    $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";
                    return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output();
                }
                if ($this->accountIsActive()) {
                    return $this->error_response(
                        "El Servicio de Paz y Salvo del Área de Sistemas estará disponible posterior al vencimiento de su contrato.",
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        $this->user
                    );
                }
            }

            $username = isset($user->usua_login) ? $user->usua_login : 0;
            if ($this->hasLDAP($username) && $this->accountIsActive()) {
                return $this->error_response(
                    "El Servicio de Paz y Salvo del Área de Sistemas estará disponible posterior al vencimiento de su contrato.",
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $this->user
                );
            }

            $filed = Filed::query()->where('radi_usua_actu', $user->usua_codi)->count();
            $informed = Informed::query()->where('usua_codi', $user->usua_codi)->count();
            $total = (int) $filed + (int) $informed;
            if ( $total > 0 ) {
                return $this->error_response("Para generar el paz y salvo de sistemas debe tener sus bandejas de Orfeo en cero, actualmente cuenta con {$total} radicado(s) sin procesar.");
            }
            /*
            $ou = 'OU=INACTIVOS,OU=ORGANIZACION IDRD,DC=adidrd,DC=local';
            $this->user->setAccountControl('514');
            if ($this->user->move($ou)) {
                $text = "<p>Que, dando cumplimiento a lo estipulado en el memorando con número de radicado <b>20203000123583</b> de febrero 24 de 2020, expedido por la <b>Subdirección Administrativa y Financiera</b> e informado a todas las dependencias, se debe verificar que el <b>Sistema de Gestión Documental - Orfeo -</b> no tenga radicados pendientes de trámite y esté al día al momento de finalizar contrato para contratistas y/o desvinculación, traslado ó encargo para los servidores públicos del <b>IDRD</b>.</p>";
                $text.= "<p>Por lo anterior y una vez verificado en el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a) <b>{$user->full_name}</b>, identificado(a) con cédula de ciudadanía No. <b>{$user->document}</b>, número de contrato: <b>068/2019</b> y número de expediente: <b>201980020110014E</b>, a la fecha no tiene radicados pendientes de trámite y se procede a inactivar el usuario: <b>{$user->username}</b>.</p>";
                $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";
                return $this->getPDF('PAZ_Y_SALVO.pdf', $text)->Output();
            }
            */
            $text = "<p>Que, dando cumplimiento a lo estipulado en el memorando con número de radicado <b>20203000123583</b> de febrero 24 de 2020, expedido por la <b>Subdirección Administrativa y Financiera</b> e informado a todas las dependencias, se debe verificar que el <b>Sistema de Gestión Documental - Orfeo -</b> no tenga radicados pendientes de trámite y esté al día al momento de finalizar contrato para contratistas y/o desvinculación, traslado ó encargo para los servidores públicos del <b>IDRD</b>.</p>";
            $text.= "<p>Por lo anterior y una vez verificado en el Sistema de Gestión Documental - Orfeo - a cargo del(la) funcionario(a) <b>{$user->name}</b>, identificado(a) con cédula de ciudadanía No. <b>{$user->document}</b>, número de contrato: <b>{$request->get('contract')}</b> y número de expediente: <b>{$request->get('virtual_file')}</b>, a la fecha no tiene radicados pendientes de trámite y se procede a inactivar el usuario: <b>{$user->usua_login}</b>.</p>";
            $text.= "<p>Se expide certificado de paz y salvo por solicitud del usuario {$day} del mes de {$m} del año {$year} debido a: <b>TERMINACIÓN DE CONTRATO.</b></p>";
            return $this->getPDF('PAZ_Y_SALVO.pdf', $text, $certification)->Output();
        } catch (\Exception $e) {
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
     * @return bool
     */
    public function accountIsExpired()
    {
        $expiration_date = isset($this->user->accountexpires[0]) ? ldapDateToCarbon( $this->user->getFirstAttribute('accountexpires') ) : now()->addYears(3);
        return now()->isAfter($expiration_date);
    }

    public function accountIsActive()
    {
        return ! $this->accountIsExpired();
    }

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
        $name = Str::random(9);
        $url = "https://sim.idrd.gov.co/portal-comtratista/validacion-documento/$name";
        QrCode::url($url)
            ->setErrorCorrectionLevel('H')
            ->setSize(10)
            ->setOutfile(storage_path("app/templates/{$name}.png"))
            ->png();
        $file = storage_path("app/templates/{$name}.png");
        $certification->token = $name;
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
        $certification->save();
        return $pdf;
    }
}
