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
use App\Modules\PaymentGateway\src\Models\Pago;

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
            $di = Carbon::createFromFormat('Y-m-d', $request->dateInit)->startOfDay();
            $de = Carbon::createFromFormat('Y-m-d', $request->dateEnd)->endOfDay();
            $paymentz = DB::connection('mysql_pse')->table('pago_pse')
                  ->leftJoin('parque', 'pago_pse.parque_id', '=', 'parque.id_parque')
                  ->leftJoin('servicio', 'pago_pse.servicio_id', '=', 'servicio.id_servicio')
                  ->leftJoin('medio_pago', 'pago_pse.medio_id', '=', 'medio_pago.id')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->select('pago_pse.*', 'servicio.id_servicio', 'servicio.servicio_nombre', 'servicio.codigo_servicio', 'parque.nombre_parque', 'parque.codigo_parque', 'medio_pago.Nombre as medio_pago')
                  ->get();
            return $this->success_response(ReportPaymentResource::collection($paymentz));
      }

      public function excel($dateInit, $dateEnd)
      {
            $di = Carbon::createFromFormat('Y-m-d', $dateInit)->startOfDay();
            $de = Carbon::createFromFormat('Y-m-d', $dateEnd)->endOfDay();
            $paymentz = DB::connection('mysql_pse')->table('pago_pse')
                  ->leftJoin('parque', 'pago_pse.parque_id', '=', 'parque.id_parque')
                  ->leftJoin('servicio', 'pago_pse.servicio_id', '=', 'servicio.id_servicio')
                  ->leftJoin('medio_pago', 'pago_pse.medio_id', '=', 'medio_pago.id')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->select('pago_pse.*', 'servicio.id_servicio', 'servicio.servicio_nombre', 'servicio.codigo_servicio', 'parque.nombre_parque', 'parque.codigo_parque', 'medio_pago.Nombre as medio_pago')
                  ->get();
            return Excel::download(new PaymentzExport($paymentz), 'Reporte_Pagos.xlsx');
      }

      public function json(Request $request)
      {
            $tabla = Pago::with('service','park','state')->where('estado_id', 2)->get();
            return response()->json($tabla , 200);
      }
}
