<?php

include('../../connection/dbcon.php');

$message = '';
$messagetype = 1; // 1 = error, 2 = success, 3 = warning
$data = [];

if (isset($_POST['partnumber'])) {
    $partnumber = $_POST['partnumber'];

    $query = "delete [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]
        
        WHERE column1 = ?";
    
    $params = array($partnumber);
    
    $result = sqlsrv_query($conn, $query, $params);

    if ($result) {
        echo json_encode(array("message" => 'This part number ' . $partnumber . ' has been deleted.', "type" => 2));
    } else {
        echo json_encode(array("message" => "error", "details" => sqlsrv_errors(), "type" => 1));
    }
    
    sqlsrv_close($conn);
} else {
    // die(json_encode(array("message" => "error", "details" => "Invalid request", "type" => 1)));
    echo json_encode(array("message" => "error", "details" => "Invalid request", "type" => 1));
    die(); // Stop execution if partnumber is not set
}

?>