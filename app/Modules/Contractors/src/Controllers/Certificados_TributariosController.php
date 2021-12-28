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

        } catch (Exception $exception) {
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
    public function consultaSV(ConsultaRequest $request){
        $data=DB::connection("oracle")=>raw("SELECT F.PVD_CODI, FAC_ANOP,P.PVR_NOCO,LIQ_NOMB,(SELECT SUM(A1.DFA_VALO)  FROM PO_DFACT A1 WHERE A1.PVD_CODI=F.PVD_CODI and A1.DFA_ANOP=F.FAC_ANOP) VAL_BRUT, SUM (LIQ_VALO)*-1 VAL_RETE, SUM(LIQ_BASE) VAL_BASE FROM PO_FACTU F, PO_DVFAC D, PO_PVDOR P
        WHERE  F.PVD_CODI={$request=>get('document')}
        AND F.FAC_ANOP={$request=>get('year')}
        AND F.FAC_CONT= D.FAC_CONT
        AND LIQ_CODI IN ('RTEFTEVARI','RETE')
        and liq_valo <>0
        and f.fac_esta ='A'
        AND P.EMP_CODI= F.EMP_CODI
        AND P.PVD_CODI = F.PVD_CODI
        GROUP BY F.PVD_CODI, FAC_ANOP,P.PVR_NOCO,LIQ_NOMB");
        return $data;
    }
}
