<?php


namespace App\Modules\Passport\src\Controllers;


use App\Helpers\FPDF;
use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
use App\Modules\Passport\src\Models\Renew;
use App\Modules\Passport\src\Request\PrintRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class PrintController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param PrintRequest $request
     * @return JsonResponse|string
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     */
    public function index(PrintRequest $request)
    {
        $passports = $request->get('passports');
        $not_found = [];
        $pdf = new FPDF("L", "mm", [87,55]);
        $pdf->SetFont('Helvetica', 'BI', 9.6);
        $pdf->AddPage('L');
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(true, 5);
        $pdf->setSourceFile(storage_path("app/templates/PASAPORTE_VITAL_FISICO.pdf"));
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId, 0, 0, null, null, true);
        foreach ($passports as $index => $passport) {
            $passports_new = Passport::find($passport['passport']);
            $passports_old = PassportOld::find($passport['passport']);
            if (!isset($passports_new->id) && !isset($passports_old->id)) {
                $not_found[] = $passport['passport'];
            }
            if (isset($passports_new->id)) {
                $data = PassportView::find($passport['passport']);
                $pdf->SetXY(5, 37);
                $pdf->Cell(75,5, utf8_decode($data->card_name),0,0,'L');
                $pdf->SetXY(5, 41);
                $document = "$data->document_type_name. $data->document  -  Nº $data->id";
                $pdf->Cell(75,5, utf8_decode($document),0,0,'L');
                if ($index + 1 < count($passports)) {
                    $pdf->AddPage('L');
                }
                if ($passports_new->printed) {
                    $renew = new Renew();
                    $renew->i_fk_id_usuario             =  $passports_new->user_id;
                    $renew->i_fk_id_pasaporte           =  $passports_new->id;
                    $renew->i_fk_id_usuario_supercade   =  isset(auth('api')->user()->sim_id) ? auth('api')->user()->sim_id : Passport::SUPER_USER;
                    $renew->i_fk_supercade              =  $passport['cade_id'];
                    $renew->vc_denuncio                 =  toUpper($passport['denounce']);
                    $renew->save();
                } else {
                    $passports_new->printed = true;
                    $passports_new->save();
                }
            }
            if (isset($passports_old->id)) {
                $data = PassportOldView::find($passport['passport']);
                $pdf->SetXY(5, 37);
                $pdf->Cell(75,5, utf8_decode($data->card_name),0,0,'L');
                $pdf->SetXY(5, 41);
                $document = "$data->document_type_name. $data->document  -  Nº $data->id";
                $pdf->Cell(75,5, utf8_decode($document),0,0,'L');
                if ($index + 1 < count($passports)) {
                    $pdf->AddPage('L');
                }
                $renew = new Renew();
                $renew->i_fk_id_usuario             =  $passports_old->user_id;
                $renew->i_fk_id_pasaporte           =  $passports_old->id;
                $renew->i_fk_id_usuario_supercade   =  isset(auth('api')->user()->sim_id) ? auth('api')->user()->sim_id : Passport::SUPER_USER;
                $renew->i_fk_supercade              =  (int) $passport['cade_id'];
                $renew->vc_denuncio                 =  toUpper($passport['denounce']);
                $renew->save();
                $passports_old->increment('Renovacion');
            }
        }
        if (count($not_found) == count($passports)) {
            return $this->error_response(
                __('validation.handler.resource_not_found')
            );
        }
        return $this->success_message(
            'data:application/pdf;base64,'.base64_encode($pdf->Output('S', 'PASAPORTES.pdf', true)),
            Response::HTTP_OK,
            Response::HTTP_OK,
            [
                'not_found' => count($not_found) > 0
                    ? trans_choice('passport.validations.not_found', count($not_found), ['passports' => implode(', ', $not_found)])
                    : null
            ]
        );
    }
}
