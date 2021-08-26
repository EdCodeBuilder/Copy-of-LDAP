<?php

namespace App\Modules\CitizenPortal\src\Imports;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xml;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\Reader\Slk;
use PhpOffice\PhpSpreadsheet\Reader\Gnumeric;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Excel
{
    /**
     * @var Xlsx|Xls|Xml|Ods|Slk|Gnumeric|Html|Csv
     */
    private $reader;

    /**
     * @var Filesystem
     */
    private $storage;

    public function importer($filePath, $readerType = null, $startRow = 2)
    {
        $this->reader = IOFactory::createReader(
            $readerType ?: IOFactory::identify(storage_path("app/tmp/$filePath"))
        );
        $this->storage = Storage::disk('local');
        return $this->read($filePath);
    }

    public static function import($filePath, $readerType = null, $startRow = 2)
    {
        return resolve(Excel::class)->importer($filePath, $readerType, $startRow);
    }

    public function read($filePath)
    {
        $job = collect();
        $this->reader->setReadDataOnly(true);
        $this->reader->setReadEmptyCells(false);
        $spreadsheet = $this->reader->load(storage_path("app/tmp/$filePath"));
        $sheet = $spreadsheet->getSheetByName('MASIVOS');
        $rows = $sheet->toArray(null, true, true, true);
        foreach ($rows as $key => $row) {
            $result = [];
            if ($key > 1) {
                foreach (range('A', 'Q') as $col) {
                    $k = Str::slug($sheet->getCell("{$col}1")->getValue(), '_');
                    if (in_array($col, ['N', 'O']) && !(is_null($row[$col]) || $row[$col] == '' )) {
                        $result[$k] = Carbon::parse(Date::excelToTimestamp($row[$col]))->format('Y-m-d H:i:s');
                    } else {
                        $result[$k] = $row[$col];
                    }
                }
                $job->push(
                    $result
                );
            }
        }
        if ($this->storage->exists("tmp/$filePath")) {
            $this->storage->delete("tmp/$filePath");
        }
        return $job->toArray();
    }

    /**
     * @param $filePath
     * @return array
     * @throws Exception
     */
    public function getTotalRows($filePath)
    {
        $info = $this->reader->listWorksheetInfo($filePath);
        $totalRows = [];
        foreach ($info as $sheet) {
            $totalRows[$sheet['worksheetName']] = $sheet['totalRows'];
        }
        return $totalRows;
    }

    /**
     * @param $filePath
     * @return array
     * @throws Exception
     */
    public function getWorkSheets($filePath)
    {
        $worksheets     = [];
        $worksheetNames = $this->reader->listWorksheetNames($filePath);
        foreach ($worksheetNames as $index => $sheetImport) {
            // Specify with worksheet name should have which import.
            $worksheets[$index] = $sheetImport;
        }
        return $worksheets;
    }
}
