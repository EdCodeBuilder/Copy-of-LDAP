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
            Certification::query()
                ->where("document", $request->get("document"))
                ->where("code", $request->get("code"))
                ->firstOrFail();
            return $this->conexionSeven($request);
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
            $pdf=$this->createPDF($data["data"]);
            return $this->success_message([
               "file_name"=>"Ingresos_Retenciones.pdf",
               "file"=>"data:application/pdf;base64,".base64_encode($pdf)
            ]);
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
        $collection=collect($data??[]);
        $pdf=new FPDF("L", "mm", "Letter");
        $pdf->AddPage();
        $pdf->setSourceFile(storage_path("app/templates/Certificado_Ingresos_Retenciones.pdf"));
        $template=$pdf->importPage(1);
        $pdf->useTemplate($template, 0, 0, null, null, true);
        $pdf->SetFont("Helvetica");
        $pdf->SetFontSize(8);
        $pdf->SetTextColor(0,0,1);
        $name=null;
        $document=null;
        $year=null;
        $i=0;
        $retef=[];

        $collection->map(function($collect) use(&$name, &$document, &$year, &$i, &$pdf, &$retef){
            if($i==0){
                $name=$collect["pvr_noco"]??"";
                $document=$collect["pvd_codi"]??"";
                $year=$collect["fac_anop"]??"";
            }
            $concepto=$collect["liq_nomb"]??"";
            $valbruto="$ ".number_format($collect["val_brut"]??0, 2, ".", ",");
            $valbase="$ ".number_format($collect["val_base"]??0, 2, ".", ",");
            $valrete="$ ".number_format($collect["val_rete"]??0, 2, ".", ",");
            $retef[toLower($concepto)]=intval($collect["val_rete"]??0);
            if(toLower($concepto)=="total"){
                $pdf->Text(90, 146, utf8_decode($valbruto));
            }else{
                $pdf->Text(34, 126+($i*4), utf8_decode($collect["liq_nomb"]??""));
                $pdf->Text(90, 126+($i*4), utf8_decode($valbruto));
                $pdf->Text(140, 126+($i*4), utf8_decode($valbase));
                $pdf->Text(175, 126+($i*4), utf8_decode($valrete));
                $i++;
            }
        });
        $ret=$retef["rte fuente"]??0;
        $tot=$retef["total"]??0;
        $pdf->Text(175, 146, utf8_decode(
            $ret>1
            ? "$ ".number_format($ret, 2, ".", ",")
            :"$ ".number_format($tot, 2, ".", ",")
            )
        );
        $pdf->Text(70, 77, utf8_decode($year));
        $pdf->Text(70, 98, utf8_decode($name));
        $pdf->Text(70, 106, utf8_decode($document));
        $pdf->Text(70, 186, utf8_decode(now()->format("Y-m-d H:i:s")));

        return $pdf->Output("S", "Ingresos_Retenciones.pdf", true);
    }
}

