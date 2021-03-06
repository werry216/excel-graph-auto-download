<?php
    include "C:/xampp/php/Classes/PHPExcel/IOFactory.php";
    require_once "../../../connect.php";
    $fileName = $_POST["fileName"];
    $inputFileName = "../../../files/".$fileName;

    //  Read your Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    //  Loop through each row of the worksheet in turn
    for ($row = 2; $row <= $highestRow; $row++) {
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                        NULL,
                                        TRUE,
                                        FALSE);
        $rowData = $rowData[0];

        $USUARIO = $rowData[0];
        $RECIBIDO = $rowData[1];
        $RECIBIDOType = gettype($RECIBIDO);
        if ($RECIBIDOType==='string') {
            $RECIBIDO = date_create_from_format('d/m/Y', $RECIBIDO);
            $RECIBIDO = $RECIBIDO->format('d/m/Y');
        } else {
            $RECIBIDO = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($RECIBIDO));
        }
        $FECHA = $rowData[2];
        $FechaType = gettype($FECHA);
        if ($FechaType==='string') {
            $FECHA = date_create_from_format('d/m/Y', $FECHA);
            $FECHA = $FECHA->format('d/m/Y');
        } else {
            $FECHA = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($FECHA));
        }
        $FechaData = date_create_from_format('d/m/Y', $FECHA);
        $RECIBIDOData = date_create_from_format('d/m/Y', $RECIBIDO);
        $FECHA = $FechaData->format('d/m/Y');
        $RECIBIDO = $RECIBIDOData->format('d/m/Y');
        $ANO = $rowData[3];
        $MES = $rowData[4];
        $REGION = $rowData[5];
        $FINCA_LOTE = $rowData[6];
        $DE_FINCA = $rowData[7];
        $ESTRATO = $rowData[8];
        $ESTADO = $rowData[9];
        $CD_PLUVIOMETRO = $rowData[10];
        $CANT_LLUVIA = $rowData[11];

        $insert_query = "INSERT INTO rain (USUARIO, RECIBIDO, FECHA, ANO, MES, REGION, FINCA_LOTE,
        DE_FINCA, ESTRATO, ESTADO, CD_PLUVIOMETRO, CANT_LLUVIA)
        VALUES ('$USUARIO', '$RECIBIDO', '$FECHA', '$ANO', '$MES', '$REGION', '$FINCA_LOTE',
        '$DE_FINCA', '$ESTRATO', '$ESTADO', '$CD_PLUVIOMETRO', '$CANT_LLUVIA')";
        $confirm = $conn->query($insert_query);
    }
    echo json_encode(array("success"=>true));
?>
