<?php

namespace App\Modules\PaymentGateway\src\Request;

use App\Modules\PaymentGateway\src\Models\ParkPse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParkPaymentCreateUpdateRequest extends FormRequest
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
            return [
                  'name_park'             =>  'required|string',
                  'code_park'             =>  "required|numeric|unique:{$park->getConnectionName()}.{$park->getTable()},codigo_parque,{$this->route('id')},id_parque,deleted_at,NULL",
                  'contac_park'           =>  'required|string',
                  'phones_park'           =>  'required|string',
                  'address_park'          =>  'required|string',
                  'email_park'            =>  'required|email',

            ];
      }
}
