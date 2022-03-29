<?php

namespace App\Modules\PaymentGateway\src\Help;

use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Helpers
{
      public function getAuthToken()
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


      public function getTypeDocument($type)
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

      public function getStatus($status)
      {
            switch ($status) {
                  case 'pending':
                        return 1;
                        break;
                  case 'approved':
                        return 2;
                        break;
                  case 'cancelled':
                        return 3;
                        break;
                  case 'rejected':
                        return 4;
                        break;
                  default:
                        return 5;
                        break;
            }
      }

      public function getStatusWebHook($status)
      {
            switch ($status) {
                  case '0':
                        return 1;
                        break;
                  case '1':
                        return 2;
                        break;
                  case '2':
                        return 3;
                        break;
                  case '4':
                        return 4;
                        break;
                  default:
                        return 5;
                        break;
            }
      }

      public function sendEmailReservation($payment)
      {
            $environment = App::environment();
            $reservation =  DB::connection('mysql_pse')->table('reserva_sintetica')->where('id', '=', $payment->first()->id_reserva)->first();
            $park_endowment = DB::connection('mysql_parks')->table('parquedotacion')->where('Id', '=', $reservation->id_dotacion)->first();
            $park = DB::connection('mysql_parks')->table('parque')->where('Id', '=', $park_endowment->Id_Parque)->first();
            $horai = date('g:i A', strtotime($reservation->hora_inicio));
            $horaf = date('g:i A', strtotime($reservation->hora_fin));
            $texto = "Se realiza la reserva de la cancha con código " . $reservation->id_dotacion . " del parque " . $park->Nombre . " Dirección: "
                  . $park->Direccion . " En el horario: " . $horai . " a " . $horaf . " en la fecha: " . $reservation->fecha . " a nombre de: " . $payment->first()->nombre . " "
                  . $payment->first()->apellido . ". Identificado con documento número: " . $payment->first()->identificacion . ". Datos de contacto " . " email: " . $payment->first()->email . " teléfono: "
                  . $payment->first()->telefono . ". Por un valor de: $ " . number_format($payment->first()->total, 2, ',', '.');
            $park_email = $park->Email == '' || $park->Email == null ? 'ajmsolcontainfo@gmail.com' : $park->Email;
            Mail::send(
                  'mail.email',
                  [
                        'texto' => $texto,
                        'entorno' => $environment,
                  ],
                  function ($m) use ($reservation, $payment, $park_email, $environment) {
                        $m->from('mails@idrd.gov.co', 'Reserva Cancha código' . $reservation->id_dotacion);
                        if ($environment == 'production') {
                              $m->bcc($park_email);
                              $m->bcc('karla.ortiz@idrd.gov.co');
                              $m->bcc('carmen.vergara@idrd.gov.co');
                        } else {
                              $m->bcc('jhonnyzb1@hotmail.com');
                              $m->bcc('jhon.zabala@idrd.gov.co');
                        }
                        $m->to($payment->first()->email, $payment->first()->nombre)->subject('Reserva Cancha sintetica ' . $reservation->id_dotacion);
                  }
            );
      }

      public function emailReservationCron($payment)
      {
            $environment = App::environment();
            $reservation =  DB::connection('mysql_pse')->table('reserva_sintetica')->where('id', '=', $payment->id_reserva)->first();
            $park_endowment = DB::connection('mysql_parks')->table('parquedotacion')->where('Id', '=', $reservation->id_dotacion)->first();
            $park = DB::connection('mysql_parks')->table('parque')->where('Id', '=', $park_endowment->Id_Parque)->first();
            $horai = date('g:i A', strtotime($reservation->hora_inicio));
            $horaf = date('g:i A', strtotime($reservation->hora_fin));
            $texto = "Se realiza la reserva de la cancha con código " . $reservation->id_dotacion . " del parque " . $park->Nombre . " Dirección: "
                  . $park->Direccion . " En el horario: " . $horai . " a " . $horaf . " en la fecha: " . $reservation->fecha . " a nombre de: " . $payment->nombre . " "
                  . $payment->apellido . ". Identificado con documento número: " . $payment->identificacion . ". Datos de contacto " . " email: " . $payment->email . " teléfono: "
                  . $payment->telefono . ". Por un valor de: $ " . number_format($payment->total, 2, ',', '.');
            $park_email = $park->Email == '' || $park->Email == null ? 'ajmsolcontainfo@gmail.com' : $park->Email;
            Mail::send(
                  'mail.email',
                  [
                        'texto' => $texto,
                        'entorno' => $environment,
                  ],
                  function ($m) use ($reservation, $payment, $park_email, $environment) {
                        $m->from('mails@idrd.gov.co', 'Reserva Cancha código' . $reservation->id_dotacion);
                        if ($environment == 'production') {
                              $m->bcc($park_email);
                              $m->bcc('karla.ortiz@idrd.gov.co');
                              $m->bcc('carmen.vergara@idrd.gov.co');
                        } else {
                              $m->bcc('jhon.zabala@idrd.gov.co');
                              $m->bcc('daniel.forero@idrd.gov.co');
                        }
                        $m->to($payment->email, $payment->nombre)->subject('Reserva Cancha sintetica ' . $reservation->id_dotacion);
                  }
            );
      }

      public function statusVoucher($statusId)
      {
            switch ($statusId) {
                  case '1':
                        return ['r' => 0, 'g' => 0, 'b' => 255];
                        break;
                  case '2':
                        return ['r' => 0, 'g' => 128, 'b' => 0];
                        break;
                  default:
                        return ['r' => 255, 'g' => 0, 'b' => 0];
                        break;
            }
      }
}
