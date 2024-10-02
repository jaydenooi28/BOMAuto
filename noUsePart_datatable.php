
<?php

  include('../../connection/dbcon.php');

  if (!$conn) {
      die(json_encode(['message' => print_r(sqlsrv_errors(), true), 'type' => 1]));
  }
  $start = isset($_POST['start']) ? $_POST['start'] : 0;
$length = isset($_POST['length']) ? $_POST['length'] : 10; // Number of records per page
$searchValue = trim($_POST['search']['value'] ?? '');

$query=" WITH CTE AS (
    SELECT 
        ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS RowNum, 
        column1 AS NoUsePart
    FROM 
        [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]
)
SELECT 
    RowNum, 
    NoUsePart
FROM 
    CTE
WHERE 
         RowNum > $start
        AND RowNum <= ($start + $length)


";


if (!empty($searchValue)) {
    $query .= " and NoUsePart  LIKE '%$searchValue%' ";
}

$query .= "
    ORDER BY (SELECT NULL)
    OFFSET $start ROWS
    FETCH NEXT $length ROWS ONLY
";

// echo ($query);
$result = sqlsrv_query($conn, $query, );

$myArray = array();
$totalRows = 0;
while ($rs = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $myArray[] = $rs;
        $totalRows++;
}
$queryTotal = "
select distinct count(distinct column1) as total
from [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]";

$resultTotal = sqlsrv_query($conn, $queryTotal,);
$row_count = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$row_count = ($row_count !== false) ?  $row_count['total']:0 ;


$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" =>   $row_count,
    "recordsFiltered" =>  $totalRows,
    "data" => $myArray
);  


header('Content-Type: application/json');  
echo json_encode($json_data, JSON_PRETTY_PRINT);

sqlsrv_close($conn);
?>