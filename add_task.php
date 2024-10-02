<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
//include('../../connection/dbcon.php');
header('Content-Type: application/json');

switch ($_POST['mode']) {
  case 'btnAdd': //add form Modal

  case 'btnEdit':
    $model = $rev = $readonly = '';
    $title = 'Import TMSMT BOM';
    $submit = 'btnAdd';
    $btnName = 'Submit';
    $ShowModel = 'none';

    if ($_POST['mode'] === 'btnEdit') {
      // $rev = sprintf("%03d", $_POST['rev']);
      $model = $_POST['model'];

      $readonly = 'readonly';
      $title = 'Edit TMSMT BOM'; 
      $submit = 'btnEdit';
      $btnName = 'Edit';
      $ShowModel = '';
    }

    $data = '<div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">' . $title . '</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
          <div class="mb-3 row">
            <div class="row mb-3 " style="display: ' . $ShowModel . ';">
                <label class="col-sm-3 col-form-label">Model</label>
                <div class="col-sm-9">
                  <input  type="text" class="form-control" name="model" value="' . $model . '" readonly>
                </div>
              </div>
              <div class="row mb-3 ">


              </div>
              <div class="row mb-3 ">
                <label class="col-sm-3 col-form-label">Customer File</label>
              
                  <div class="col-sm-9">
                    <input  type="file" class="form-control" name="excel-file" accept=".xls,.xlsx">
                    
                  </div>
                  <div class="col-sm-9">
                    <code><br>
                          1. This section is for <mark>Upload TMSMT BOM</mark><br>
                          2. Please make sure <mark>No Use Part is up to date</mark><br>
                          3. Only Excel files (xlsx)and (xls) are allowed for upload<br>
                          4. Only accepts original Agile BOM file downloaded from customer program<br>
                          5. Please make sure <mark>file name is the same as the Model</mark><br>
                          6. Re uploaded file will replace the current BOM<br>
                         
                  </div>
              </div>
        
        </div>
        <div class="modal-footer d-flex">
        <label class="col-form-label text-start m-0 d-none label-timer">Loading Time: <span id="loadingTime">0</span> seconds</label> 
            <div>
                <button type="button" class="btn btn-primary" id="' . $submit . '" >
                    <i class="bi bi-upload"></i> ' . $btnName . ' <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> 
                </button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>';
  
    break;

    case 'btnNoUse':
      $model = $rev = $readonly = '';
      $title = 'Import No Used Part';
      $submit = 'btnNoUse';
      $btnName = 'Submit';
      $ShowModel = 'none';
  
      if ($_POST['mode'] === 'btnEdit') {
        // $rev = sprintf("%03d", $_POST['rev']);
        $model = $_POST['model'];
  
        $readonly = 'readonly';
        $title = 'Edit TMSMT BOM'; 
        $submit = 'btnEdit';
        $btnName = 'Edit';
        $ShowModel = '';
      }
  
      $data = '<div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">' . $title . '</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <div class="modal-body">
            <div class="mb-3 row">
              <div class="row mb-3 " style="display: ' . $ShowModel . ';">
                  <label class="col-sm-3 col-form-label">Model</label>
                  <div class="col-sm-9">
                    <input  type="text" class="form-control" name="model" value="' . $model . '" readonly>
                  </div>
                </div>
                <div class="row mb-3 ">
  
  
                </div>
                <div class="row mb-3 ">
                  <label class="col-sm-3 col-form-label">Customer File</label>
                
                    <div class="col-sm-9">
                      <input  type="file" class="form-control" name="excel-file" accept=".xls,.xlsx">
                      
                    </div>
                    <div class="col-sm-9">
                      <code><br>          
                            1. This section is for uploading <mark>NO USED PART</mark><br>
                            
                            3. Only Excel files (xlsx)and (xls) are allowed for upload.<br>
                            4. Please only key in the Part Number in Column A.<br>
                           
                           
                           
                    </div>
                </div>
          
          </div>
          <div class="modal-footer d-flex">
          <label class="col-form-label text-start m-0 d-none label-timer">Loading Time: <span id="loadingTime">0</span> seconds</label> 
              <div>
                  <button type="button" class="btn btn-primary" id="' . $submit . '" >
                      <i class="bi bi-upload"></i> ' . $btnName . ' <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> 
                  </button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
        </div>';
    

    break;

    case 'btnUnReg': //add form Modal
      include('../../connection/dbcon.php');
  
      $itemTable = '';
  
      // $rev = sprintf("%03d", $_POST['rev']);
      $model = $_POST['model'];
  
  
      $query1 = "SELECT 
    ROW_NUMBER() OVER (ORDER BY column2) AS numbering,
    column2 as Unregistered_Item
FROM (
    SELECT DISTINCT 
        a.column2
    FROM 
        [erplivedb_customer].[dbo].[TMSMT_BOM] a
    LEFT JOIN  
        [erp].[dbo].ttcibd001800 b WITH (NOLOCK) ON LTRIM(b.t_item) = a.column2
    WHERE 
        b.t_dsca IS NULL
        AND a.column2 <> ''
		and model='" . $model . "' 
) AS distinct_columns
          ";
  
      $result1 = sqlsrv_query($conn, $query1);
      
      if ($result1 === false) {
        die(json_encode(array("message" => "Error executing query: " . print_r(sqlsrv_errors(), true), "type" => 1)));
      }
      $itemTable = '<table class="table">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Unregistered Part No.</th>
                          
                          </tr>
                      </thead>
                      <tbody>';
      while ($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        // $itemTable .= "<tr><td>" . $row['Unregistered_Item'] . "<br></td></tr>";
      
        $itemTable .= "<tr><td>" . $row['numbering'] . ". </td><td>" . $row['Unregistered_Item'] . "</td></tr>";

      }
      $itemTable .= '</tbody></table>';
      $data = '<div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Item not found in Infor LN</h1>
                
                </div>
  
                  <div class="modal-body">
                    <div id="body">'.$itemTable .'</div>
                  </div>
  
              <div class="modal-footer d-flex">
               
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
              </div>
          </div> ';
      break;
  
     
    default:
    $data = '<div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Failed</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>';

    break;

}


$json_data = array("data" => $data, "java" => 'js/add_task.js', );
echo json_encode($json_data);


?>