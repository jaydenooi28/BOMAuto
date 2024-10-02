<?php

include('../../connection/dbcon.php');
header('Content-Type: application/json');

$data = '';
// First query to retrieve balanceQty from the first table
$query1 = "select distinct  column1 as NoUsePart from [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]";

$result1 = sqlsrv_query($conn, $query1);

$data1 = array();


// Create an HTML table to display the results
$data .= '<table Class="table table-sm table-bordered">';
$data .= '<tr><th>No</th><th>No Use Part</th></tr>';
$rowNumber = 1;

while ($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

  $data .= "<tr>";
  $data .= "<td>$rowNumber</td>";
  $data .= "<td>".$row['NoUsePart']."</td>";
  $data .= '<td><button class="btn btn-danger btn-sm" onClick="btnRemove(\''.$row['NoUsePart'].'\')"><i class="bi bi-trash"></i> Remove</button></td>';
  $data .= "</tr>";

  $rowNumber++;
}

$data .=  '</table>';

$json_data = array( "data" => $data, );
// $json_data = array("data" => $data,"message" => "Loading Successful","type" => 2 );
echo json_encode($json_data, JSON_PRETTY_PRINT);
sqlsrv_close($conn);


?>




