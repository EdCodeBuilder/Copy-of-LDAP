<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class ParkFurniture extends Model implements Auditable
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
	protected $table = 'parquemobiliario';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

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
		'idParque',
		'idMobiliario',
		'Material',
		'Bueno',
		'Malo',
		'Regular',
		'Total',
		'Descrpicion',
		'Imagen',
		'Publico',
	];


    public function furniture()
    {
        return $this->belongsTo(Furniture::class, 'idMobiliario');
    }

	public function park()
	{
		return $this->belongsTo(Park::class, 'idParque', 'Id');
	}

	public function material()
	{
		return $this->belongsTo(Material::class, 'Material', 'IdMaterial');
	}

	/**
	 * @param array $request
	 * @return array
	 */
	public function transformRequest(array $request, $action, $imageOld = '')
	{
		return [
			'idParque'             	=>  Arr::get($request, 'park_id', null),
			'idMobiliario'               =>  Arr::get($request, 'furniture_id', null),
			'Material'              =>  Arr::get($request, 'material', null),
			'Bueno'               	=>  Arr::get($request, 'good', null),
			'Malo'          		=>  toUpper(Arr::get($request, 'bad', null)),
			'Regular'               =>  Arr::get($request, 'regular', null),
			'Total'                 =>  Arr::get($request, 'total', null),
			'Descrpicion'           =>  toUpper(Arr::get($request, 'description', null)),
			'Imagen'                =>  Arr::get($request, 'image', null),
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
