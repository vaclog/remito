<?php // Code in app/Traits/MyTrait.php



namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

use Mpdf\Mpdf as NewPdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\IWriter;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;


trait ExcelTrait
{
    protected function read(Request $request){

        //
        /* Se cambio por CSV despues de la implementacion de Orien
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        */

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

        $client_id = $request->input('client_id');
        $armado = $request->input('armado');
        $path= $request->file('archivo')->getRealPath();
        
        $encoding = mb_detect_encoding(file_get_contents($request->file('archivo')),
        // example of a manual detection order
       'ASCII,UTF-8,ISO-8859-15');

        $reader->setInputEncoding($encoding);
        
        $ws = $reader->load($path)->getActiveSheet();


        
       
        //dd($ws->getRowDimensions());

        $highestRow = $ws->getRowDimensions(); // e.g. 10
        $highestColumn = ['S']; // e.g 'F'

        if(sizeof($highestRow) <= 1){
            $lastRowIndex = 1000;
        }
        else{
            $lastRowIndex = sizeof($highestRow);
        }
            
        
        //$sp->setActiveSheetIndex(0);
        $lastColumnString = 'S';//Coordinate::stringFromColumnIndex(sizeof($highestColumn));
        

        
        $dataArray = $ws->rangeToArray(
        'A3:'.$lastColumnString.$lastRowIndex,     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        TRUE         // Should the array be indexed by cell row and cell column
        );
        //dd($highestColumn->getColumnIndex());

        //$last = Arr::last($highestColumn, null, null);
        /*
        * Se eliminan los nulos
        */
        $filtered = Arr::where($dataArray, function ($value, $key) {
            
            return !is_null($value['F']);
        });

        $filtered_by_Armado = Arr::where($filtered, function ($value, $key) use ($request){
            return ($value['A'] == $request->input('armado'));
        });
       
        return $filtered_by_Armado;

    }

    public function RemitoPrint(Request $request, $remito ){
            
            set_time_limit(0);
           
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $sp = $reader->load("assets/templates/REMITO_PRINT2.xlsx");



            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('assets/VACLOG.jpg');
            //$drawing->setCoordinates('A2');
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);
            $drawing->setHeight(100);

            $drawing->setWorksheet($sp->getActiveSheet());
    
            $r = $remito;

            $numero_remito = str_pad( $r->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $r->numero_remito, 8, "0", STR_PAD_LEFT);
            $fecha_remito = (strtotime($r->fecha_remito)> 0)?date_format(date_create($r->fecha_remito), 'd/m/Y'):'' ;
            $destinatario = $r->customer->nombre;
            $cuit = $r->customer->cuit;
            $domicilio = $r->calle;
            $localidad = $r->localidad;
            $provincia = $r->provincia;
            $transporte = $r->transporte;
            $chofer = $r->conductor;
            $patente = $r->patente;
            
            $ws = $sp->getActiveSheet()->setCellValue("E2", $numero_remito);
            
            $ws = $sp->getActiveSheet()->setCellValue("F3", $fecha_remito);
            $ws = $sp->getActiveSheet()->setCellValue("B7", $destinatario);
            $ws = $sp->getActiveSheet()->setCellValue("B8", $domicilio);
            $ws = $sp->getActiveSheet()->setCellValue("E8", $localidad);
            $ws = $sp->getActiveSheet()->setCellValue("G8", $provincia);
            $ws = $sp->getActiveSheet()->setCellValue("B9", $cuit);

            $ws = $sp->getActiveSheet()->setCellValue("B10", $transporte);
            $ws = $sp->getActiveSheet()->setCellValue("B11", $chofer);
            $ws = $sp->getActiveSheet()->setCellValue("F11", $patente);

              
            \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setDecimalSeparator(',');
            \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setThousandsSeparator('.');
            $i = 0;
            $page = 0;
            $fromrow = 14;
            foreach($r->articulos as $a){
                $i++;
                $codigo = $a->codigo;
                $descripcion = $a->descripcion;
                $marca = $a->marca;
                
                $ws = $sp->getActiveSheet()->insertNewRowBefore(($fromrow + $i), 1);
                $ws = $sp->getActiveSheet()->mergeCells('A'.($fromrow + $i).':B'.($fromrow + $i));
                //$ws = $sp->getActiveSheet()->mergeCells('C'.($fromrow + $i).':G'.($fromrow + $i));

                $detalle = $codigo.'    '.$descripcion.'    '.$marca;
                $cantidad = $a->cantidad;
                $ws = $sp->getActiveSheet()->setCellValue("A".($fromrow + $i), $cantidad);
                $ws = $sp->getActiveSheet()->setCellValue("C".($fromrow + $i), $codigo);
                $ws = $sp->getActiveSheet()->setCellValue("E".($fromrow + $i), $descripcion);
                $ws = $sp->getActiveSheet()->setCellValue("G".($fromrow + $i), $marca);




            }

