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
    $trimmedFileName = trim($originalFileName);
    $lastDotPosition = strrpos($trimmedFileName, '.');
    $fileNameWithoutExtension = substr($trimmedFileName, 0, $lastDotPosition);
    

    if ($mode === "Edit" && $fileNameWithoutExtension !==$selectModel) {
        die(json_encode(array("message" => 'Error : Excel file name (' . $fileNameWithoutExtension . ') not match with selected model (' . $selectModel . ')', "type" => 1)));
    }
        // Extract the file extension
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // if (strtolower($fileExtension) !== "xlsx") {
    //     die(json_encode(array("message" => 'Error: Invalid excel file format.', "type" => 1)));
    // }
    // Load the uploaded Excel file
    $excel = IOFactory::load($_FILES['excel-file']['tmp_name']);

    // Get the value from cell B2
    $getModel = $excel->getActiveSheet()->getCell('B2')->getValue();
    if (!in_array(strtolower($fileExtension), ["xlsx", "xls"])) {
        die(json_encode(array("message" => 'Error: Invalid excel file format.', "type" => 1)));
    }

    

    try {
       
                $sheet = $excel->getSheet(0);
                $dataArray = $sheet->toArray();

        if ($mode === "btnAdd") {

            $BomQuery = "SELECT TOP 1 * FROM [erplivedb_customer].[dbo].[TMSMT_BOM] with (nolock) WHERE model = ?";
        
            $params = array($getModel);
            // $params = array($getModel, $getRev);
            $checkBom = sqlsrv_query($conn, $BomQuery, $params);
        
            $row = sqlsrv_fetch_array($checkBom, SQLSRV_FETCH_ASSOC);
        
            if ($row !== false) {
                if (isset($row['model'])) {
                    die(json_encode(array("message" => 'Error: The BOM ('. $getModel .') already exists; Please select re-upload to update the BOM!', "type" => 1)));
                }

            }
        }

        function filterRows($row, $rowNumber) {
            // Specify the columns to check for emptiness
            $columnsToCheck = [0, 1, 2, 3, 4, 5, 6, 7]; // Adjust as needed
            // Iterate through the specified columns
            foreach ($columnsToCheck as $index) {
                // If any column is empty, die with a message indicating the row number and column index
                if (empty($row[$index])) {
                    die(json_encode(array("message" => "Row " . $rowNumber . ", Column " . ($index + 1) . " cannot be empty!", "type" => 1)));
                }
            }
            // If all specified columns are not empty, return true
            return true;
        }
        

     
        $column1Values = array_map('strval', array_column($dataArray, 0));    
        $column2Values = array_map('strval', array_column($dataArray, 1));
        $column3Values = array_map('strval', array_column($dataArray, 2));
        $column4Values = array_map('strval', array_column($dataArray, 3));
        $column5Values = array_map('strval', array_column($dataArray, 4));
        $column6Values = array_map('strval', array_column($dataArray, 5));
        $column7Values = array_map('strval', array_column($dataArray, 6));
        $column8Values = array_map('strval', array_column($dataArray, 7));
        $column9Values = array_map('strval', array_column($dataArray, 8));
        $column10Values = array_map('strval', array_column($dataArray, 9));
        $column11Values = array_map('strval', array_column($dataArray, 10));
        $column12Values = array_map('strval', array_column($dataArray, 11));
        $column13Values = array_map('strval', array_column($dataArray, 12));
        $column14Values = array_map('strval', array_column($dataArray, 13));
        $column15Values = array_map('strval', array_column($dataArray, 14));
        $column16Values = array_map('strval', array_column($dataArray, 15));
        $column17Values = array_map('strval', array_column($dataArray, 16));
        $column18Values = array_map('strval', array_column($dataArray, 17));
        $column19Values = array_map('strval', array_column($dataArray, 18));
        $column20Values = array_map('strval', array_column($dataArray, 19));
        $column21Values = array_map('strval', array_column($dataArray, 20));
        $column22Values = array_map('strval', array_column($dataArray, 21));
        $column23Values = array_map('strval', array_column($dataArray, 22));
        $column24Values = array_map('strval', array_column($dataArray, 23));
        $column25Values = array_map('strval', array_column($dataArray, 24));
        $column26Values = array_map('strval', array_column($dataArray, 25));
        $column27Values = array_map('strval', array_column($dataArray, 26));
        $column28Values = array_map('strval', array_column($dataArray, 27));
        $column29Values = array_map('strval', array_column($dataArray, 28));
        $column30Values = array_map('strval', array_column($dataArray, 29));
        $column31Values = array_map('strval', array_column($dataArray, 30));
        $column32Values = array_map('strval', array_column($dataArray, 31));
        $column33Values = array_map('strval', array_column($dataArray, 32));
        $column34Values = array_map('strval', array_column($dataArray, 33));
        $column35Values = array_map('strval', array_column($dataArray, 34));
		$column36Values = array_map('strval', array_column($dataArray, 35));
        $column37Values = array_map('strval', array_column($dataArray, 36));
        $column38Values = array_map('strval', array_column($dataArray, 37));
        $column39Values = array_map('strval', array_column($dataArray, 38));
        $column40Values = array_map('strval', array_column($dataArray, 39));
        $column41Values = array_map('strval', array_column($dataArray, 40));
        $column42Values = array_map('strval', array_column($dataArray, 41));
        $column43Values = array_map('strval', array_column($dataArray, 42));
       

    } catch (PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die(json_encode(array("message" => 'Error: Invalid Excel file format! ' . $e->getMessage() . '.', "type" => 1)));
    }

    // echo date("Ymd_His.U");
    // Generate a timestamp with the format '_yyyyMMdd_HHmmssuu' (year, month, day, hour, minute, seconds, microseconds)
    $timestamp = date('_Ymd_His');
    // $timestamp = substr($timestamp, 0, 18);

    // Extract the original file name without the extension
    $originalFileName = pathinfo($_FILES['excel-file']['name'], PATHINFO_FILENAME);

    // Extract the file extension
    $fileExtension = pathinfo($_FILES['excel-file']['name'], PATHINFO_EXTENSION);

    // Create the new file name with the timestamp added before the original file name
    $newFileName = $originalFileName . $timestamp . '.' . $fileExtension;

    $tempFile = $_FILES['excel-file']['tmp_name']; // Define $tempFile
    $targetFile = $targetDirectory . $newFileName; // Define $targetFile
    
    // Start a database transaction
    sqlsrv_begin_transaction($conn);
    $Source="";
    try {
        // Move the uploaded file
        move_uploaded_file($tempFile, $targetFile);
        
        // $worksheet = $excel->getActiveSheet();
        // $highestRow = $worksheet->getHighestRow();
        $highestRow = count($dataArray)-1;

        if ($mode === "Edit") {

            $deletesql = "DELETE FROM [erplivedb_customer].[dbo].[TMSMT_BOM] WHERE model = ?";
            $params = array($getModel);
            //$params = array($getModel, $getRev);
            $stmt = sqlsrv_query($conn, $deletesql, $params);
        }
       
        // print_r(array($getModel));
        // die();
	
        if (isset($_FILES['excel-file']) && $_FILES['excel-file']['error'] == UPLOAD_ERR_OK) {
            // Load the Excel file
           
            
            // Get the first worksheet
            $worksheet = $excel->getActiveSheet();
            
            // Get the highest row number
            $highestRow = $worksheet->getHighestRow();
            
            // Prepare the SQL statement
            $sql = "INSERT INTO [erplivedb_customer].[dbo].[TMSMT_BOM] 
            ([model], [column1],[column2],[column3],[column4],[column5],[column6],
             [column7],[column8],[column9],[column10],[column11],[column12],[column13],
             [column14],[column15],[column16],[column17],[column18],[column19],[column20],
             [column21],[column22],[column23],[column24],[column25],[column26],[column27],
             [column28],[column29],[column30],[column31],[column32],[column33],[column34],
             [column35],[column36],[column37],[column38],[column39],[column40],
             [column41],[column42],[column43],[userid],[Parent],[runningNum]) 
           	VALUES (?,?, ?,
	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
	?, ?, ?,?)";
    
    
            // Prepare the SQL query once outside the loop for better performance
            // $stmt = sqlsrv_prepare($conn, $sql,$params);
            
            // Check if preparing the statement was successful
            if ($stmt === false) {
                die(json_encode(array("message" => 'Error: Unable to prepare SQL query.', "type" => 1)));
            }
        
            // Set initial parent values from getModel
            $smallParent = $getModel;
            $A190Parent = $getModel;
        
            // Iterate through each row and insert data into the database
            $runningNumber = 1;
            for ($row = 2; $row <= $highestRow; $row++) {
                // Initialize the parameter array for each row
                $params = array();
                
                // Add model parameter
                $params[] = $getModel;
                
                // Loop through columns and values
                $column3Value = null;
                for ($i = 1; $i <= 43; $i++) {
                    $value = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
                    // Add empty string if the value is null
                    $params[] = $value !== null ? $value : "";
                    if ($i == 3) {
                        $column3Value = $value;
                    }
                }
                
                // Add an empty string for the userid
                $params[] = "";
                
                // Check if column3 has the value 'A190-Top Level Card Assembly'
                if ($column3Value === 'A190-Top Level Card Assembly') {
                    $parent = $A190Parent;
                    $smallParent = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                } else {
                    $parent = $smallParent;
                }
                
                // Add parent parameter
                $params[] = $parent;
                $params[] = $runningNumber;
              
                // echo "SQL Query: $sql\n";
                // echo "Parameters: " . json_encode($params) . "\n";
                // Execute the query with the prepared statement and parameters
                if (!sqlsrv_query($conn, $sql, $params)) {
                    $errors = sqlsrv_errors();
                    die(json_encode(array("message" => 'Error: Unable to execute SQL query.', "type" => 1, "error" => $errors)));
                }
                $runningNumber++; 
            }
            
            
            // echo json_encode(array("message" => 'Data imported successfully.', "type" => 2));
        }
        
        // for ($row = 1; $row <= $highestRow; $row++) {
        //     // echo $row;
       

        //     $sql = "INSERT INTO [erplivedb_customer].[dbo].[TMSMT_BOM] ([model], [column1],[column2],[column3],[column4],[column5],[column6]
        //     ,[column7],[column8],[column9],[column10],[column11],[column12],[column13],[column14],[column15],[column16],[column17],[column18]
        //     ,[column19],[column20],[column21],[column22],[column23],[column24],[column25],[column26],[column27],[column28],[column29],[column30]
        //     ,[column31],[column32],[column33],[column34],[column35],[column36],[column37],[column38],[column39],[column40]
        //     ,[column41],[column42],[column43],[userid]) 
		// 	VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //      $params = array($getModel);
    
        //      // Loop through columns and values
        //      for ($i = 1; $i <= 43; $i++) {
        //          $columnName = "column{$i}Values";
        //          $value = isset(${$columnName}[$row]) ? ${$columnName}[$row] : null;
        //          $params[] = $value;
        //      }
             
        //      // Add an empty string for the last parameter (userid)
        //      $params[] = "";
			
	
        //     $stmt = sqlsrv_query($conn, $sql, $params);

        //     if ($stmt === false) {
        //         $errors = sqlsrv_errors();
        //         // Output detailed error information
        //         print_r($errors);

        //         // Rollback the transaction on error
        //         sqlsrv_rollback($conn);
        //         die(json_encode(array("message" => "Error executing insert query1: " . print_r(sqlsrv_errors(), true), "type" => 1)));
        //     }
            
        // }

        sqlsrv_commit($conn);
        sqlsrv_close($conn);


        // echo "Data from Excel file has been updated in the database.";
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