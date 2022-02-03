<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Help\Helpers;
use App\Modules\PaymentGateway\src\Models\Pago;
use App\Modules\PaymentGateway\src\Resources\StatusPseResource;
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

      public function transferBank(Request $request)
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
                                    'response_url' => env('REDIRECT_TRANSACTION_PAY_URL') . $id_transaccion->toString(),
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
                              'dev_reference' => '1',
                              'amount' => (int)$request->totalPay,
                              'vat' => 0,
                              'description' => $request->concept
                        ]
                  ]
            ]);

            $responsePse = json_decode($response->getBody()->getContents(), true);
            $pago = new Pago;
            $pago->id_parque = $request->parkSelected;
            $pago->id_servicio = $request->serviceParkSelected;
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
            $pago->id_reserva = null;
            $pago->fecha_pago = null;
            $pago->user_id_pse = 'PSE' . $request->document;
            $pago->save();

            return $this->success_message(['bank_url' => $responsePse['transaction']['bank_url']]);
      }

      public function status($codePayment)
      {
            $payment = Pago::where('codigo_pago', $codePayment)->get();
            $responsePse = null;
            if ($payment) {
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
                  $payment->first()->load('state');
            }
            return $this->success_response(StatusPseResource::collection($payment));
      }

      public function transaccions($document)
      {
            $transaccions = Pago::with('state')->where('identificacion', $document)->get();
            return $this->success_response(StatusPseResource::collection($transaccions));
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
