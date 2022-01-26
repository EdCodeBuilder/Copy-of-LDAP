<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Models\Pago;
use App\Modules\PaymentGateway\src\Resources\TransaccionsPseResource;
use GuzzleHttp\Client;
use DateTime;
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
            $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/banks/PSE/', [
                  'headers' => [
                        "auth-token" => $this->getAuthToken(),
                        "Content-Type" => "application/json",
                  ],
            ]);
            return json_decode($response->getBody()->getContents(), true);
      }



      public function transferBank(Request $request)
      {
            $id_transaccion = Uuid::uuid1();
            $http = new Client();
            $response = $http->post(env('URL_BASE_PAYMENTEZ') . '/order/', [
                  'headers' => [
                        "auth-token" => $this->getAuthToken(),
                        "Content-Type" => "application/json",
                  ],
                  'json' => [
                        'carrier' => [
                              'id' => 'PSE',
                              'extra_params' => [
                                    'bank_code' => $request->BankTypeSelected,
                                    'response_url' => 'http://localhost:43513/pasarela-pagos/' . $id_transaccion->toString(),
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
                              'dev_reference' => 'reject',
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
            $pago->tipo_identificacion = $this->getTypeDocument($request->documentTypeSelected);
            $pago->codigo_pago = $id_transaccion;
            $pago->id_transaccion_pse = $responsePse['transaction']['id'];
            $pago->email = toUpper($request->email);
            $pago->nombre = toUpper($request->name);
            $pago->apellido = toUpper($request->lastName);
            $pago->telefono = $request->phone;
            $pago->estado = $responsePse['transaction']['status'];
            $pago->estado_banco = $responsePse['transaction']['status_bank'];
            $pago->concepto = toUpper($request->concept);
            $pago->total = $request->totalPay;
            $pago->iva = 0;
            $pago->permiso = $request->permitNumber;
            $pago->tipo_permiso = $request->permitTypeSelected;
            $pago->save();

            return $this->success_message(['bank_url' => $responsePse['transaction']['bank_url']]);
      }



      public function status($codePayment)
      {
            $pago = Pago::where('codigo_pago', $codePayment)->first();
            $responsePse = null;
            if ($pago) {
                  $http = new Client();
                  $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/pse/order/' . $pago->id_transaccion_pse . '/', [
                        'headers' => [
                              "auth-token" => $this->getAuthToken(),
                              "Content-Type" => "application/json",
                        ],
                  ]);
                  $responsePse =  json_decode($response->getBody()->getContents(), true);
                  $pago->estado = $responsePse['transaction']['status'];
                  $pago->estado_banco = $responsePse['transaction']['status_bank'];
                  $pago->save();
            }
            return $this->success_message(['pago' => $pago, 'responsePse' => $responsePse]);
      }


      public function transaccions($document)
      {
            $transaccions = Pago::where('identificacion', $document)->get();
            return $this->success_response(TransaccionsPseResource::collection($transaccions));
      }

      public function webHook(Request $request)
      {

            $transaccion = Pago::where('id_transaccion_pse', $request->transaction['id'])->first();
            $transaction_id = $transaccion->id_transaccion_pse;
            $app_code = env('API_LOGIN_DEV');
            $user_id = 'PSE' . $transaccion->identificacion;
            $app_key = env('API_KEY_DEV');
            $for_md5 = $transaction_id . '_' . $app_code . '_' . $user_id . '_' . $app_key;
            $stoken = md5($for_md5);
            if ($request->transaction['stoken'] === $stoken) {
                  $transaccion->estado = $request->transaction['status'] === '1' ? 'approved' : 'failure';
                  $transaccion->save();
            }
      }



      private function getAuthToken()
      {
            $server_application_code = env('API_LOGIN_DEV');
            $server_app_key = env('API_KEY_DEV');
            $date = new DateTime();
            $unix_timestamp = $date->getTimestamp();
            $uniq_token_string = $server_app_key . $unix_timestamp;
            $uniq_token_hash = hash('sha256', $uniq_token_string);
            $auth_token = base64_encode($server_application_code . ";" . $unix_timestamp . ";" . $uniq_token_hash);
            return $auth_token;
      }


      private function getTypeDocument($type)
      {
            switch ($type) {
                  case 'CC':
                        return 1;
                        break;
                  case 'TI':
                        return 2;
                        break;
                  case 'NIT':
                        return 7;
                        break;
                  case 'CE':
                        return 4;
                        break;
                  case 'PP':
                        return 6;
                        break;
                  case 'RC':
                        return 3;
                        break;
                  default:
                        return 14;
                        break;
            }
      }
}
