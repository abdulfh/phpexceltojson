<?php

require '../vendor/autoload.php';
if(isset($_POST["submit"])) {
    $data = $_FILES['file']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($data);
    $worksheet = $spreadsheet->getActiveSheet();
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

                if ($key != null && $value != null) {
                    $data[$key] = $value;
                }
            }
        }
        array_push($rows,$data);
    }

    echo json_encode($rows);
}