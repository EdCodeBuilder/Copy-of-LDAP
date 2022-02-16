<?php

namespace App\Modules\PaymentGateway\src\Request;

use App\Modules\PaymentGateway\src\Models\ServiceOffered;
use Illuminate\Foundation\Http\FormRequest;

class ServiceOfferedCreateUpdateRequest extends FormRequest
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
            $service = new ServiceOffered();
            return [
                  'name_service'    =>  'required|string',
                  'code_service'    =>  "required|numeric|unique:{$service->getConnectionName()}.{$service->getTable()},codigo_servicio,{$this->route('id')},id_servicio,deleted_at,NULL",
            ];
      }
}
