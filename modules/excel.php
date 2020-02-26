<?php

require '../vendor/autoload.php';
if(isset($_POST["submit"])) {
    $file = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];

    $exts = array('xls', 'xlsx'); 
    if(in_array(end(explode('.', $filename)), $exts)){
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setReadDataOnly(true); 
        $worksheet->setReadEmptyCells(false);
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $getActiveCell = $worksheet->getActiveCell();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        $rows = [];
        for($row=1;$row<$highestRow;$row++){ 
            $data = [];
            for ($col = 1; $col <= $highestColumnIndex;$col++) {
                if (!empty($row)) {
                    $key = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                    $value = $worksheet->getCellByColumnAndRow($col, $row+1)->getValue();

                    if ($key != null) {
                        $data[$key] = $value;
                    }
                }
            }
            if (!empty($data)) {
                array_push($rows,$data);
            }
        }

        echo json_encode($rows);
    }else{
        echo "File Type Not Allowed !";
    }
}