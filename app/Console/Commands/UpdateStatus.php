<?php

namespace App\Console\Commands;

use App\Modules\PaymentGateway\src\Help\Helpers;
use App\Modules\PaymentGateway\src\Models\Pago;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateStatus extends Command
{
      /**
       * The name and signature of the console command.
       *
       * @var string
       */
      protected $signature = 'update:statuspse';

      /**
       * The console command description.
       *
       * @var string
       */
      protected $description = 'update status payment in pse';

      /**
       * Create a new command instance.
       *
       * @return void
       */
      public function __construct()
      {
            parent::__construct();
      }

      /**
       * Execute the console command.
       *
       * @return mixed
       */
      public function handle()
      {
            //
            $payments = Pago::where('estado_id', 1)->get();
            foreach ($payments as $payment) {
                  $responsePse = null;
                  $http = new Client();
                  $help = new Helpers();
                  $response = $http->get(env('URL_BASE_PAYMENTEZ') . '/pse/order/' . $payment->id_transaccion_pse . '/', [
                        'headers' => [
                              "auth-token" =>  $help->getAuthToken(),
                              "Content-Type" => "application/json",
                        ],
                  ]);
                  $responsePse =  json_decode($response->getBody()->getContents(), true);
                  $payment->estado_id = $help->getStatus($responsePse['transaction']['status']);
                  $payment->estado_banco = $responsePse['transaction']['status_bank'];
                  $payment->fecha_pago =  $responsePse['transaction']['paid_date'];
                  $payment->save();
                  $payment->load('state');
                  try {
                        if ($payment->id_reserva) {
                              if ($payment->state->id == 2) {
                                    $help->emailReservationCron($payment);
                              }
                        }
                  } catch (\Exception $e) {
                        $this->info($e->getMessage());
                  }
            }
            $this->info('finalizo');
            echo "se revisaron " .$payments->count()." pagos en espera\n";
            $paymentsAux = Pago::where('estado_id', 1)->get();
            echo "Qudan por revisar " .$paymentsAux->count()." pagos en espera\n";

      }
}
