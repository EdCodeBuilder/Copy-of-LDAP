<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Exports\PaymentzExport;
use App\Modules\PaymentGateway\src\Resources\ReportPaymentResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class ReportController extends Controller
{
      /**
       * Initialise common request params
       */
      public function __construct()
      {
            parent::__construct();
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Reportes
       *
       * Muestra un listado de los pagos efectuados efectivos.
       *
       *
       * @return JsonResponse
       */
      public function index(Request $request)
      {
            $di = Carbon::create($request->dateInit)->toDateString();
            $de = Carbon::create($request->dateEnd)->toDateString();
            $paymentz = DB::connection('mysql_pse')->table('pago_pse')
                  ->leftJoin('parque', 'pago_pse.parque_id', '=', 'parque.id_parque')
                  ->leftJoin('servicio', 'pago_pse.servicio_id', '=', 'servicio.id_servicio')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->get();
            return $this->success_response(ReportPaymentResource::collection($paymentz));
      }

      public function excel($dateInit, $dateEnd)
      {
            $di = Carbon::create($dateInit)->toDateString();
            $de = Carbon::create($dateEnd)->toDateString();
            $paymentz = DB::connection('mysql_pse')->table('pago_pse')
                  ->leftJoin('parque', 'pago_pse.parque_id', '=', 'parque.id_parque')
                  ->leftJoin('servicio', 'pago_pse.servicio_id', '=', 'servicio.id_servicio')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->get();
            return Excel::download(new PaymentzExport($paymentz), 'Reporte_Pagos.xlsx');
      }
}
