<?php

namespace App\Modules\Parks\src\Constants;

class ParkStatus
{
    /**
     * @return string[]
     */
    public function zones() {
        return [
            'RESIDENCIAL',
            'COMERCIAL',
            'SENDEROS',
            'MIXTO',
            'RESIDENCIAL/COMERCIAL',
            'INDUSTRIAL/COMERCIAL',
            'RURAL',
            'MONTAÑOSO',
            'INDUSTRIAL',
            'INSTITUCIONAL',
            'ESCOLAR',
            'OTRO',
            'NINGUNO',
            'BUENO',
            'MALO',
            'REGULAR',
        ];
    }

    /**
     * @return array
     */
    public static function zoneTypesAsKeyValue()
    {
        return (new ParkStatus)->formatter( (new ParkStatus)->zones() );
    }

    /**
     * @return string[]
     */
    public function concerns()
    {
        return [
            'IDRD',
            'Junta Administradora Local',
            'Alcaldía Local',
            'Alianza Público Privada',
            'Indefinido',
            'Otros',
            'SI',
            'NO',
        ];
    }

    /**
     * @return array
     */
    public static function concernsAsKeyValue()
    {
        return (new ParkStatus)->formatter( (new ParkStatus)->concerns() );
    }

    /**
     * @return string[]
     */
    public function vigilance()
    {
        return [
            'Sin vigilancia',
            'Con vigilancia',
        ];
    }

    /**
     * @return array
     */
    public static function vigilanceAsKeyValue()
    {
        return (new ParkStatus)->formatter( (new ParkStatus)->vigilance() );
    }

    private function formatter($array) {
        $items = [];
        foreach ($array as $value) {
            $items[] = [
                'id'    => $value,
                'name'    => $value,
            ];
        }
        return $items;
    }
}
