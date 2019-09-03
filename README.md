# remito

Es una aplicacion diseñada para VACLOG-WD que permite generar remitos desde archivos excel especificos

es temporal para uso interno de VACLOG WD SAS.






Agregar al .env

PHPOFFICE_TEMP_DIR=C:\<directorio temporal dedicado para remito>, porque la aplicacion lo borra completo al utilizar el MPdf, al escribir tiene que usar un directorio temporal dedicado para luego borrarlo.

tambien incorporar a la libreria

el MPDF

..\remito\vendor\phpoffice\phpspreadsheet\src\PhpSpreadsheet\Writer\Pdf\Mpdf.php

que se subio al github con un cambio para que utilize el PHPOFFICE_TEMP_DIR.



