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
        'A1:'.$lastColumnString.$lastRowIndex,     // The worksheet range that we want to retrieve
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
}
