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
            $di = Carbon::create($request->dateInit)->toDateString();
            $de = Carbon::create($request->dateEnd)->toDateString();
            $paymentz = DB::connection('mysql_pse')->table('pago_pse')
                  ->leftJoin('parque', 'pago_pse.parque_id', '=', 'parque.id_parque')
                  ->leftJoin('servicio', 'pago_pse.servicio_id', '=', 'servicio.id_servicio')
                  ->leftJoin('medio_pago', 'pago_pse.medio_id', '=', 'medio_pago.id')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->select('pago_pse.*', 'parque.nombre_parque', 'parque.codigo_parque', 'servicio.*', 'medio_pago.Nombre as medio_pago')
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
                  ->leftJoin('medio_pago', 'pago_pse.medio_id', '=', 'medio_pago.id')
                  ->where('estado_id', 2)
                  ->whereBetween('pago_pse.created_at', [$di, $de])
                  ->select('pago_pse.*', 'parque.nombre_parque', 'parque.codigo_parque', 'servicio.*', 'medio_pago.Nombre as medio_pago')
                  ->get();
            return Excel::download(new PaymentzExport($paymentz), 'Reporte_Pagos.xlsx');
      }

      public function json(Request $request)
      {
            $tabla = Pago::with('service','park','state')->get();
            return response()->json($tabla , 200);
      }
}
