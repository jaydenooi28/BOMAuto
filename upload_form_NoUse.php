<?php
include('../../connection/dbcon.php');
include('../../assets/plugins/PhpSpreadsheet/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Reader\Exception;
error_reporting(E_ERROR | E_PARSE);

$highestRow = 0;
$targetDirectory = '/upload';
// $SelectPartNo='';
// $ChildPartNo='';
// $PartNoArray = array();
// $ChildItemArray = array();

$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$selectModel = isset($_POST['model']) ? $_POST['model'] : '';
// $getRev = isset($_POST['rev']) ? $_POST['rev'] : '';
// $getRev = intval(ltrim($getRev, '0'));
$getModel='';
// $getRev ='1';



if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0777, true);
}

// Check if the file was uploaded successfully
if (isset($_FILES['excel-file']) && $_FILES['excel-file']['error'] == UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['excel-file'];
    // Get the original file name
    $originalFileName = $uploadedFile['name'];
        // Extract the file extension
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);


    // Load the uploaded Excel file
    $excel = IOFactory::load($_FILES['excel-file']['tmp_name']);

    $timestamp = date('_Ymd_His');
    $originalFileName = pathinfo($_FILES['excel-file']['name'], PATHINFO_FILENAME);
    $fileExtension = pathinfo($_FILES['excel-file']['name'], PATHINFO_EXTENSION);
    $newFileName = $originalFileName . $timestamp . '.' . $fileExtension;
    $tempFile = $_FILES['excel-file']['tmp_name']; // Define $tempFile
    $targetFile = $targetDirectory . $newFileName; // Define $targetFile
    
    // Start a database transaction
    sqlsrv_begin_transaction($conn);
    $Source="";
    try {
        move_uploaded_file($tempFile, $targetFile);
        // $highestRow = count($dataArray)-1;

	
        if (isset($_FILES['excel-file']) && $_FILES['excel-file']['error'] == UPLOAD_ERR_OK) {
            $worksheet = $excel->getActiveSheet();
            
            $highestRow = $worksheet->getHighestRow();

            $sqlCheck = "SELECT COUNT(*) AS count FROM [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART] WHERE [column1] = ?";
            $sqlInsert = "INSERT INTO [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]
            ([column1],[userid]) 
           	VALUES (?,?)";

            if ($stmt === false) {
                die(json_encode(array("message" => 'Error: Unable to prepare INSERT SQL query.', "type" => 1)));
            }
            
          
            

          
            for ($row = 1; $row <= $highestRow; $row++) {
                // Initialize the parameter array for each row
                $params = array();
                $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $params[] = $value !== null ? (string) $value : ""; 
                if (trim($params[0]) === "") {
                    
                    continue;
                }
            
                // Check if the item already exists in the database
                $checkParams = array($params[0]);
          
                $checkStmt = sqlsrv_query($conn, $sqlCheck, $checkParams);
                if ($checkStmt === false) {
                    $errors = sqlsrv_errors();
                    die(json_encode(array("message" => 'Error: Unable to execute SELECT SQL query.', "type" => 1, "error" => $errors)));
                }
            
                $rowCheck = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);
                if ($rowCheck['count'] == 0) {
                    $params[] = "";
            
                
            
                    // Execute the insert query
                    if (!sqlsrv_query($conn, $sqlInsert, $params)) {
                        $errors = sqlsrv_errors();
                        die(json_encode(array("message" => 'Error: Unable to execute INSERT SQL query.', "type" => 1, "error" => $errors)));
                    }
                    
                } else {
   
                }
                sqlsrv_free_stmt($checkStmt);
            }
        }
        

        sqlsrv_commit($conn);
        sqlsrv_close($conn);

        die(json_encode(array("message" => 'Data from Excel file has been updated.', "type" => 2)));

    } catch (Exception $e) {
        // Rollback the transaction on any exception
        sqlsrv_rollback($conn);
        
        // Capture detailed SQL error information
        $errors = sqlsrv_errors();
        $errorMessage = "Error executing insert query2: " . $errors[0]['message'];
        
        die(json_encode(array("message" => $errorMessage, "type" => 1)));
    }

} else {
    die(json_encode(array("message" => 'Please select Excel File.', "type" => 1)));
}
?>