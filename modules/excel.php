<?php

require '../vendor/autoload.php';
if(isset($_POST["submit"])) {
    $file = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];

    $exts = array('xls', 'xlsx'); 
    if(in_array(end(explode('.', $filename)), $exts)){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($file);
        // $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
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
                        $data[$key] = $value;
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