<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Endowment;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\ParkEndowment;
use App\Modules\Parks\src\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

class ParkEndowmentUpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return auth('api')->user()->can(Roles::can(ParkEndowment::class, 'update'), ParkEndowment::class) ||
			auth('api')->user()->can(Roles::can(ParkEndowment::class, 'manage'), ParkEndowment::class);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$park = new Park();
		$endowment = new Endowment();
		return [
			//
			'park_id'				=>  "required|numeric|exists:{$park->getConnectionName()}.{$park->getTable()},{$park->getKeyName()}",
			'endowment_id'			=>  "required|numeric|exists:{$endowment->getConnectionName()}.{$endowment->getTable()},{$endowment->getKeyName()}",
			'endowment_num'			=>  'required|numeric',
			'status'				=>  'required|numeric',
			'material'				=>  'required|string',
			'illumination'			=>  'required|string',
			'economic_use'			=>  'required|string',
			'area'					=>  'required|numeric',
			'floor_material'			=>  'required|numeric',
			'enclosure'				=>  'required|string',
			'dressing_room'			=>  'required|string',
			'light'				=>  'required|string',
			'water'				=>  'required|string',
			'gas'					=>  'required|string',
			'capacity'				=>  'required|numeric',
			'lane'					=>  'required|numeric',
			'bathroom'				=>  'required|numeric',
			'sanitary_battery'			=>  'required|numeric',
			'description'				=>  'required|string',
			'diag_maintenance'			=>  'required|string',
			'diag_constructions'			=>  'required|string',
			'Positioning'				=>  'required|string',
			'destination'				=>  'required|string',
			'image'				=>  'required',
			'date'					=>  'required|date|date_format:Y-m-d',
			'enclosure_type'			=>  'required|string',
			'enclosure_height'			=>  'required|string',
			'long'					=>  'required|numeric',
			'width'				=>  'required|numeric',
			'covered'				=>  'required|string',
			'dunt'					=>  'required|numeric',
			'b_male'				=>  'required|numeric',
			'b_female'				=>  'required|numeric',
			'b_disabled'				=>  'required|numeric',
			'c_vehicle'				=>  'required|numeric',
			'c_bike_parking'			=>  'required|numeric',
			'public'				=>  'required|numeric',
			'i_fk_id_sector'			=>  'required|numeric',
			'mapping'				=>  'required|string',
		];
	}
}
