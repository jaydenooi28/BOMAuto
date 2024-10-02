
<?php

  include('../../connection/dbcon.php');

  if (!$conn) {
      die(json_encode(['message' => print_r(sqlsrv_errors(), true), 'type' => 1]));
  }
$start = isset($_POST['start']) ? $_POST['start'] : 0; // Start index for pagination
$length = isset($_POST['length']) ? $_POST['length'] : 10; // Number of records per page
$searchValue = $_POST['search']['value'];


$query=" with count as(
SELECT COUNT(DISTINCT a.column2) AS NotReg_Item,
a.model
FROM 
    [erplivedb_customer].[dbo].[TMSMT_BOM] a
LEFT JOIN  
    [erp].[dbo].ttcibd001800 b WITH (NOLOCK) ON LTRIM(b.t_item) = a.column2
WHERE 
	b.t_dsca IS NULL
    AND a.column2 <> ''
	GROUP BY 
    a.model

)
select a.model, 
FORMAT([datecreated], 'yyyy-MM-dd') AS datecreated,
NotReg_Item
from erplivedb_customer.dbo.TMSMT_BOM a
left join count b on a.model = b.model




";
if (!empty($searchValue)) {
    $query .= " WHERE a.model LIKE '%$searchValue%' ";
}

// Add grouping and ordering
$query .= " 
GROUP BY FORMAT([datecreated], 'yyyy-MM-dd'), a.model, NotReg_Item
ORDER BY [datecreated] DESC
 OFFSET $start ROWS FETCH NEXT $length ROWS ONLY
";



$result = sqlsrv_query($conn, $query, );

$myArray = array();
$totalRows = 0;
while ($rs = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $myArray[] = $rs;
        $totalRows++;
}
$queryTotal = "
select count(*) as total
from erplivedb_customer.dbo.TMSMT_BOM
GROUP BY FORMAT([datecreated], 'yyyy-MM-dd'), [model]";



$resultTotal = sqlsrv_query($conn, $queryTotal,);
$row_count = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$row_count = ($row_count !== false) ?  $row_count['total']:0 ;


$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" =>   $row_count,
    "recordsFiltered" =>  $totalRows,
    "data" => $myArray,
    $query
);  


header('Content-Type: application/json');  
echo json_encode($json_data, JSON_PRETTY_PRINT);

sqlsrv_close($conn);
?>