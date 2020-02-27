<?php

require '../vendor/autoload.php';
require 'function.php';

if(isset($_POST["submit"])) {
    /**
     * Call Check String If JSON
     */
    $function = new Helper();

    $file = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];

    $exts = array('xls', 'xlsx'); 
    if(in_array(end(explode('.', $filename)), $exts)){
        /**
        * Call PHP SpreadSheet Function
        */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $getActiveCell = $worksheet->getActiveCell();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        $rows = [];
        for($row=1;$row<$highestRow;$row++){ 
            $data = [];
            for ($col = 1; $col <= $highestColumnIndex;$col++) {
                $key = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $value = $worksheet->getCellByColumnAndRow($col, $row+1)->getValue();

                if ($key != null) {
                    if (is_bool($value) && $value == false) {
                        $data[$key] = false;
                    }else{
                        if ($function->isJSON($value)) {
                            $data[$key] = json_decode($value);
                        }else{
                            $data[$key] = $value;
                        }
                    }
                }
            }
            array_push($rows,$data);
        }

        echo json_encode($rows);
    }else{
        echo "File Type Not Allowed !";
    }
}