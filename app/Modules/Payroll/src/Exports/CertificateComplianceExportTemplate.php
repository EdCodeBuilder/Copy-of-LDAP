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
     * @var array
     */
    private $collectionSupervisorSupport;

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
    public function __construct(CertificateCompliance $certificate, $collection, $supervisorSupportList)
    {
        $this->collections = $collection;
        $this->certificate = $certificate;
        $this->collectionSupervisorSupport = $supervisorSupportList;

    }

    public function create()
    {
        try {
            $this->file = IOFactory::load( storage_path('app/templates/CERTIFICADO_CUMPLIMIENTO_COLECTIVO_V2.xlsx') );
            //$this->file->setActiveSheetIndex(1);
            $this->file->setActiveSheetIndex(0);
            $this->worksheet = $this->file->getActiveSheet();
            //$this->worksheet->getProtection()->setSheet(true);

            //$this->worksheet->getCell('D13')->setValue(toUpper($this->certificate->supervisor));
            $this->worksheet->getCell('Q9')->setValue($this->certificate->entry);
            $this->worksheet->getCell('W9')->setValue($this->certificate->funding_source);
            //$this->worksheet->getCell('U9')->setValue('COMPONENTE GASTO '.$this->certificate->component);

            
            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                )
            );         

            $this->worksheet->insertNewRowBefore(14, count($this->collections) - 1);
            $i = 13;
            $contador = 1;
            $total_pagar_aux = 0;

            $collectionAux = collect([]); 
            $countByIdentification = $this->collections->countBy('identification');
            foreach ($this->collections as $key => $collection) {
                
                $this->worksheet->mergeCells("D$i:E$i");
                $this->worksheet->getStyle("C$i:U$i")->applyFromArray($style);
                //$this->worksheet->getCell("C$i")->setValue($contador);
                //$contador++;

                //combinar celdas por identificación
                if(! $collectionAux->contains( $collection['identification'] ) ){
                    $y = $i + $countByIdentification["{$collection['identification']}"];
                    $y--;
                    $this->worksheet->getCell("C$i")->setValue($contador);
                    $this->worksheet->mergeCells("C$i:C$y");


                    /* $this->worksheet->getCell("D$i")->setValue(isset($collection['person_name']) ? (string) $collection['person_name'] : null);
                    $this->worksheet->mergeCells("D$i:D$y"); */
                    
                    $contador++;
                    $collectionAux->push($collection['identification']);
                }

                $this->worksheet->getCell("D$i")->setValue(isset($collection['person_name']) ? (string) $collection['person_name'] : null);
                $this->worksheet->getCell("F$i")->setValue(isset($collection['identification']) ? (string) $collection['identification'] : null);
                $this->worksheet->getCell("G$i")->setValue(isset($collection['contract_object']) ? (string) $collection['contract_object'] : null);
                $this->worksheet->getCell("H$i")->setValue(isset($collection['entry']) ? (string) $collection['entry'] : null);
                $this->worksheet->getCell("I$i")->setValue(isset($collection['contract_number']) ? (string) $collection['contract_number'] : null);
                $this->worksheet->getCell("J$i")->setValue(isset($collection['registry_number']) ? (string) $collection['registry_number'] : null);

                $this->worksheet->getCell("K$i")->setValue(isset($collection['source']) ? (string) $collection['source'] : null);
                $this->worksheet->getCell("L$i")->setValue(isset($this->certificate->component) ? (string) $this->certificate->component : null);

                $this->worksheet->getCell("M$i")->setValue(isset($collection['pmr']) ? (string) $collection['pmr'] : null);
                $this->worksheet->getCell("N$i")->setValue(isset($collection['position']) ? (string) $collection['position'] : null);

                $this->worksheet->getCell("O$i")->setValue(date('d/m/Y',strtotime($collection['start_date'])) );
                $this->worksheet->getCell("P$i")->setValue(date('d/m/Y',strtotime($collection['final_date'])) );
                //$this->worksheet->getCell("L$i")->setValue(isset($collection['final_date']) ? (string) $collection['final_date'] : null);
                $this->worksheet->getCell("Q$i")->setValue('X');
                $this->worksheet->getCell("R$i")->setValue(' ');
                $this->worksheet->getCell("S$i")->setValue('X');
                $this->worksheet->getCell("T$i")->setValue(' ');
                $this->worksheet->getCell("U$i")->setValue(isset($collection['pago_mensual']) ? (double) $collection['pago_mensual'] : null);
                //$this->worksheet->getCell("R$i")->setValue(toUpper($this->certificate->settlement_period));
                $this->worksheet->getCell("V$i")->setValue("{$collection['startPeriod']} AL {$collection['finalPeriod']}"); 
                $this->worksheet->getCell("W$i")->setValue(isset($collection['dias_trabajados']) ? (int) $collection['dias_trabajados'] : null);
                //$this->worksheet->getCell("T$i")->setValue(isset($collection['total_pagar']) ? (double) $collection['total_pagar'] : null);
                $aux_total = ( (int) $collection['dias_trabajados'] / 30 * (double) $collection['pago_mensual']);
                $total_pagar_aux += $aux_total;
                $this->worksheet->getCell("X$i")->setValue($aux_total);
                $this->worksheet->getCell("Y$i")->setValue(toUpper($this->certificate->supervisor));

                //$this->worksheet->setCellValue('T$i','=ROUND(Q$i/30*S$i;0)');
                //$this->worksheet->getCell("T$i")->setValue('=ROUND(Q$i/30*S$i;0)');
                /* $this->worksheet->setCellValueExplicit(
                    "T$i",
                    '=ROUND(Q$i/30*S$i;0)',
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC
                ); */
                $i++;
            }
            $i++;
            
            //$collection->sum('pages');
            $this->worksheet->getCell("X$i")->setValue($total_pagar_aux);
            

            //$i += 7;
            $i += 4;
            $filaIdentificacion = $i + 1;
            $filaCargo = $i + 2;

            $this->worksheet->getCell("E$i")->setValue(toUpper($this->certificate->supervisor));
            $this->worksheet->getCell("E$filaIdentificacion")->setValue(toUpper($this->certificate->component));
            $this->worksheet->getCell("E$filaCargo")->setValue(toUpper($this->certificate->profession));

            $auxFilaDosApoyo = $i + 7;
            $filaIdentificacionDos = $auxFilaDosApoyo+ 1;
            $filaCargoDos = $auxFilaDosApoyo + 2;
            if(count($this->collectionSupervisorSupport) > 0){
                $contAux = 1;
                foreach ($this->collectionSupervisorSupport as $key => $item) {
                    switch ($contAux) {
                        case 1:
                            $this->worksheet->getCell("G$i")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("G$filaIdentificacion")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("G$filaCargo")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;
                        case 2:
                            $this->worksheet->getCell("K$i")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("K$filaIdentificacion")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("K$filaCargo")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;
                        case 3:
                            $this->worksheet->getCell("O$i")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("O$filaIdentificacion")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("O$filaCargo")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;

                        case 4:
                            $this->worksheet->getCell("E$auxFilaDosApoyo")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("E$filaIdentificacionDos")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("E$filaCargoDos")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            
                            break;
                        case 5:
                            $this->worksheet->getCell("G$auxFilaDosApoyo")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("G$filaIdentificacionDos")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("G$filaCargoDos")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;
                        case 6:
                            $this->worksheet->getCell("K$auxFilaDosApoyo")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("K$filaIdentificacionDos")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("K$filaCargoDos")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;
                        case 7:
                            $this->worksheet->getCell("O$auxFilaDosApoyo")->setValue(isset($item['name']) ? (string) $item['name'] : null);
                            $this->worksheet->getCell("O$filaIdentificacionDos")->setValue(isset($item['numberIdentification']) ? (string) $item['numberIdentification'] : null);
                            $this->worksheet->getCell("O$filaCargoDos")->setValue(isset($item['profession']) ? (string) $item['profession'] : null);
                            break;
                        default:
                            # code...
                            break;
                    }
                    $contAux ++;
                }
            }
            
            $this->worksheet->getStyle("F13")//A13:U$i
                            ->getProtection()
                            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            
            /* $security = $this->file->getSecurity();
            $security->setLockWindows(true);
            $security->setLockStructure(true); 
            $security->setWorkbookPassword($this->contractor->document."-".now()->year);
            */
            //$x = $i + 2;
            
            /* $protection = $this->worksheet->getProtection();
            $protection->setSheet(true);
            $protection->setSort(true);
            $protection->setInsertRows(true);
            $protection->setPassword($this->contractor->document."-".now()->year);
            $this->file->setActiveSheetIndex(0);*/
            return IOFactory::createWriter($this->file, Excel::XLSX);
        } catch (\Exception $exception) {

        }
    }
}
