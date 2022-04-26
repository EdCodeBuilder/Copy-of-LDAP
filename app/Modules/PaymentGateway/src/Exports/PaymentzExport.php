<?php

namespace App\Modules\PaymentGateway\src\Exports;

use App\Modules\PaymentGateway\src\Models\Pago;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentzExport implements FromCollection, WithHeadings, WithMapping
{
      use Exportable;

      protected $paymentz;

      public function __construct($paymentz = null)
      {
            $this->paymentz = $paymentz;
      }

      /**
       * @return \Illuminate\Support\Collection
       */
      public function collection()
      {
            return $this->paymentz;
      }

      public function map($paymentz): array
      {
            return [
                  $paymentz->id,
                  $paymentz->created_at,
                  $paymentz->total,
                  $paymentz->codigo_parque . $paymentz->codigo_servicio,
                  $paymentz->nombre_parque,
                  $paymentz->servicio_nombre,
                  $paymentz->identificacion,
                  $paymentz->email,
                  $paymentz->nombre,
                  $paymentz->apellido,
                  $paymentz->telefono,
                  $paymentz->concepto,
                  $paymentz->medio_pago,
                  $paymentz->id_transaccion_pse,
                  $paymentz->codigo_pago,
            ];
      }

      public function headings(): array
      {
            return ['ID', 'FECHA', 'TOTAL', 'CODIGO', 'PARQUE', 'SERVICIO',  'IDENTIFICACION', 'EMAIL', 'NOMBRE', 'APELLIDO', 'TELEFONO', 'CONCEPTO', 'METODO PAGO', 'TRANSACCION ID PSE', 'CODIGO PAGO'];
      }
}
