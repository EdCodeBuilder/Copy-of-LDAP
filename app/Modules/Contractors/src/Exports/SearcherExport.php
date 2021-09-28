<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Jobs\ProcessExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Imtigger\LaravelJobStatus\JobStatus;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SearcherExport implements WithEvents, WithTitle, FromCollection, ShouldAutoSize, WithColumnFormatting, ShouldQueue
{
    use Exportable;

    public function __construct($job)
    {
        update_status_job($job, JobStatus::STATUS_EXECUTING, 'excel-contractor-portal');
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $sheet) {
                $comma = "'";
                $sheet->sheet->getDelegate()->mergeCells('A1:C1');
                $sheet->sheet->getDelegate()->getCell('A1')
                    ->setValue("PUEDES ELIMINAR LA COMILLA $comma INICIAL DE LAS FÓRMULAS DE EJEMPLO EN LAS CELDAS D8, D13 Y D26 PARA HACER FUNCIONAL EL BUSCADOR DE DATOS, LUEGO, DIGITA UN NÚMERO DE DOCUMENTO EN LA CELDA B2 PARA REALIZAR LA BÚSQUEDA.")
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);
                $sheet->sheet->getDelegate()->getCell('A1')
                    ->getStyle()
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical('center')
                    ->setHorizontal('center');
                $sheet->sheet->getDelegate()->getRowDimension(1)
                    ->setRowHeight(60);
                $sheet->sheet->getDelegate()->getColumnDimension('A')
                    ->setWidth(50);
                $sheet->sheet->getDelegate()->getColumnDimension('B')
                    ->setWidth(50);

                $this->setRanges($sheet, 'ESTUDIOS', 'H', 'D8', 'academic');
                $this->setRanges($sheet, 'CONTRATOS', 'Z', 'D13', 'contracts');
                $this->setRanges($sheet, 'ARCHIVOS', 'K', 'D26', 'files');

                $sheet->sheet->getDelegate()->getCell("J8")
                    ->getStyle()
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);

                $sheet->sheet->getDelegate()->getCell("K8")
                    ->getStyle()
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);

                foreach (range(26, 40) as $value) {
                    $sheet->sheet->getDelegate()->getCell("M$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                    $sheet->sheet->getDelegate()->getCell("N$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                }
                foreach (range(13, 21) as $value) {
                    $sheet->sheet->getDelegate()->getCell("K$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    $sheet->sheet->getDelegate()->getCell("L$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    $sheet->sheet->getDelegate()->getCell("M$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    $sheet->sheet->getDelegate()->getCell("N$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    $sheet->sheet->getDelegate()->getCell("AA$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                    $sheet->sheet->getDelegate()->getCell("AB$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                    $sheet->sheet->getDelegate()->getCell("AC$value")
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                }

                $this->setTitles($sheet->sheet, 'A2:A3', 'A2', 'DIGITA EL NÚMERO DE DOCUMENTO DEL CONTRATISTA');
                $this->setTitles($sheet->sheet, 'D5:G6', 'D5', 'ESTUDIOS ACADÉMICOS');
                $this->setTitles($sheet->sheet, 'D10:G11', 'D10', 'CONTRATOS');
                $this->setTitles($sheet->sheet, 'D23:G24', 'D23', 'ARCHIVOS');
                $sheet->sheet->getDelegate()->mergeCells('B2:B3');
                $sheet->sheet->getDelegate()->getCell('B2')
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);
                $row = 6;
                foreach ($this->contractorHeaders() as $header) {
                    $this->setTitles($sheet->sheet, "A$row", "A$row", $header, 14, false, false);
                    $row++;
                }
                $lettersAcademic = [];
                foreach (range('D', 'K') as $value) {
                    $lettersAcademic[] = $value;
                }
                foreach ($lettersAcademic as $key => $letter) {
                    $this->setTitles(
                        $sheet->sheet,
                        "{$letter}7",
                        "{$letter}7",
                        $this->headingsAcademic()[$key] ?? '',
                        14,
                        false,
                        false
                    );
                }
                $lettersContracts = [];
                foreach (range('D', 'Z') as $value) {
                    $lettersContracts[] = $value;
                }
                foreach (range('A', 'C') as $value) {
                    $lettersContracts[] = "A$value";
                }
                foreach ($lettersContracts as $key => $letter) {
                    $sheet->sheet->getDelegate()->getColumnDimension($letter)
                        ->setWidth(25);
                    $this->setTitles(
                        $sheet->sheet,
                        "{$letter}12",
                        "{$letter}12",
                        $this->headingsContracts()[$key] ?? '',
                        14,
                        false,
                        false
                    );
                }
                $lettersFile = [];
                foreach (range('D', 'N') as $value) {
                    $lettersFile[] = $value;
                }
                foreach ($lettersFile as $key => $letter) {
                    $this->setTitles(
                        $sheet->sheet,
                        "{$letter}25",
                        "{$letter}25",
                        $this->headingsFile()[$key] ?? '',
                        14,
                        false,
                        false
                    );
                }
                $row = 6;
                foreach ($this->contractorHeaders() as $header) {
                    $sheetContractor = $sheet->sheet->getDelegate()->getParent()->getSheet(0);
                    $contractorsRow = $sheetContractor->getHighestDataRow('AE');
                    $error = '""';
                    $match_header = "MATCH(BUSCADOR!A$row,CONTRATISTAS!A7:AE7,0)";
                    $range_name = "CONTRATISTAS!A8:AE$contractorsRow";
                    $lookup = "VLOOKUP(BUSCADOR!B2,$range_name,$match_header,0)";
                    $formula = "=IFERROR(IF($lookup=0,,$lookup),$error)";
                    $sheet->sheet->getDelegate()->getCell("B$row")->setValue($formula);
                    $sheet->sheet->getDelegate()->getCell("B$row")
                        ->getStyle()
                        ->getAlignment()
                        ->setVertical('center')
                        ->setHorizontal('right');
                    if ($row == 11) {
                        $sheet->sheet->getDelegate()->getCell("B$row")
                            ->getStyle()
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    }
                    if ($row == 35 || $row == 36) {
                        $sheet->sheet->getDelegate()->getCell("B$row")
                            ->getStyle()
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4);
                    }
                    $row++;
                }
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "BUSCADOR";
    }

    /**
     * @param Sheet $sheet
     * @param $cellRange
     * @param $cellValue
     * @param $value
     * @param int $fontSize
     * @param bool $merge
     * @param bool $wrap
     * @throws Exception
     */
    private function setTitles(Sheet $sheet, $cellRange, $cellValue, $value, int $fontSize = 14, bool $merge = true, bool $wrap = true)
    {
        if ($merge) {
            $sheet->getDelegate()->mergeCells($cellRange);
        }
        $sheet->getDelegate()->getCell($cellValue)
            ->setValue($value)
            ->getStyle()
            ->getFont()
            ->setSize($fontSize)
            ->setBold(true);
        $sheet->getDelegate()
            ->getStyle($cellValue)
            ->getFont()
            ->setSize($fontSize)
            ->setBold(true)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getDelegate()->getStyle($cellValue)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('594d95');
        if ($wrap) {
            $sheet->getDelegate()->getCell($cellValue)
                ->getStyle()
                ->getAlignment()
                ->setWrapText(true)
                ->setVertical('center')
                ->setHorizontal('center');
        }
    }

    /**
     * @return string[]
     */
    private function contractorHeaders(): array
    {
        return [
            'DOCUMENTO',
            'TIPO DE DOCUMENTO',
            'ID CONTRATISTA',
            'NOMBRES',
            'APELLIDOS',
            'FECHA DE NACIMIENTO',
            'EDAD',
            'PAÍS DE NACIMIENTO',
            'DEPTO DE NACIMIENTO',
            'CIUDAD DE NACIMIENTO',
            'SEXO',
            'CORREO PERSONAL',
            'CORREO INSTITUCIONAL',
            'TELÉFONO',
            'EPS',
            'OTRA EPS',
            'AFP',
            'OTRA AFP',
            'PAÍS DE RESIDENCIA',
            'DEPTO DE RESIDENCIA',
            'CIUDAD DE RESIDENCIA',
            'LOCALIDAD',
            'UPZ',
            'BARRIO',
            'OTRO BARRIO',
            'DIRECCIÓN',
            'NOMBRES USUARIO QUE REGISTRÓ CONTRATISTA',
            'APELLIDOS USUARIO QUE REGISTRÓ CONTRATISTA',
            'DOCUMENTO QUE REGISTRÓ CONTRATISTA',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    /**
     * @return string[]
     */
    private function headingsContracts(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'ID CONTRATO',
            'ESTADO DEL CONTRATO',
            'TIPO DE TRÁMITE',
            'CONTRATO',
            'SE SUMINISTRA TRANSPORTE',
            'CARGO',
            'FECHA INICIAL',
            'FECHA FINAL',
            'FECHA INICIAL DE SUSPENSIÓN',
            'FECHA FINAL DE SUSPENSIÓN',
            'VALOR TOTAL DEL CONTRATO O ADICIÓN',
            'DÍAS QUE NO TRABAJA',
            'NIVEL DE RIESGO',
            'SUBDIRECCIÓN',
            'DEPENDENCIA',
            'OTRA SUBDIRECCIÓN O DEPEDENCIA',
            'EMAIL SUPERVISOR',
            'NOMBRES CREADOR DEL CONTRATO',
            'APELLIDOS CREADOR DEL CONTRATO',
            'DOCUMENTO CREADOR DEL CONTRATO',
            'ARCHIVOS ARL',
            'ARCHIVOS OTROS',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
            'FECHA DE ELIMINACIÓN',
        ];
    }

    /**
     * @return string[]
     */
    private function headingsAcademic(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'ID ESTUDIOS',
            'NIVEL ACADÉMICO',
            'CARRERA',
            '¿ES GRADUADO?',
            'AÑO/CURSO APROBADO',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    /**
     * @return string[]
     */
    private function headingsFile(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'ID ARCHIVO',
            'CONTRATO',
            'TIPO DE CONTRATO',
            'TIPO DE ARCHIVO',
            'NOMBRE ARCHIVO',
            'NOMBRE USUARIO QUE CREA ARCHIVO',
            'APELLIDOS USUARIO QUE CREA ARCHIVO',
            'DOCUMENTO USUARIO QUE CREA ARCHIVO',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    /**
     * @param $sheet
     * @param $maxRow
     * @throws Exception
     */
    private function setRanges($sheet, $sheetName, $maxRow, $targetCell, $rangeName)
    {
        $academicSheet = $sheet->sheet->getDelegate()
            ->getParent()
            ->getSheetByName($sheetName);
        $academicRow = $academicSheet->getHighestDataRow('A');
        $sheet->sheet->getDelegate()
            ->getParent()
            ->addNamedRange( new NamedRange("data_{$rangeName}", $academicSheet, "A8:A$academicRow") );
        $sheet->sheet->getDelegate()
            ->getParent()
            ->addNamedRange( new NamedRange("data_{$rangeName}_array", $academicSheet, "A8:{$maxRow}{$academicRow}") );
        $error = '""';
        $formula = "FILTRAR(data_{$rangeName}_array;data_{$rangeName}=B2)";
        $formula = "'=SI.ERROR(SI($formula=0;$error;$formula);$error)";
        $sheet->sheet->getDelegate()->getCell($targetCell)->setValue($formula);
    }

    /**
     * @return Collection|\Tightenco\Collect\Support\Collection
     */
    public function collection()
    {
        return collect([]);
    }

    public function columnFormats(): array
    {
        return [
            'K'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'M'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'N'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AA'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AB'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AC'  => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
