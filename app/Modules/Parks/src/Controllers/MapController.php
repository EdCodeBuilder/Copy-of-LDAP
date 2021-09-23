<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @group Parques - Mapa
 *
 * Api para la configuración del mapa de parques.
 */
class MapController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Parques - Mapa
     *
     * Mapa
     *
     * Provee la configuración del mapa y los servicios para embeber el
     * mapa desde una etiqueta HTML <iframe> o para utilizar las
     * librerías de ESRI/ARCGis junto con el API de Catastro.
     *
     * @responseFile responses/parks/assigned.get.json
     * @return JsonResponse
     */
    public function map()
    {
        return $this->success_message([
            'iframe' => [
                'url'   => 'https://mapas.bogota.gov.co/?l=436&e=-74.57201001759988,4.2906625340901,-73.61070630666201,4.928542831147915,4686&show_menu=false',
                'dark'  => '&b=7176',
                'light' => '&b=262',
                'filter' => '&layerFilter=436;',
            ],
            'layer' => [
                'url'   => 'https://serviciosgis.catastrobogota.gov.co/arcgis/rest/services/recreaciondeporte/parquesyescenarios/MapServer/1',
                'outFields' => [
                    'OBJECTID',
                    'ID_PARQUE',
                    'NOMBRE_PARQ',
                    'CODIGOPOT',
                    'TIPOPARQUE',
                    'ID_UPZ',
                    'ID_LOCALIDAD',
                    'LOCNOMBRE',
                    'ESTRATO',
                    'ADMINISTRA',
                    'ESTADO_CER',
                    'FECHAINCORPORACION',
                    'SHAPE',
                ],
                'popupTemplate' => [
                    'title' => '{ID_PARQUE} - {NOMBRE_PARQ}',
                    'lastEditInfoEnabled' => true,
                    'content' => [
                        [
                            'type' => 'fields',
                            'fieldInfos' => [
                                [
                                    'fieldName' => 'TIPOPARQUE',
                                    'label' => 'Tipo de Parque',
                                ],
                                [
                                    'fieldName' => 'ID_UPZ',
                                    'label' => 'UPZ',
                                ],
                                [
                                    'fieldName' => 'LOCNOMBRE',
                                    'label' => 'Localidad',
                                ],
                                [
                                    'fieldName' => 'ESTRATO',
                                    'label' => 'Estrato',
                                ],
                                [
                                    'fieldName' => 'ADMINISTRA',
                                    'label' => 'Administrado por',
                                ],
                                [
                                    'fieldName' => 'ESTADO_CER',
                                    'label' => 'Estado Certificado',
                                ],
                                [
                                    'fieldName' => 'FECHAINCORPORACION',
                                    'label' => 'Fecha de Incorporación',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'param' => 'ID_PARQUE=',
            'park_types' => [
                [
                    'name' => 'TODO',
                    'value' => 'todo',
                    'style' => [
                        'backgroundColor' => 'rgba(89,77,149, 1)',
                        'borderColor' => 'rgba(89,77,149, 1)',
                    ],
                ],
                [
                    'name' => 'PARQUE REGIONAL',
                    'value' => "TIPOPARQUE='PARQUE REGIONAL'",
                    'style' => [
                        'backgroundColor' => 'rgba(56, 168, 0, 1)',
                        'borderColor' => 'rgba(56, 168, 0, 1)',
                    ],
                ],
                [
                    'name' => 'PARQUE METROPOLITANO',
                    'value' => "TIPOPARQUE='PARQUE METROPOLITANO'",
                    'style' => [
                        'backgroundColor' => 'rgba(112, 168, 0, 1)',
                        'borderColor' => 'rgba(112, 168, 0, 1)',
                    ],
                ],
                [
                    'name' => 'PARQUE ZONAL',
                    'value' => "TIPOPARQUE='PARQUE ZONAL'",
                    'style' => [
                        'backgroundColor' => 'rgba(170, 255, 0, 1)',
                        'borderColor' => 'rgba(170, 255, 0, 1)',
                    ],
                ],
                [
                    'name' => 'ESCENARIO DEPORTIVO',
                    'value' => "TIPOPARQUE='ESCENARIO DEPORTIVO'",
                    'style' => [
                        'backgroundColor' => 'rgba(230, 152, 0, 1)',
                        'borderColor' => 'rgba(230, 152, 0, 1)',
                    ],
                ],
                [
                    'name' => 'PARQUE VECINAL',
                    'value' => "TIPOPARQUE='PARQUE VECINAL'",
                    'style' => [
                        'backgroundColor' => 'rgba(209, 255, 115, 1)',
                        'borderColor' => 'rgba(209, 255, 115, 1)',
                    ],
                ],
                [
                    'name' => 'PARQUE DE BOLSILLO',
                    'value' => "TIPOPARQUE='PARQUE DE BOLSILLO'",
                    'style' => [
                        'backgroundColor' => 'rgba(233, 255, 190, 1)',
                        'borderColor' => 'rgba(233, 255, 190, 1)',
                    ],
                ],
                [
                    'name' => 'ADMINISTRA IDRD',
                    'value' => "ADMINISTRA='IDRD'",
                    'style' => [
                        'backgroundColor' => 'rgb(255,190,200)',
                        'borderColor' => 'rgba(255,190,200)',
                    ],
                ],
            ],
        ]);
    }
}
