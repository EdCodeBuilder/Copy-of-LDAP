<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class ParkEndowment extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'mysql_parks';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'parquedotacion';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'Id';

	// /**
	//  * Indicates if the model should be timestamped.
	//  *
	//  * @var bool
	//  */
	// public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'Id_Parque',
		'Id_Dotacion',
		'Num_Dotacion',
		'Estado',
		'Material',
		'Iluminacion',
		'AprovechamientoEconomico',
		'Area',
		'MaterialPiso',
		'Cerramiento',
		'Camerino',
		'Luz',
		'Agua',
		'Gas',
		'Capacidad',
		'Carril',
		'Bano',
		'BateriaSanitaria',
		'Descripcion',
		'Diag_Mantenimiento',
		'Diag_Construcciones',
		'Posicionamiento',
		'Destinacion',
		'Imagen',
		'Fecha',
		'TipoCerramiento',
		'AlturaCerramiento',
		'Largo',
		'Ancho',
		'Cubierto',
		'Dunt',
		'B_Masculino',
		'B_Femenino',
		'B_Discapacitado',
		'C_Vehicular',
		'C_BiciParqueadero',
		'Publico',
		'i_fk_id_sector',
		'mapeo'
	];



	public function park()
	{
		return $this->belongsTo(Park::class, 'Id_Parque', 'Id');
	}

	public function material()
	{
		return $this->belongsTo(Material::class, 'MaterialPiso', 'IdMaterial');
	}

	public function endowment()
	{
		return $this->belongsTo(Endowment::class, 'Id_Dotacion');
	}

	public function enclosure()
	{
		return $this->belongsTo(Enclosure::class, 'Cerramiento');
	}

	public function status()
	{
		return $this->belongsTo(Status::class, 'Estado');
	}

	/**
	 * @param array $request
	 * @return array
	 */
	public function transformRequest(array $request, $action, $imageOld = '')
	{
		return [
			'Id_Parque'             	=>  Arr::get($request, 'park_id', null),
			'Id_Dotacion'               =>  Arr::get($request, 'endowment_id', null),
			'Num_Dotacion'              =>  Arr::get($request, 'endowment_num', null),
			'Estado'               	=>  Arr::get($request, 'status', null),
			'Material'          		=>  toUpper(Arr::get($request, 'material', null)),
			'Iluminacion'               =>  toUpper(Arr::get($request, 'illumination', null)),
			'AprovechamientoEconomico'  =>  toUpper(Arr::get($request, 'economic_use', null)),
			'Area'          		=>  Arr::get($request, 'area', null),
			'MaterialPiso'              =>  Arr::get($request, 'floor_material', null),
			'Cerramiento'              	=>  toUpper(Arr::get($request, 'enclosure', null)),
			'Camerino'          		=>  toUpper(Arr::get($request, 'dressing_room', null)),
			'Luz'                  	=>  toUpper(Arr::get($request, 'light', null)),
			'Agua'             		=>  toUpper(Arr::get($request, 'water', null)),
			'Gas'            		=>  toUpper(Arr::get($request, 'gas', null)),
			'Capacidad'                 =>  Arr::get($request, 'capacity', null),
			'Carril'     			=>  Arr::get($request, 'lane', null),
			'Bano'      			=>  Arr::get($request, 'bathroom', null),
			'BateriaSanitaria'        	=>  Arr::get($request, 'sanitary_battery', null),
			'Descripcion'           	=>  toUpper(Arr::get($request, 'description', null)),
			'Diag_Mantenimiento'        =>  toUpper(Arr::get($request, 'diag_maintenance', null)),
			'Diag_Construcciones'      	=>  toUpper(Arr::get($request, 'diag_constructions', null)),
			'Posicionamiento'         	=>  toUpper(Arr::get($request, 'Positioning', null)),
			'Destinacion'			=>  toUpper(Arr::get($request, 'destination', null)),
			'Imagen'            		=>  $this->getImage(Arr::get($request, 'image', null), $action, $imageOld),
			'Fecha'              	=>  Arr::get($request, 'date', null),
			'TipoCerramiento'           =>  toUpper(Arr::get($request, 'enclosure_type', null)),
			'AlturaCerramiento'         =>  toUpper(Arr::get($request, 'enclosure_height', null)),
			'Largo'           		=>  Arr::get($request, 'long', null),
			'Ancho'         		=>  Arr::get($request, 'width', null),
			'Cubierto'     		=>  toUpper(Arr::get($request, 'covered', null)),
			'Dunt'                	=>  Arr::get($request, 'dunt', null),
			'B_Masculino'        	=>  Arr::get($request, 'b_male', null),
			'B_Femenino'        		=>  Arr::get($request, 'b_female', null),
			'B_Discapacitado'           =>  Arr::get($request, 'b_disabled', null),
			'C_Vehicular'      		=>  Arr::get($request, 'c_vehicle', null),
			'C_BiciParqueadero'         =>  Arr::get($request, 'c_bike_parking', null),
			'Publico'          		=>  Arr::get($request, 'public', null),
			'i_fk_id_sector'           	=>  Arr::get($request, 'i_fk_id_sector', null),
			'mapeo'                  	=>  Arr::get($request, 'mapping', null)
		];
	}

	private function getImage($image, $action, $imageOld)
	{
		if ($action === 'create') {
			$imageName = random_img_name() . '.' . $image->getClientOriginalExtension();
			$image->storePubliclyAs('parks', $imageName, ['disk' => 'public']);
			return $imageName;
		} else {
			if (gettype($image) === 'string') {
				return $image;
			} else {
				if ($imageOld === '' || $imageOld === null) {
					$imageName = random_img_name() . '.' . $image->getClientOriginalExtension();
					$image->storePubliclyAs('parks', $imageName, ['disk' => 'public']);
					return $imageName;
				} else {
					$imageName = random_img_name() . '.' . $image->getClientOriginalExtension();
					$existImage =  Storage::disk('public')->exists("parks/{$imageOld}");
					if ($existImage) {
						Storage::disk('public')->delete("parks/{$imageOld}");
					}
					$image->storePubliclyAs('parks', $imageName, ['disk' => 'public']);
					return $imageName;
				}
			}
		}
	}
}
