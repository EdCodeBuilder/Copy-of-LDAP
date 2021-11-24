<?php


namespace App\Modules\Passport\src\Controllers;


use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Exports\Excel as ExcelRaw;
use App\Modules\Passport\src\Exports\PassportExport;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\PassportConfig;
use App\Modules\Passport\src\Models\PassportGlobalView;
use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
use App\Modules\Passport\src\Models\User;
use App\Modules\Passport\src\Request\ExcelRequest;
use App\Modules\Passport\src\Request\ShowPassportRequest;
use App\Modules\Passport\src\Request\StorePassportAdminRequest;
use App\Modules\Passport\src\Request\StorePassportRequest;
use App\Modules\Passport\src\Resources\PassportResource;
use App\Modules\Passport\src\Resources\RenewalResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $count = 0;
            $text = null;
            if ($request->has('find_old')) {
                $query = PassportOldView::query();
                if ($this->query) {
                    $count =  PassportView::query()
                        ->where(function ($q) {
                            return $q->where('id', 'like', "%{$this->query}%")
                                ->orWhere('full_name', 'like', "%{$this->query}%")
                                ->orWhere('document', 'like', "%{$this->query}%");
                        })->count();
                    if ($count > 0)
                        $text = trans_choice('passport.table.matches_new', $count, ['count' => $count]);
                }
            } else {
                $query = PassportView::query();
                if ($this->query) {
                    $count = PassportOld::query()
                        ->where(function ($q) {
                            return $q->where('idPasaporte', 'like', "%{$this->query}%")
                                    ->orWhere('documento', 'like', "%{$this->query}%");
                        })->count();
                    if ($count > 0)
                        $text = trans_choice('passport.table.matches_old', $count, ['count' => $count]);
                }
            }
            return $this->success_response(
                PassportResource::collection(
                    $query
                        ->when(isset($this->query), function ($query) use ($request) {
                            if ($request->has('find_old')) {
                                $keys = PassportOld::query()
                                    ->where(function ($q) {
                                        return $q->where('idPasaporte', 'like', "%{$this->query}%")
                                            ->orWhere('documento', 'like', "%{$this->query}%");
                                    })->get()->pluck('idPasaporte')->toArray();
                                return $query->whereIn('id', $keys);
                            }
                            return $query->where(function ($q) {
                                return $q->where('id', 'like', "%{$this->query}%")
                                    ->orWhere('full_name', 'like', "%{$this->query}%")
                                    ->orWhere('document', 'like', "%{$this->query}%");
                            });
                        })
                        ->when($this->column && $this->order, function ($query) use ($request) {
                            return $request->has('find_old')
                            ? $query
                            : $query->orderBy($this->column == 'birthdate' ? 'birthday' : $this->column, $this->order);
                        })
                        ->simplePaginate($this->per_page)
                ),
                Response::HTTP_OK,
                array_merge(PassportResource::table(!$request->has('find_old')), [
                    'matches'   => $text,
                    'count'     => $count,
                    'total'     => $request->has('find_old')
                        ? PassportOld::query()
                            ->when(isset($this->query), function ($query) {
                                return $query->where('idPasaporte', 'like', "%{$this->query}%")
                                    ->orWhere('documento', 'like', "%{$this->query}%");
                            })
                            ->count()
                        : Passport::count(),
                    'order'     => $this->order,
                    'old'       => $request->has('find_old')
                ])
            );
        } catch (Exception $exception) {
            return $this->error_response(
                "El servicio no está disponible, por favor intente más tarde.",
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
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
            $user->type()->syncWithoutDetaching(6);
            $passport = new Passport();
            $file = null;
            if ($request->hasFile('file')) {
                $ext = $request->file('file')->getClientOriginalExtension();
                $now = now()->format('YmdHis');
                $file = "COMPROBANTE_{$request->get('document')}_{$now}.$ext";
                $request->file('file')->storeAs('passport-files', $file, [ 'disk' => 'local' ]);
            }
            $passport_attrs = $passport->transformRequest($request->all());
            $user->passport()->create( array_merge($passport_attrs, ['file' => $file]) );
            DB::commit();
            $pdf = $this->createCard(
                $user->passport->id,
                $user->card_name,
                $user->document_type->name,
                $user->document
            )->Output('S', 'PASAPORTE_VITAL.pdf', true);
            return $this->success_message(
                'data:application/pdf;base64,'.base64_encode($pdf),
                Response::HTTP_OK,
                Response::HTTP_OK,
                [
                    'file'  => "PASAPORTE_VITAL_{$user->passport->id}.pdf"
                ]
            );
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->error_response(
                "El servicio no está disponible, por favor intente más tarde.",
                422,
                $e->getMessage()
            );
        }
    }


    public function adminStore(StorePassportAdminRequest $request)
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
            $user->type()->syncWithoutDetaching(6);
            $passport = new Passport();
            $passport_attrs = $passport->transformRequest($request->all());
            if (isset(auth('api')->user()->sim_id)) {
                $passport_attrs['i_fk_id_usuario_supercade'] = auth('api')->user()->sim_id;
            }
            $passport_attrs['i_fk_id_superCade'] = $request->get('cade_id');
            $user->passport()->create( $passport_attrs );
            DB::commit();
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_CREATED
            );
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
     * @param PassportGlobalView $passport
     * @return JsonResponse
     */
    public function show($passport)
    {
        $old_passport = null;
        $data = PassportView::find($passport);
        if (!isset($data->id)) {
            $data = PassportOldView::find($passport);
            $old_passport = true;
        }
        return $this->success_response(
            new PassportResource($data->load('renewals')),
            Response::HTTP_OK,
            [
                'keys' => array_merge(
                    PassportResource::table()['headers'],
                    PassportResource::table()['expanded']
                ),
                'headers'  => RenewalResource::headers(),
                'old_passport' => $old_passport
            ]
        );
    }

    /**
     * @param $passport
     * @return JsonResponse
     */
    public function destroy($passport)
    {
        try {
            DB::beginTransaction();
            $data = Passport::find($passport);
            if (isset($data->file)) {
                if (!is_null($data->file) && $data->file != '' && Storage::disk('local')->exists("passport-files/$data->file")) {
                    Storage::disk('local')->delete("passport-files/$data->file");
                }
            }
            if (!isset($data->id)) {
                $data = PassportOld::findOrFail($passport);
                if (isset($data->documento)) {
                    DB::connection('mysql_passport_old')
                        ->table('pasaporte_registro')
                        ->where('documento', $data->documento)
                        ->delete();
                }
            }
            if (isset($data->user)) {
                $data->user->type()->detach();
                $data->user->social_population()->detach();
                $data->user()->delete();
            }
            $data->renewals()->delete();
            $data->delete();
            DB::commit();
            return $this->success_message(
                __('validation.handler.deleted'),
                Response::HTTP_OK,
                Response::HTTP_NO_CONTENT
            );
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
     * @param $file
     * @return JsonResponse|BinaryFileResponse
     */
    public function file($file)
    {
        if (Storage::disk('local')->exists("passport-files/$file")) {
            return response()->file( storage_path("app/passport-files/$file") );
        }
        return $this->error_response(
            __('validation.handler.resource_not_found'),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @param ShowPassportRequest $request
     * @return JsonResponse
     */
    public function query(ShowPassportRequest $request)
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
            $pdf = $this->createCard(
                $passport->id,
                $passport->card_name,
                $passport->document_type_name,
                $passport->document
            )->Output('S', 'PASAPORTE_VITAL.pdf', true);
            return $this->success_message(
                'data:application/pdf;base64,'.base64_encode($pdf),
                Response::HTTP_OK,
                Response::HTTP_OK,
                [
                    'file'  => "PASAPORTE_VITAL_$id.pdf"
                ]
            );
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
     * @param ExcelRequest $request
     * @return JsonResponse
     */
    public function excel(ExcelRequest $request)
    {
        $file = ExcelRaw::raw(new PassportExport($request), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('passport.validations.passport_vital_online')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
        );
        return $this->success_message($response);
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
        QrCode::text($passport)
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
