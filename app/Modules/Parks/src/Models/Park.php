<?php

namespace App\Modules\Parks\src\Models;

use App\Traits\FullTextSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Park extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, FullTextSearch, SoftDeletes;

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
    protected $table = 'parque';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Nombre',
        'Direccion',
        'Id_Localidad',
        'Id_Tipo',
        'CompeteIDRD',
        'Area',
        'Estrato',
        'Mapa',
        'Id_IDRD',
        'Imagen',
        'TelefonoParque',
        'Upz',
        'EstadoCertificado',
        'AreaZDura',
        'AreaZVerde',
        'UbicacionCertificado',
        'FechaVisita',
        'EstadoGeneral',
        'Cerramiento',
        'Viviendas',
        'TipoZona',
        'Administracion',
        'CantidadSenderos',
        'EstadoSendero',
        'ViasAcceso',
        'EstadoVias',
        'PoblacionInfantil',
        'PoblacionJuvenil',
        'PoblacionMayor',
        'NomAdministrador',
        'Estado',
        'Longitud',
        'Latitud',
        'Urbanizacion',
        'Areageo_enHa',
        'Email',
        'Id_Barrio',
        'Vigilancia',
        'Aforo',
        'RecibidoIdrd',
        'Id_Tipo_Escenario',
        'Id_Vocacion',
    ];

    /**
     * The columns of the full text index
     *
     * @var array
     */
    protected $searchable = [
        'Id_IDRD',
        'Nombre',
        'Direccion',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['FechaVisita'];

    /*
    * ---------------------------------------------------------
    * Data Change Auditor
    * ---------------------------------------------------------
    */

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'Nombre',
        'Direccion',
        'Id_Localidad',
        'Id_Tipo',
        'CompeteIDRD',
        'Area',
        'Estrato',
        'Mapa',
        'Id_IDRD',
        'Imagen',
        'TelefonoParque',
        'Upz',
        'EstadoCertificado',
        'AreaZDura',
        'AreaZVerde',
        'UbicacionCertificado',
        'FechaVisita',
        'EstadoGeneral',
        'Cerramiento',
        'Viviendas',
        'TipoZona',
        'Administracion',
        'CantidadSenderos',
        'EstadoSendero',
        'ViasAcceso',
        'EstadoVias',
        'PoblacionInfantil',
        'PoblacionJuvenil',
        'PoblacionMayor',
        'NomAdministrador',
        'Estado',
        'Longitud',
        'Latitud',
        'Urbanizacion',
        'Areageo_enHa',
        'Email',
        'Id_Barrio',
        'Vigilancia',
        'Aforo',
        'RecibidoIdrd',
        'Id_Tipo_Escenario',
        'Id_Vocacion',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park'];
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return Model|void|null
     */
    public function resolveRouteBinding($value)
    {
        return $this
                ->where('Id', $value)
                ->orWhere('Id_IDRD', $value)
                ->first() ?? abort(404);
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case $this->primaryKey:
                return $this->primaryKey;
            case 'created_at':
            case 'updated_at':
            case 'deleted_at':
                return $column;
            default:
                return in_array($this->getFillableValues($column), $this->fillable)
                    ? $this->getFillableValues($column)
                    : $this->primaryKey;
        }
    }

    public function getFillableValues($value)
    {
        $attrs = [
        'id' => 'Id',
        'code' => 'Id_IDRD',
        'name' => 'Nombre',
        'address'  => 'Direccion',
        'stratum'  => 'Estrato',
        'locality_id'  => 'Id_Localidad',
        'upz_code' => 'Upz',
        'neighborhood_id'  => 'Id_Barrio',
        'urbanization' => 'Urbanizacion',
        'latitude' => 'Latitud',
        'longitude'    => 'Longitud',
        'area_hectare' => 'Areageo_enHa',
        'area' => 'Area',
        'grey_area'    => 'AreaZDura',
        'green_area'   => 'AreaZVerde',
        'capacity' => 'Aforo',
        'children_population'  => 'PoblacionInfantil',
        'youth_population' => 'PoblacionJuvenil',
        'older_population' => 'PoblacionMayor',
        'enclosure'    => 'Cerramiento',
        'households'   => 'Viviendas',
        'walking_trails'   => 'CantidadSenderos',
        'walking_trails_status'    => 'EstadoSendero',
        'access_roads' => 'ViasAcceso',
        'access_roads_status'  => 'EstadoVias',
        'zone_type'    => 'TipoZona',
        'scale_id' => 'Id_Tipo',
        'concern'  => 'CompeteIDRD',
        'visited_at'   => 'FechaVisita',
        'general_status'   => 'EstadoGeneral',
        'stage_type_id'    => 'Id_Tipo_Escenario',
        'status_id'    => 'Estado',
        'admin'    => 'Administracion',
        'phone'    => 'TelefonoParque',
        'email'    => 'Email',
        'admin_name'   => 'NomAdministrador',
        'vigilance'    => 'Vigilancia',
        'received' => 'RecibidoIdrd',
        'vocation_id'  => 'Id_Vocacion',
    ];
        return Arr::get($attrs, $value, $value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNombreAttribute($value)
    {
        $this->attributes['Nombre'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setDireccionAttribute($value)
    {
        $this->attributes['Direccion'] = toUpper($value);
    }

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * Parks managed by IDRD
     *
     * @param $query
     * @return mixed
     */
    public function scopeIdrd($query)
    {
        return $query->where('Administracion', 'IDRD');
    }

    /**
     * Parks not managed by IDRD
     *
     * @param $query
     * @return mixed
     */
    public function scopeNotIdrd($query)
    {
        return $query->where('Administracion', '!=', 'IDRD');
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * Park has one locality
     *
     * @return HasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class, 'Id_Localidad', 'Id_Localidad');
    }

    /**
     * Park has one scale type
     *
     * @return HasOne
     */
    public function scale()
    {
        return $this->hasOne(Scale::class, 'Id_Tipo', 'Id_Tipo');
    }

    /**
     * Park has one UPZ
     *
     * @return HasOne
     */
    public function upz_name()
    {
        return $this->hasOne(Upz::class, 'cod_upz', 'Upz');
    }

    /**
     * Park has one neighborhood
     *
     * @return HasOne
     */
    public function neighborhood()
    {
        return $this->hasOne(Neighborhood::class, 'IdBarrio', 'Id_Barrio');
    }

    /**
     * Park has one certified
     *
     * @return HasOne
     */
    public function certified()
    {
        return $this->hasOne( Certified::class, 'id_EstadoCertificado', 'EstadoCertificado' );
    }

    /**
     * Park has endowments
     *
     * @return HasMany
     */
    public function park_endowment()
    {
        return $this->hasMany( ParkEndowment::class, 'Id_Parque', 'Id' );
    }

    /**
     * Park has furnitures
     *
     * @return HasMany
     */
    public function park_furnitures()
    {
        return $this->hasMany( ParkFurniture::class, 'idParque', 'Id' );
    }

    /**
     * Park belongs to many endowments
     *
     * @return BelongsToMany
     */
    public function endowments()
    {
        return $this->belongsToMany(Endowment::class, 'parquedotacion', 'Id_Parque', 'Id_Dotacion')
            ->withPivot('Num_Dotacion', 'Estado', 'Material', 'iluminacion', 'Aprovechamientoeconomico', 'Area', 'MaterialPiso', 'Cerramiento', 'Camerino', 'Luz', 'Agua', 'Gas', 'Capacidad', 'Carril', 'Bano', 'BateriaSanitaria', 'Descripcion', 'Diag_Mantenimiento', 'Diag_Construcciones', 'Posicionamiento', 'Destinacion', 'Imagen', 'Fecha', 'TipoCerramiento', 'AlturaCerramiento', 'Largo', 'Ancho', 'Cubierto', 'Dunt', 'B_Masculino', 'B_Femenino', 'B_Discapacitado', 'C_Vehicular', 'C_BiciParqueadero', 'Publico');
    }

    /**
     * Park has many sectors
     *
     * @return HasMany
     */
    public function sectors()
    {
        return $this->hasMany(Sector::class, 'i_fk_id_parque');
    }

    /**
     * Park has many rupis
     *
     * @return HasMany
     */
    public function rupis()
    {
        return $this->hasMany(Rupi::class, 'Id_Parque', 'Id');
    }

    /**
     * Park has one certified
     *
     * @return HasOne
     */
    public function status()
    {
        return $this->hasOne(Status::class, 'Id_Estado','Estado');
    }

    /**
     * Park has one stage type
     *
     * @return HasOne
     */
    public function stage_type()
    {
        return $this->hasOne(StageType::class, 'id','Id_Tipo_Escenario');
    }

    /**
     * Park has one vovation
     *
     * @return HasOne
     */
    public function vocation()
    {
        return $this->hasOne(Vocation::class, 'id','Id_Vocacion');
    }

    /**
     * Park has one history
     *
     * @return HasOne
     */
    public function history()
    {
        return $this->hasOne(Origin::class, 'IdParque','Id');
    }

    /**
     * Park has many stories
     *
     * @return HasMany
     */
    public function story()
    {
        return $this->hasMany(Story::class, 'idParque','Id');
    }

    /**
     * A park has many emergency plans
     *
     * @return HasMany
     */
    public function emergency_plans()
    {
        return $this->hasMany(EmergencyPlan::class, 'idParque', 'Id');
    }

    /**
     * @param array $request
     * @return array
     */
    public function transformRequest(array $request)
    {
        return [
            'Id_IDRD'               =>  Arr::get($request, 'code', null),
            'Nombre'                =>  toUpper( Arr::get($request, 'name', null) ),
            'Direccion'             =>  toUpper( Arr::get($request, 'address', null) ),
            'Estrato'               =>  Arr::get($request, 'stratum', null),
            'Id_Localidad'          =>  Arr::get($request, 'locality_id', null),
            'Upz'                   =>  Arr::get($request, 'upz_code', null),
            'Id_Barrio'             =>  Arr::get($request, 'neighborhood_id', null),
            'Urbanizacion'          =>  toUpper(Arr::get($request, 'urbanization', null)),
            'Latitud'               =>  number_format(Arr::get($request, 'latitude', 0), 15),
            'Longitud'              =>  number_format(Arr::get($request, 'longitude', 0), 15),
            'Areageo_enHa'          =>  Arr::get($request, 'area_hectare', null),
            'Area'                  =>  Arr::get($request, 'area', null),
            'AreaZDura'             =>  Arr::get($request, 'grey_area', null),
            'AreaZVerde'            =>  Arr::get($request, 'green_area', null),
            'Aforo'                 =>  Arr::get($request, 'capacity', null),
            'PoblacionInfantil'     =>  Arr::get($request, 'children_population', null),
            'PoblacionJuvenil'      =>  Arr::get($request, 'youth_population', null),
            'PoblacionMayor'        =>  Arr::get($request, 'older_population', null),
            'Cerramiento'           =>  Arr::get($request, 'enclosure', null),
            'Viviendas'             =>  Arr::get($request, 'households', null),
            'CantidadSenderos'      =>  Arr::get($request, 'walking_trails', null),
            'EstadoSendero'         =>  Arr::get($request, 'walking_trails_status', null),
            'ViasAcceso'            =>  toUpper(Arr::get($request, 'access_roads', null)),
            'EstadoVias'            =>  Arr::get($request, 'access_roads_status', null),
            'TipoZona'              =>  Arr::get($request, 'zone_type', null),
            'Id_Tipo'               =>  Arr::get($request, 'scale_id', null),
            'CompeteIDRD'           =>  Arr::get($request, 'concern', null),
            'FechaVisita'           =>  Arr::get($request, 'visited_at', null),
            'EstadoGeneral'         =>  toUpper(Arr::get($request, 'general_status', null)),
            'Id_Tipo_Escenario'     =>  Arr::get($request, 'stage_type_id', null),
            'Estado'                =>  Arr::get($request, 'status_id', null),
            'Administracion'        =>  Arr::get($request, 'admin', null),
            'TelefonoParque'        =>  Arr::get($request, 'phone', null),
            'Email'                 =>  toLower(Arr::get($request, 'email', null)),
            'NomAdministrador'      =>  toUpper(Arr::get($request, 'admin_name', null)),
            'Vigilancia'            =>  Arr::get($request, 'vigilance', null),
            'RecibidoIdrd'          =>  Arr::get($request, 'received', null),
            'Id_Vocacion'           =>  Arr::get($request, 'vocation_id', null),
            // 'Mapa'                  =>  Arr::get($request, '', null),
            // 'Imagen'                =>  Arr::get($request, '', null),
            // 'EstadoCertificado'     =>  Arr::get($request, '', null),
            // 'UbicacionCertificado'  =>  Arr::get($request, '', null),
        ];
    }
}
