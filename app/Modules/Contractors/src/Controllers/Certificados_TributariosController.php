<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Contractors\src\Request\ConsultaRequest;
use App\Modules\Contractors\src\Request\ValidacionUsuarioRequest;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Jobs\VerificationCodeTributario;
use App\Modules\Contractors\src\Request\ValidacionRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Helpers\FPDF;

class Certificados_TributariosController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function index(ValidacionUsuarioRequest $request){
        try {
            $contractor = Contractor::query()
            ->where('document', $request->get('document'))
            ->where('birthdate', $request->get('birthdate'))
            ->firstOrFail();
            $certification = new Certification();
            $certification->document = $contractor->document;
            $certification->name = $contractor->full_name;
            $certification->type = "TRB";
            $certification->save();
            $this->dispatch(new VerificationCodeTributario($contractor, $certification));
            $email=mask_email($contractor->email);
            return $this->success_message("Hemos enviado un código de verificación al correo $email.");

        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    'No se encuentra el usuario con los parámetros establecidos.',
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

    public function validarUsuario(ValidacionRequest $request){
        try {
            $data=Certification::query()->where("document", $request->get("document"))->where("code", $request->get("code"))->firstOrFail();

            $pdf = $this->conexionSeven($request);
            return $this->success_message($pdf);

        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    'El código no coincide con el enviado. Por favor verifique nuevamente',
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

    public function conexionSeven(ValidacionRequest $request){
        $http=new Client();
        $response=$http->post("http://66.70.171.168/api/contractors-portal/certificado-tributario/oracle", [
            "json"=>$request->all(), "headers"=>[
                'Accept'    => 'application/json',
                'Content-type' => 'application/json'
            ]
            ]);
            $data=json_decode($response->getBody()->getContents(), true);
            return $data;
    }

    public function consultaSV(ConsultaRequest $request){
        $data=DB::connection("oracle")->select("SELECT F.PVD_CODI, FAC_ANOP,P.PVR_NOCO,LIQ_NOMB,(SELECT SUM(A1.DFA_VALO)  FROM PO_DFACT A1 WHERE A1.PVD_CODI=F.PVD_CODI and A1.DFA_ANOP=F.FAC_ANOP) VAL_BRUT
        ,SUM(LIQ_BASE) VAL_BASE, case WHEN liq_nomb='TOTAL' THEN  0 ELSE SUM (LIQ_VALO)*-1 END VAL_RETE  FROM PO_FACTU F, PO_DVFAC D, PO_PVDOR P
        WHERE  F.PVD_CODI={$request->get('document')}
        AND F.FAC_ANOP={$request->get('year')}
        AND F.FAC_CONT= D.FAC_CONT(+)
        AND LIQ_CODI IN ('RTEFTEVARI','RETE','TOTAL')
        and liq_valo <>0
        and f.fac_esta ='A'
        AND P.EMP_CODI= F.EMP_CODI
        AND P.PVD_CODI = F.PVD_CODI
        GROUP BY F.PVD_CODI, FAC_ANOP,P.PVR_NOCO,LIQ_NOMB");
        return $this->success_message($data);
    }

    public function createPDF($data){
        $collection=collect($data[0]??[]);
        $pdf=new FPDF("L", "mm", "Letter");
        $pdf->AddPage();
        $pdf->setSourceFile(storage_path("app/templates/Certificado_Ingresos_Retenciones.pdf"));
        $template=$pdf->importPage(1);
        $pdf->useTemplate($template, 0, 0, null, null, true);
        $pdf->SetFont("Helvetica");
        $pdf->SetFontSize(10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(0, 0, utf8_decode($collection->get("fac_anop")));
        $pdf->Text(2, 2, utf8_decode($collection->get("pvr_noco")));
        $pdf->Text(3, 3, utf8_decode($collection->get("pvd_codi")));
        $pdf->Text(4, 4, utf8_decode($collection->get("liq_nomb")));
        $pdf->Text(5, 5, utf8_decode($collection->get("val_brut")));
        $pdf->Text(6, 6, utf8_decode($collection->get("val_base")));
        $pdf->Text(7, 7, utf8_decode($collection->get("val_rete")));

        return $pdf->Output("I", "Ingresos y Retenciones.pdf");
    }
}

