<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use App\Helpers\FPDF;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Help\Helpers;
use App\Modules\PaymentGateway\src\Models\Pago;
use App\Modules\PaymentGateway\src\Request\CreateTransferBankRequest;
use App\Modules\PaymentGateway\src\Resources\StatusPseResource;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class PseController extends Controller
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
       * Parques
       *
       * Muestra un listado del recurso.
       *
       *
       * @return JsonResponse
       */
      public function banks()
      {
            $http = new Client();
            $help = new Helpers();
            $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/banks/PSE/', [
                  'headers' => [
                        "auth-token" => $help->getAuthToken(),
                        "Content-Type" => "application/json",
                  ],
            ]);
            return json_decode($response->getBody()->getContents(), true);
      }

      public function transferBank(CreateTransferBankRequest $request)
      {
            $http = new Client();
            $help = new Helpers();
            $id_transaccion = Uuid::uuid1();
            $response = $http->post(env('URL_BASE_PAYMENTEZ') . '/order/', [
                  'headers' => [
                        "auth-token" => $help->getAuthToken(),
                        "Content-Type" => "application/json",
                  ],
                  'json' => [
                        'carrier' => [
                              'id' => 'PSE',
                              'extra_params' => [
                                    'bank_code' => $request->BankTypeSelected,
                                    'response_url' => $request->has('redirect_url')
                                        ? $request->get('redirect_url').$id_transaccion->toString()
                                        : env('REDIRECT_TRANSACTION_PAY_URL') . $id_transaccion->toString(),
                                    'user' => [
                                          'name' => $request->name,
                                          'fiscal_number' => (int)$request->document,
                                          'type' => $request->typePersonSelected,
                                          'type_fis_number' => $request->documentTypeSelected,
                                          'ip_address' => $request->ip_address
                                    ]
                              ]
                        ],
                        'user' => [
                              'id' => 'PSE' . $request->document,
                              'email' => $request->email
                        ],
                        'order' => [
                              'country' => 'COL',
                              'currency' => 'COP',
                              'dev_reference' => $id_transaccion->toString(),
                              'amount' => (int)$request->totalPay,
                              'vat' => 0,
                              'description' => $request->concept
                        ]
                  ]
            ]);

            $responsePse = json_decode($response->getBody()->getContents(), true);
            $pago = new Pago;
            $pago->parque_id = $request->parkSelected;
            $pago->servicio_id = $request->serviceParkSelected;
            $pago->identificacion = $request->document;
            $pago->tipo_identificacion =  $help->getTypeDocument($request->documentTypeSelected);
            $pago->codigo_pago = $id_transaccion;
            $pago->id_transaccion_pse = $responsePse['transaction']['id'];
            $pago->email = toUpper($request->email);
            $pago->nombre = toUpper($request->name);
            $pago->apellido = toUpper($request->lastName);
            $pago->telefono = $request->phone;
            $pago->estado_id = $help->getStatus($responsePse['transaction']['status']);
            $pago->estado_banco = $responsePse['transaction']['status_bank'];
            $pago->concepto = toUpper($request->concept);
            $pago->moneda = $responsePse['transaction']['currency'];
            $pago->total = $request->totalPay;
            $pago->iva = 0;
            $pago->permiso = $request->permitNumber;
            $pago->tipo_permiso = $request->permitTypeSelected;
            $pago->id_reserva = $request->reservationId;
            $pago->fecha_pago = null;
            $pago->user_id_pse = 'PSE' . $request->document;
            $pago->medio_id = 1;
            $pago->save();

            return $this->success_message(['bank_url' => $responsePse['transaction']['bank_url']]);
      }

      public function status($codePayment)
      {
            $payment = Pago::where('codigo_pago', $codePayment)->get();
            if ($payment->first()->estado_id != 2) {
                  $responsePse = null;
                  $http = new Client();
                  $help = new Helpers();
                  $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/pse/order/' . $payment->first()->id_transaccion_pse . '/', [
                        'headers' => [
                              "auth-token" =>  $help->getAuthToken(),
                              "Content-Type" => "application/json",
                        ],
                  ]);
                  $responsePse =  json_decode($response->getBody()->getContents(), true);
                  $payment->first()->estado_id = $help->getStatus($responsePse['transaction']['status']);
                  $payment->first()->estado_banco = $responsePse['transaction']['status_bank'];
                  $payment->first()->fecha_pago =  $responsePse['transaction']['paid_date'];
                  $payment->first()->save();
                  $payment->first()->load('state', 'method');
                  try {
                        if ($payment->first()->id_reserva) {
                              if ($payment->first()->state->id == 2) {
                                    $help->sendEmailReservation($payment);
                              }
                        }
                        return $this->success_response(StatusPseResource::collection($payment));
                  } catch (\Exception $e) {
                        return $this->success_response(StatusPseResource::collection($payment));
                  }
            }
            $payment->first()->load('state', 'method');
            return $this->success_response(StatusPseResource::collection($payment));

            // logica si utilizamos el webhook
            // $payment = Pago::where('codigo_pago', $codePayment)->get();
            // $payment->first()->load('state', 'method');
            // if ($payment->first()->id_reserva) {
            //       if ($payment->first()->state->id == 2) {
            //             $help = new Helpers();
            //             $help->sendEmailReservation($payment);
            //       }
            // }
            // return $this->success_response(StatusPseResource::collection($payment));

      }


      public function statusRefresh($codePayment)
      {
            // refresh estado transaccion
            $payment = Pago::where('codigo_pago', $codePayment)->get();
            if ($payment->first()->estado_id != 2) {
                  $responsePse = null;
                  $http = new Client();
                  $help = new Helpers();
                  $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/pse/order/' . $payment->first()->id_transaccion_pse . '/', [
                        'headers' => [
                              "auth-token" =>  $help->getAuthToken(),
                              "Content-Type" => "application/json",
                        ],
                  ]);
                  $responsePse =  json_decode($response->getBody()->getContents(), true);
                  $payment->first()->estado_id = $help->getStatus($responsePse['transaction']['status']);
                  $payment->first()->estado_banco = $responsePse['transaction']['status_bank'];
                  $payment->first()->fecha_pago =  $responsePse['transaction']['paid_date'];
                  $payment->first()->save();
                  $payment->first()->load('park', 'service', 'state', 'method');
                  try {
                        if ($payment->first()->id_reserva) {
                              if ($payment->first()->state->id == 2) {
                                    $help->sendEmailReservation($payment);
                              }
                        }
                        return $this->success_response(StatusPseResource::collection($payment));
                  } catch (\Exception $e) {
                        return $this->success_response(StatusPseResource::collection($payment));
                  }
            }
            $payment->first()->load('park', 'service', 'state', 'method');
            return $this->success_response(StatusPseResource::collection($payment));
      }


      public function transaccions(Request $request)
      {
            $dt = Carbon::create($request->date)->toDateString();
            $transaccions = Pago::with('park', 'service', 'state', 'method')->where('identificacion', $request->document)->whereDate('created_at', $dt)->get();
            return $this->success_response(StatusPseResource::collection($transaccions));
      }

      public function voucher(Request $request)
      {
            $help = new Helpers();
            $transaccion =  Pago::with('park', 'service',  'state', 'method')->where('codigo_pago', $request->codePayment)->first();
            $pdf = new FPDF('L', 'mm', 'Letter');
            $pdf->AddPage();
            $pdf->setSourceFile(storage_path("app/templates/COMPROBANTE_PAGO.pdf"));
            $tplId = $pdf->importPage(1);
            $pdf->useTemplate($tplId, 0, 0, null, null, true);
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(146, 28);
            $pdf->Cell(40, 40, $transaccion->created_at);
            $pdf->SetFont('Courier', 'U', 30);
            $pdf->SetTextColor(
                  $help->statusVoucher($transaccion->estado_id)['r'],
                  $help->statusVoucher($transaccion->estado_id)['g'],
                  $help->statusVoucher($transaccion->estado_id)['b']
            );
            $pdf->SetXY(135, 70);
            $pdf->Cell(40, 40, $transaccion->state->descripcion);
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(31, 93);
            $pdf->Cell(40, 40, $transaccion->identificacion);
            $pdf->SetXY(135, 93);
            $pdf->Cell(40, 40, $transaccion->id_transaccion_pse);
            $pdf->SetXY(31, 109);
            $pdf->Cell(40, 40, $transaccion->total);
            $pdf->SetXY(31, 125);
            $pdf->Cell(40, 40, $transaccion->email);
            $pdf->SetXY(135, 125);
            $pdf->Cell(40, 40, $transaccion->moneda);
            $pdf->SetXY(31, 142);
            $pdf->Cell(40, 40, $transaccion->nombre . ' ' . $transaccion->apellido);
            $pdf->SetXY(135, 142);
            $pdf->Cell(40, 40, $transaccion->telefono);
            $pdf->SetXY(31, 159);
            $pdf->Cell(40, 40, $transaccion->codigo_pago);
            $pdf->SetXY(135, 159);
            $pdf->Cell(40, 40, $transaccion->user_id_pse);
            $pdf->SetXY(31, 193);
            $pdf->MultiCell(70, 3,$transaccion->concepto, 0, 'L');
            $pdf->SetXY(135, 175);
            $pdf->Cell(40, 40, $transaccion->iva);
            $pdf->SetXY(31, 192);
            $pdf->Cell(40, 40, $transaccion->method->Nombre);
            $pdf->SetXY(135, 192);
            $pdf->Cell(40, 40, $transaccion->state->descripcion);
            $pdf->SetXY(31, 208);
            $pdf->Cell(40, 40, $transaccion->service ? $transaccion->service->servicio_nombre : '-');
            $pdf->SetXY(135, 227);
            $pdf->MultiCell(70, 3, $transaccion->park ? $transaccion->park->nombre_parque : '-', 0, 'L');
            $pdf->Output('D', 'Comprobante_' . $transaccion->codigo_pago . '.pdf');
      }

      public function webHook(Request $request)
      {
            $help = new Helpers();
            $transaccion = Pago::where('id_transaccion_pse', $request->transaction['id'])->first();
            $transaction_id = $transaccion->id_transaccion_pse;
            $app_code = env('API_LOGIN_DEV');
            $user_id = $transaccion->user_id_pse;
            $app_key = env('API_KEY_DEV');
            $for_md5 = $transaction_id . '_' . $app_code . '_' . $user_id . '_' . $app_key;
            $stoken = md5($for_md5);
            if ($request->transaction['stoken'] === $stoken) {
                  $transaccion->estado_id = $help->getStatusWebHook($request->transaction['status']);
                  $transaccion->save();
                  return (new \Illuminate\Http\Response)->setStatusCode(200);
            }
            return (new \Illuminate\Http\Response)->setStatusCode(203);
      }
}
