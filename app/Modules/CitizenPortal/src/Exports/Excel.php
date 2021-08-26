<?php

namespace App\Modules\CitizenPortal\src\Exports;

class Excel extends \Maatwebsite\Excel\Excel
{
    public function toRaw($export, string $writerType)
    {
        $temporaryFile = $this->writer->export($export, $writerType);
        $contents = file_get_contents($temporaryFile);
        return $contents;
    }

    public static function raw($export, string $writerType)
    {
        return resolve(Excel::class)->toRaw($export, $writerType);
    }
}
