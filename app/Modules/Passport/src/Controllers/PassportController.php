<?php


namespace App\Modules\Passport\src\Controllers;


use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Models\Security\CityLDAP;
use App\Models\Security\CountryLDAP;
use App\Models\Security\StateLDAP;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\PassportConfig;
use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
use App\Modules\Passport\src\Models\User;
use App\Modules\Passport\src\Request\ShowPassportRequest;
use App\Modules\Passport\src\Request\StorePassportRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Throwable;

class PassportController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param StorePassportRequest $request
     * @return string
     */
    public function store(StorePassportRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user_attributes = $user->transformRequest( $request->all() );
            $user = User::query()
                ->updateOrCreate(
                    ['Cedula' => $request->get('document')],
                    $user_attributes
                );
            $user->type()->attach(6);
            $passport = new Passport();
            $passport_attrs = $passport->transformRequest($request->all());
            $user->passport()->create( $passport_attrs );
            DB::commit();
            return $this->createCard(
                $user->passport->id,
                $user->full_name,
                $user->document_type->name,
                $user->document
            )->Output('I', 'PASAPORTE_VITAL.pdf');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->error_response(
                "El servicio no está disponible, por favor intente más tarde.",
                422,
                $e->getMessage()
            );
        }
    }

    /**
     * @param ShowPassportRequest $request
     * @return JsonResponse
     */
    public function show(ShowPassportRequest $request)
    {
        try {
            $passport = PassportView::query()
                ->when($request->get('criterion') == 'document', function ($query) use ($request) {
                    return $query->where('document', $request->get('param'));
                })
                ->when($request->get('criterion') == 'passport', function ($query) use ($request) {
                    return $query->where('id', $request->get('param'));
                })->first([
                    'id',
                    'card_name',
                    'document',
                    'document_type_name',
                ]);

            if (!isset($passport->id)){
                $passport = PassportOldView::query()
                    ->when($request->get('criterion') == 'document', function ($query) use ($request) {
                        return $query->where('document', $request->get('param'));
                    })
                    ->when($request->get('criterion') == 'passport', function ($query) use ($request) {
                        return $query->where('id', $request->get('param'));
                    })->firstOrFail([
                        'id',
                        'card_name',
                        'document',
                        'document_type_name',
                    ]);
            }

            $data = [
                'passport'      => isset($passport->id) ? (int) $passport->id : null,
                'full_name'     => isset($passport->card_name) ? (string) $passport->card_name : null,
                'document_type' => isset($passport->document_type_name) ? (string) $passport->document_type_name : null,
                'document'      => isset($passport->document) ? (int) $passport->document : null,
            ];
            return $this->success_message($data);
        } catch (Exception $exception) {
            return $this->error_response(
                "No se encontró ningún pasaporte válido para los datos especificados.",
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @return JsonResponse|string
     */
    public function download($id)
    {
        try {
            $passport = PassportView::find($id);
            if ( !isset($passport->id) ) {
                $passport = PassportOldView::findOrFail($id);
            }
            return $this->createCard(
                $passport->id,
                $passport->card_name,
                $passport->document_type_name,
                $passport->document
            )->Output('I', 'PASAPORTE_VITAL.pdf');
        } catch (Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    'No se encuentra el pasaporte con los parámetros establecidos.',
                    422
                );
            }
            return $this->error_response(
                'No podemos realizar la consulta en este momento, por favor intente más tarde.',
                422,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param $passport
     * @param $name
     * @param $document_type
     * @param $document
     * @return FPDF
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     */
    public function createCard( $passport, $name, $document_type, $document )
    {
        if (!file_exists(base_path('vendor/setasign/fpdf/font/SourceCodePro-Bold.php'))) {
            copy(
                base_path('storage/app/templates/SourceCodePro-Bold.php'),
                base_path('vendor/setasign/fpdf/font/SourceCodePro-Bold.php')
            );
        }
        if (!file_exists(base_path('vendor/setasign/fpdf/font/SourceCodePro-Bold.z'))) {
            copy(
                base_path('storage/app/templates/SourceCodePro-Bold.z'),
                base_path('vendor/setasign/fpdf/font/SourceCodePro-Bold.z')
            );
        }
        $config = PassportConfig::query()->latest()->first();
        $pdf = new FPDF("L", "mm", "Letter");
        $pdf->AddFont('SourceCodePro-Bold', 'B', 'SourceCodePro-Bold.php');
        $pdf->SetFont('SourceCodePro-Bold', 'B', 13);
        // add a page
        $pdf->AddPage();
        // set the source file
        if (isset($config->id)) {
            if ( !is_null($config->template) && Storage::disk('local')->exists("templates/$config->template") ) {
                $pdf->setSourceFile(storage_path("app/templates/$config->template"));
            } else {
                $pdf->setSourceFile(storage_path("app/templates/PASAPORTE_VITAL.pdf"));
            }
        } else {
            $pdf->setSourceFile(storage_path("app/templates/PASAPORTE_VITAL.pdf"));
        }
        // import page 1
        $tplId = $pdf->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $pdf->useTemplate($tplId, 0, 0, null, null, true);
        if ( isset($config->id) ) {
            if ( $config->dark ) {
                $pdf->SetTextColor(0, 0, 0);
            } else {
                $pdf->SetTextColor(255, 255, 255);
            }
        } else {
            $pdf->SetTextColor(0, 0, 0);
        }
        /*
        $pdf->SetXY(60, 125);
        $pdf->Cell(160,10, utf8_decode($data['full_name']),0,0,'L');
        $pdf->SetXY(60, 130);
        $text = $data['document_type'].' '.$data["document"].' - N.'.$data["passport"];
        $pdf->Cell(160,10, utf8_decode($text),0,0,'L');
        */
        $pdf->SetXY(58, 125);
        $pdf->Cell(160,10, utf8_decode($name),0,0,'L');
        $pdf->SetXY(58, 130);
        $pdf->Cell(160,10, utf8_decode($document_type.' '.$document),0,0,'L');
        $pdf->SetXY(58, 135);
        $pdf->Cell(160,10, utf8_decode('N. '.$passport),0,0,'L');
        ;
        $url = "https://sim.idrd.gov.co/pasaporte-vital-en-linea/es/validar-pasaporte?passport=$passport";
        QrCode::url($url)
            ->setErrorCorrectionLevel('H')
            ->setSize(10)
            ->setOutfile(storage_path("app/templates/$passport.png"))
            ->png();
        $file = storage_path("app/templates/$passport.png");
        $pdf->Image($file, 136, 95, 25, 25);
        if (Storage::disk('local')->exists("templates/$passport.png")) {
            Storage::disk('local')->delete("templates/$passport.png");
        }
        $downloads = Passport::query()->where('i_pk_id', $passport)->first();
        if ( !isset($downloads->i_pk_id) ) {
            $downloads = PassportOld::query()->where('idPasaporte', $passport)->first();
        }
        $downloads->increment('downloads');
        return $pdf;
    }
}
