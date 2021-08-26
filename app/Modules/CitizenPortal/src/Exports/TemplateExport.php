<?php

namespace App\Modules\CitizenPortal\src\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Writer;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\NamedRange;

class TemplateExport implements WithMultipleSheets, WithEvents
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[0] = new DataExport();
        $sheets[1] = new ProgramExport();
        $sheets[2] = new ActivityExport();
        $sheets[3] = new StageExport();
        $sheets[4] = new DayExport();
        $sheets[5] = new HourExport();
        return  $sheets;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $writer) {
                $writer->writer->getDelegate()->setActiveSheetIndex(0);
                $sheet = $writer->writer->getDelegate()->getSheetByName('MASIVOS');

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $sheet->getStyle("A1:Q1")
                    ->applyFromArray($styleArray);

                $sheet->getStyle('A1:Q1')
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $sheet->getRowDimension(1)->setRowHeight(50);
                $sheet->getStyle('A1:Q1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('594d95');

                $this->setComment(
                    $writer->writer,
                    'MASIVOS',
                    'N2',
                    'Formato de fecha:',
                    "Por favor utiliza el formato AAAA-MM-DD HH:MM:SS."
                );

                $this->setComment(
                    $writer->writer,
                    'MASIVOS',
                    'O2',
                    'Formato de fecha:',
                    "Por favor utiliza el formato AAAA-MM-DD HH:MM:SS."
                );

                $this->setComment(
                    $writer->writer,
                    'MASIVOS',
                    'P2',
                    'Formato del campo:',
                    "Por favor utiliza el número 1 para SI y 0 para NO."
                );

                $this->setComment(
                    $writer->writer,
                    'MASIVOS',
                    'Q2',
                    'Formato del campo:',
                    "Por favor utiliza el número 1 para SI y 0 para NO."
                );

                $this->setSelectors($writer->writer, 'B2', 'PROGRAMAS', 'B', 'programs_list');
                $this->setSelectors($writer->writer, 'D2', 'ACTIVIDADES', 'B', 'activities_list');
                $this->setSelectors($writer->writer, 'F2', 'ESCENARIOS', 'B', 'stages_list');
                $this->setSelectors($writer->writer, 'H2', 'DIAS', 'B', 'days_list');
                $this->setSelectors($writer->writer, 'J2', 'HORAS', 'B', 'hours_list');
                $this->setLookup($writer->writer, 'A2', 'MASIVOS', 'B', 'programs_list');
                $this->setLookup($writer->writer, 'C2', 'MASIVOS', 'D', 'activities_list');
                $this->setLookup($writer->writer, 'E2', 'MASIVOS', 'F', 'stages_list');
                $this->setLookup($writer->writer, 'G2', 'MASIVOS', 'H', 'days_list');
                $this->setLookup($writer->writer, 'I2', 'MASIVOS', 'J', 'hours_list');
            }
        ];
    }

    /**
     * @param Writer $writer
     * @param $target_cell
     * @param $source_sheet_name
     * @param $source_row
     * @param $range_name
     * @throws Exception
     */
    public function setSelectors(Writer $writer, $target_cell, $source_sheet_name, $source_row, $range_name)
    {
        $source_sheet = $writer->getDelegate()->getSheetByName($source_sheet_name);
        $max_data_row = $source_sheet->getHighestRow($source_row);
        $sheet = $writer->getDelegate()->getSheetByName('MASIVOS');
        $sheet->getParent()->addNamedRange( new NamedRange($range_name, $source_sheet, "{$source_row}2:{$source_row}{$max_data_row}") );
        $sheet->getCell($target_cell)->getDataValidation()->setType(DataValidation::TYPE_LIST);
        $sheet->getCell($target_cell)->getDataValidation()->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $sheet->getCell($target_cell)->getDataValidation()->setAllowBlank(false);
        $sheet->getCell($target_cell)->getDataValidation()->setShowInputMessage(true);
        $sheet->getCell($target_cell)->getDataValidation()->setShowErrorMessage(true);
        $sheet->getCell($target_cell)->getDataValidation()->setShowDropDown(true);
        $sheet->getCell($target_cell)->getDataValidation()->setErrorTitle('Error');
        $sheet->getCell($target_cell)->getDataValidation()->setError('El valor no está en la lista.');
        $sheet->getCell($target_cell)->getDataValidation()->setPromptTitle('Selecciona una opción de la lista');
        $sheet->getCell($target_cell)->getDataValidation()->setPrompt('Pro favor selecciona un valor de la lista.');
        $sheet->getCell($target_cell)->getDataValidation()->setFormula1($range_name);
    }

    public function setLookup(Writer $writer, $target_cell, $target_sheet_name, $source_row, $range_name)
    {
        $sheet = $writer->getDelegate()->getSheetByName($target_sheet_name);
        $error = '""';
        $sheet->getCell($target_cell)->setValue("=IFERROR(MATCH({$source_row}2,$range_name,0),$error)");
    }

    public function setComment(Writer $writer, $sheet_name, $target, $title, $text)
    {
        $sheet = $writer->getDelegate()->getSheetByName($sheet_name);
        $sheet->getComment($target)->setAuthor('PORTAL CIUDADANO');
        $objCommentRichText = $sheet->getComment($target)->getText()->createTextRun($title);
        $objCommentRichText->getFont()->setBold(true);
        $sheet->getComment($target)->getText()->createTextRun("\r\n");
        $sheet->getComment($target)->getText()->createTextRun($text);
    }
}
