<?php // Code in app/Traits/MyTrait.php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\IWriter;
use PhpOffice\PhpSpreadsheet\Style\Color;


trait ExcelTrait
{
    protected function read(Request $request){

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $path= $request->file('archivo')->getRealPath();
        $ws = $reader->load($path)->getActiveSheet();

        //dd($ws->getRowDimensions());

        $highestRow = $ws->getRowDimensions(); // e.g. 10
        $highestColumn = $ws->getColumnDimensions(); // e.g 'F'

        if(sizeof($highestRow)==0)
            $lastRowIndex = 1000;


        else
        $lastRowIndex = sizeof($highestRow);
        //$sp->setActiveSheetIndex(0);
        $lastColumnString = Coordinate::stringFromColumnIndex(sizeof($highestColumn));
        

        
        $dataArray = $ws->rangeToArray(
        'A2:'.$lastColumnString.$lastRowIndex,     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        TRUE         // Should the array be indexed by cell row and cell column
        );
        //dd($highestColumn->getColumnIndex());

        $last = Arr::last($highestColumn, null, null);
        /*
        * Se eliminan los nulos
        */
        $filtered = Arr::where($dataArray, function ($value, $key) {
            
            return !is_null($value['A']);
        });
       
        return $filtered;

    }

    public function RemitoPrint(Request $request ){


       
       
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load("assets/templates/REMITO_PRINT.xlsx");
    
            
    
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F8", "CONTENEDOR1");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F2", "00000000000");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F4", "10-12-2018");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F15", "1230");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F16", "2185");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F17", "28415");
    
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("B22", "26230");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("D22", "16400");
            $worksheet = $spreadsheet->getActiveSheet()->setCellValue("F22", "42630");
            \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setDecimalSeparator(',');
            \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setThousandsSeparator('.');
           
            
    
    
            //$ewriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$ewriter->save("assets/templates/LSLSL.xlsx");
            //$this->setHeader('VGM.xlsx');
    
            //$ewriter->save('php://output');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
    
            $writer->save('c:\Remito.pdf');
    
            //dd($writer);
            return response()->file('Remito.pdf');
    
            //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);
            //$writer->setUseBOM(true);
    
            //$writer->save("06featuredemo.htm");
            
       
    }
}
