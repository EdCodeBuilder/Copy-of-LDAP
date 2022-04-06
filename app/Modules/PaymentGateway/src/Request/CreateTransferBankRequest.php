<?php

namespace App\Modules\PaymentGateway\src\Request;

use App\Modules\PaymentGateway\src\Models\ParkPse;
use App\Modules\PaymentGateway\src\Models\Reservation;
use App\Modules\PaymentGateway\src\Models\ServiceOffered;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTransferBankRequest extends FormRequest
{
      /**
       * Determine if the user is authorized to make this request.
       *
       * @return bool
       */
      public function authorize()
      {
            return true;
      }

      /**
       * Get the validation rules that apply to the request.
       *
       * @return array
       */
      public function rules()
      {
            $park = new ParkPse();
            $service = new ServiceOffered();
            return [
                  'reservationId'          =>  "numeric|nullable",
                  'permitTypeSelected'     =>  "required|string",
                  'permitNumber'           =>  "required|numeric",
                  'name'                   =>  "required|string",
                  'lastName'               =>  "required|string",
                  'phone'                  =>  "required|string",
                  'email'                  =>  "required|string",
                  'documentTypeSelected'   =>  "required|string",
                  'document'               =>  "required|numeric",
                  'parkSelected'           =>  "required|numeric|exists:{$park->getConnectionName()}.{$park->getTable()},{$park->getKeyName()},deleted_at,NULL",
                  'serviceParkSelected'    =>  "required|numeric|exists:{$service->getConnectionName()}.{$service->getTable()},{$service->getKeyName()},deleted_at,NULL",
                  'concept'                =>  "required|string",
                  'totalPay'               =>  "required|numeric",
                  'concept'                =>  "required|string",
                  'typePersonSelected'     =>  "required|string",
                  'BankTypeSelected'       =>  "required|string",
                  'ip_address'             =>  "required|string",
            ];
      }
}
