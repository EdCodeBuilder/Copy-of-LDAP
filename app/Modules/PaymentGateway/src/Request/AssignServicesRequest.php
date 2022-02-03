<?php

namespace App\Modules\PaymentGateway\src\Request;

use App\Modules\PaymentGateway\src\Models\ParkPse;
use App\Modules\PaymentGateway\src\Models\ServiceOffered;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignServicesRequest extends FormRequest
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
                  'park_id'             =>  "required|numeric|exists:{$park->getConnectionName()}.{$park->getTable()},{$park->getKeyName()},deleted_at,NULL",
                  'service_id'          =>  "required|numeric|exists:{$service->getConnectionName()}.{$service->getTable()},{$service->getKeyName()},deleted_at,NULL",
            ];
      }
}