            $ws = $sp->getActiveSheet()->insertNewRowBefore(($fromrow + $i + 5), 9);

            $style = [
                'font' => [
                    'size' => 18,
                    'bold' => false,
                    // 'color'=> \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE,
                ]
            ];
            
            $sp->getActiveSheet()->getStyle('A'.($fromrow + $i + 6).':G'.($fromrow + $i + 6))->applyFromArray($style);

            $firma = 'FIRMA';
            $aclaracion = 'ACLARACION';
            $dni = 'DNI';
            $fecha = 'FECHA';
            $ws = $sp->getActiveSheet()->setCellValue("A".($fromrow + $i + 6), $firma);
            $ws = $sp->getActiveSheet()->setCellValue("C".($fromrow + $i + 6), $aclaracion);
            $ws = $sp->getActiveSheet()->setCellValue("D".($fromrow + $i + 6), $dni);
            $ws = $sp->getActiveSheet()->setCellValue("G".($fromrow + $i + 6), $fecha);




            
            $this->setHeader('Remito.pdf');
    
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($sp);
            
            $writer->save('php://output');
           
            
       
    }

    public function RemitoPrintManager( Request $request, $remito){
        switch ($remito->client_id){
            case 1: 
                $this->RemitoPrint($request, $remito);
                break;
            case 2:
                $this->RemitoPrintOrien($request, $remito);
                break;
            
        }

    }

    public function RemitoPrintOrien(Request $request, $remito ){
            
        set_time_limit(0);
       
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $sp = $reader->load("assets/templates/REMITO_ORIEN_EXCEL_2.xlsx");

        $sp->setActiveSheetIndex(0);


        $r = $remito;

        $numero_remito = str_pad( $r->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $r->numero_remito, 8, "0", STR_PAD_LEFT);
        $fecha_remito = (strtotime($r->fecha_remito)> 0)?date_format(date_create($r->fecha_remito), 'd/m/Y'):'' ;
        $destinatario = $r->customer->nombre;
        $cuit = $r->customer->cuit;
        $domicilio = $r->calle;
        $localidad = $r->localidad;
        $provincia = $r->provincia;
        $transporte = $r->transporte;
        $chofer = $r->conductor;
        $patente = $r->patente;
        
        $ws = $sp->getActiveSheet()->setCellValue("E2", $numero_remito);
        
        $ws = $sp->getActiveSheet()->setCellValue("F3", $fecha_remito);
        $ws = $sp->getActiveSheet()->setCellValue("B7", $destinatario);
        $ws = $sp->getActiveSheet()->setCellValue("B8", $domicilio);
        $ws = $sp->getActiveSheet()->setCellValue("E8", $localidad);
        $ws = $sp->getActiveSheet()->setCellValue("G8", $provincia);
        $ws = $sp->getActiveSheet()->setCellValue("B9", $cuit);

        $ws = $sp->getActiveSheet()->setCellValue("B10", $transporte);
        $ws = $sp->getActiveSheet()->setCellValue("B11", $chofer);
        $ws = $sp->getActiveSheet()->setCellValue("F11", $patente);

          
        \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setDecimalSeparator(',');
        \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setThousandsSeparator('.');
        $i = 0;
        $page = 0;
        $fromrow = 14;
        foreach($r->articulos as $a){
            $i++;
            $codigo = $a->codigo;
            $descripcion = $a->descripcion;
            $marca = $a->marca;
            
            $ws = $sp->getActiveSheet()->insertNewRowBefore(($fromrow + $i), 1);
            $ws = $sp->getActiveSheet()->mergeCells('A'.($fromrow + $i).':B'.($fromrow + $i));
            //$ws = $sp->getActiveSheet()->mergeCells('C'.($fromrow + $i).':G'.($fromrow + $i));

            $detalle = $codigo.'    '.$descripcion.'    '.$marca;
            $cantidad = $a->cantidad;
            $ws = $sp->getActiveSheet()->setCellValue("A".($fromrow + $i), $cantidad);
            $ws = $sp->getActiveSheet()->setCellValue("C".($fromrow + $i), $codigo);
            $ws = $sp->getActiveSheet()->setCellValue("E".($fromrow + $i), $descripcion);
            $ws = $sp->getActiveSheet()->setCellValue("G".($fromrow + $i), $marca);




        }

        $ws = $sp->getActiveSheet()->insertNewRowBefore(($fromrow + $i + 5), 9);

        $style = [
            'font' => [
                'size' => 18,
                'bold' => false,
                // 'color'=> \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE,
            ]
        ];
        
        $sp->getActiveSheet()->getStyle('A'.($fromrow + $i + 6).':G'.($fromrow + $i + 6))->applyFromArray($style);

        $firma = 'FIRMA';
        $aclaracion = 'ACLARACION';
        $dni = 'DNI';
        $fecha = 'FECHA';
        $ws = $sp->getActiveSheet()->setCellValue("A".($fromrow + $i + 6), $firma);
        $ws = $sp->getActiveSheet()->setCellValue("C".($fromrow + $i + 6), $aclaracion);
        $ws = $sp->getActiveSheet()->setCellValue("D".($fromrow + $i + 6), $dni);
        $ws = $sp->getActiveSheet()->setCellValue("G".($fromrow + $i + 6), $fecha);
        
        
        
        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($sp);
        
        // $writer->save('php://output');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sp);
        $this->setHeaderXlsx('Remito.xlsx');
        $writer->save('php://output');
       
        
   
}

    public function setHeaderXlsx($filename){
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
    }
    public function setHeaderCsv($filename){
        // Redirect output to a client’s web browser (Xlsx)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Type: application/csv');
        //header('Content-Type: text/csv');
        
        header('Content-Type: application/vnd.msexcel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
    }


    public function setHeader($filename){
        // Redirect output to a client’s web browser (Xlsx)
       // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment;filename="'.$filename.'"');

        header("Content-type:application/pdf");

        // It will be called downloaded.pdf
        header('Content-Disposition:attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
    }

    


    public function toExcelOrien($remito){
        $sp = new Spreadsheet();

        $numero_remito = str_pad( $remito->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $remito->numero_remito, 8, "0", STR_PAD_LEFT);
        $sp->getProperties()
           ->setCreator('VACLOG WD')
           ->setTitle('RT-ORIEN-'.$numero_remito);
        
        $sp->setActiveSheetIndex(0);
        $i = 1;
        foreach ($remito->articulos as $key => $art) {
            # code...
            $registro = [  $numero_remito, 
                            date_format(date_create($remito->fecha_remito), 'Ymd'),
                            'EMP00', 'FC|OEP',
                            $remito->referencia,
                            $remito->customer->codigo,
                            '1',
                            $art->codigo,
                            $art->cantidad];
            $sp->getActiveSheet()->fromArray($registro ,null, 'A'.$i);
            $i++;
        }
        

        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($sp);
        $writer->setDelimiter(';');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $archivo = 'RT-ORIEN-'.$numero_remito.'.csv';
        //$this->setHeaderCsv($archivo);
        //$writer->save('php://output');

        
        // return response((string) $writer->save('php://output'), 200, [
        //     'Content-Type' => 'text/csv',
        //     'Content-Transfer-Encoding' => 'binary',
        //     'Content-Disposition' => 'attachment; filename="'.$archivo.'"',
        // ]);


        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$archivo.'"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
       

        

        
//        $writer->save($archivo);
       
    }

    public function test1($remito){
        $mPDF = new NewPdf(
            [
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

        
        $mPDF->SetHTMLHeader(
        '  <table style="width: 100%; font-family: Arial, Helvetica, sans-serif;
        ">
            <tr>
                <td rowspan="2" style="width: 40%">
                    <img alt="" height="98" src="/assets/VACLOG.jpg" width="40%" /></td>
                <td  style="width: 10px;text-align: center;
                    font-size: x-large;
                    border-style: solid;
                    border-width: 1px">R</td>
                <td style="width: 77px;text-align: center;
                    font-size: x-large;
                    
                    border-width: 1px"><strong>REMITO</strong></td>
                <td style="font-size: small;
                    text-align: center">DOCUMENTO NO VÁLIDO COMO FACTURA</td>
            </tr>
            <tr>
                <td style="width: 10px;text-align: center;">Cod 91</td>
                <td class="auto-style1" style="width: 77px">&nbsp;</td>
                <td class="auto-style1">&nbsp;</td>
            </tr>
       
            <tr>
                <td style="width: 422px">&nbsp;</td>
                <td style="width: 112px">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    '  
        
        
        
        );

        $mPDF->SetFooter('|Printed using mPDF|');
        
        $mPDF->SetColumns(2);
        $mPDF->WriteHTML('Some text...');
        $mPDF->AddColumn();
        $mPDF->WriteHTML('Next column...');

        $mPDF->Output();

    }
}
