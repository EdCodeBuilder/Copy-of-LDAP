<?php

namespace App\Modules\Payroll\src\Exports;

use App\Modules\Payroll\src\Models\CertificateCompliance;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CertificateComplianceExportTemplate
{
    /**
     * @var array
     */
    private $collections;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private $file;

    /**
     * @var \App\Modules\Payroll\src\Models\CertificateCompliance;
     */
    private $certificate;

    /**
     * WareHouseExport constructor.
     */
    /* public function __construct(Contractor $contractor, $collection)
    {
        $this->collections = $collection;
        $this->contractor = $contractor;
    } */
    /**
     * CertificateComplianceExportTemplate constructor.
     */
    public function __construct(CertificateCompliance $certificate, $collection)
    {
        $this->collections = $collection;
        $this->certificate = $certificate;
    }

    public function create()
    {
        try {
            $this->file = IOFactory::load( storage_path('app/templates/CERTIFICADO_CUMPLIMIENTO_COLECTIVO.xlsx') );
            //$this->file->setActiveSheetIndex(1);
            $this->file->setActiveSheetIndex(0);
            $this->worksheet = $this->file->getActiveSheet();
            //$this->worksheet->getProtection()->setSheet(true);

            //$this->worksheet->getCell('D13')->setValue(toUpper($this->certificate->supervisor));
            $this->worksheet->getCell('M9')->setValue($this->certificate->entry);
            $this->worksheet->getCell('S9')->setValue($this->certificate->funding_source);
            $this->worksheet->getCell('U9')->setValue('COMPONENTE GASTO '.$this->certificate->component);

            
            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                )
            );
            $this->worksheet->insertNewRowBefore(14, count($this->collections) - 1);
            $i = 13;
            $contador = 1;
            $total_pagar_aux = 0;
            foreach ($this->collections as $key => $collection) {
                
                $this->worksheet->mergeCells("D$i:E$i");
                $this->worksheet->getStyle("C$i:U$i")->applyFromArray($style);
                $this->worksheet->getCell("C$i")->setValue($contador);
                $this->worksheet->getCell("D$i")->setValue(isset($collection['person_name']) ? (string) $collection['person_name'] : null);
                $this->worksheet->getCell("F$i")->setValue(isset($collection['identification']) ? (string) $collection['identification'] : null);
                $this->worksheet->getCell("G$i")->setValue(isset($collection['contract_object']) ? (string) $collection['contract_object'] : null);
                $this->worksheet->getCell("H$i")->setValue(isset($collection['entry']) ? (string) $collection['entry'] : null);
                $this->worksheet->getCell("I$i")->setValue(isset($collection['contract_number']) ? (string) $collection['contract_number'] : null);
                $this->worksheet->getCell("J$i")->setValue(isset($collection['registry_number']) ? (string) $collection['registry_number'] : null);
                $this->worksheet->getCell("K$i")->setValue(date('d/m/Y',strtotime($collection['start_date'])) );
                $this->worksheet->getCell("L$i")->setValue(date('d/m/Y',strtotime($collection['final_date'])) );
                //$this->worksheet->getCell("L$i")->setValue(isset($collection['final_date']) ? (string) $collection['final_date'] : null);
                $this->worksheet->getCell("M$i")->setValue('X');
                $this->worksheet->getCell("O$i")->setValue('X');
                $this->worksheet->getCell("Q$i")->setValue(isset($collection['pago_mensual']) ? (double) $collection['pago_mensual'] : null);
                $this->worksheet->getCell("R$i")->setValue(toUpper($this->certificate->settlement_period));
                $this->worksheet->getCell("S$i")->setValue(isset($collection['dias_trabajados']) ? (int) $collection['dias_trabajados'] : null);
                //$this->worksheet->getCell("T$i")->setValue(isset($collection['total_pagar']) ? (double) $collection['total_pagar'] : null);
                $this->worksheet->getCell("U$i")->setValue(toUpper($this->certificate->supervisor));
                $aux_total = ( (int) $collection['dias_trabajados'] / 30 * (double) $collection['pago_mensual']);
                $this->worksheet->getCell("T$i")->setValue($aux_total);
                $total_pagar_aux += $aux_total;
                //$SUMRANGE = 'D2:D'.$i;
                //$this->worksheet->getCell("T$i")->setValue('=ROUND(Q$i/30*S$i;0)');
                
                //$activeSheet->setCellValue('D'.$i ,'=REDONDEAR(Q$i/30*S$i,0)');
                $i++;
                $contador++;
            }
            $i++;
            $this->worksheet->getCell("T$i")->setValue($total_pagar_aux);
            
            $i += 7;
            $this->worksheet->getCell("E$i")->setValue(toUpper($this->certificate->supervisor));

            /* $security = $this->file->getSecurity();
            $security->setLockWindows(true);
            $security->setLockStructure(true); */
            //$security->setWorkbookPassword($this->contractor->document."-".now()->year);

            /* $x = $i + 2;
            $this->worksheet->getStyle("B7:M$x")
                ->getProtection()
                ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            */
           /*  $protection = $this->worksheet->getProtection();
            $protection->setSheet(true);
            $protection->setSort(true);
            $protection->setInsertRows(true); */
            //$protection->setPassword($this->contractor->document."-".now()->year);
            $this->file->setActiveSheetIndex(0);

            return IOFactory::createWriter($this->file, Excel::XLSX);
        } catch (\Exception $exception) {

        }
    }
}
