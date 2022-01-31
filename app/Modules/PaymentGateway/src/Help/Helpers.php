<?php

namespace App\Modules\PaymentGateway\src\Help;

use DateTime;

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
}
