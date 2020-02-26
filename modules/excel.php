<?php

require '../vendor/autoload.php';
if(isset($_POST["submit"])) {
    $data = $_FILES['file']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($data);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $getActiveCell = $worksheet->getActiveCell();
    $rows = [];
    for($row=2;$row<$highestRow;$row++){ 
        $nama = $worksheet->getCell('A'.$row)->getValue();
        $bc_user = $worksheet->getCell('B'.$row)->getValue();
        $divisi = $worksheet->getCell('C'.$row)->getValue();
        $lokasi = $worksheet->getCell('D'.$row)->getValue();
        $nik = $worksheet->getCell('E'.$row)->getValue();

        array_push($rows,array(
            "nama" => $nama,
            "bc_user" => $bc_user,
            "divisi" => $divisi,
            "lokasi" => $lokasi,
            "nik" => $nik
        ));
    }
    echo json_encode($rows);
}