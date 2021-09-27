<?php

namespace App\Traits;

use Maatwebsite\Excel\Sheet;

trait AppendHeaderToExcel
{
    public function setHeader(Sheet $sheet, $title, $titleCellMerge, $finalRow, $insertNumRows = 6)
    {
        $sheet->getDelegate()->insertNewRowBefore(1, $insertNumRows);
        $sheet->getDelegate()->mergeCells($titleCellMerge);
        $sheet->getDelegate()->getCell("A1")
            ->setValue(toUpper($title))
            ->getStyle()
            ->getFont()
            ->setSize(24)
            ->setBold(true);
        $sheet->getDelegate()->getCell('A1')
            ->getStyle()
            ->getAlignment()
            ->setVertical('center')
            ->setHorizontal('left');
        $sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
        $sheet->getDelegate()->getCell("A3")
            ->setValue('GENERADO POR:')
            ->getStyle()
            ->getFont()
            ->setSize(14)
            ->setBold(true);
        $user = auth('api')->user()->full_name ?? 'SISTEMA';
        $sheet->getDelegate()->getCell("B3")
            ->setValue($user)
            ->getStyle()
            ->getFont()
            ->setSize(14)
            ->setBold(true);
        $sheet->getDelegate()->getCell("A4")
            ->setValue('FECHA DE CREACIÓN:')
            ->getStyle()
            ->getFont()
            ->setSize(14)
            ->setBold(true);
        $sheet->getDelegate()->getCell("B4")
            ->setValue(now()->format('Y-m-d H:i:s'))
            ->getStyle()
            ->getFont()
            ->setSize(14)
            ->setBold(true);

        foreach (range('A', 'Z') as $col) {
            $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A', 'S') as $col) {
            $sheet->getDelegate()->getColumnDimension("A$col")->setAutoSize(true);
        }

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

        $row = $sheet->getDelegate()->getHighestDataRow($finalRow);
        $rowIndex = $insertNumRows + 1;
        $sheet->getDelegate()->getStyle("A$rowIndex:{$finalRow}{$row}")
            ->applyFromArray($styleArray);
        $cells = "A$rowIndex:{$finalRow}{$rowIndex}";
        $sheet->getDelegate()
            ->getStyle($cells)
            ->getFont()
            ->setSize(14)
            ->setBold(true)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getDelegate()->getRowDimension(7)->setRowHeight(50);
        $sheet->getDelegate()->getStyle($cells)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('594d95');
    }
}
