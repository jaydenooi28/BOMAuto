<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet library
include('../../connection/dbcon.php'); // Include your database connection file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;



if (isset($_POST['model'])) {
    $model = $_POST['model'];

    // Prepare cover query
    $coverQuery = "SELECT 
        LEFT(column4, CHARINDEX(',', column4) - 1) AS CustomerModel,
        '(' + model + ')' AS Model
        FROM [erplivedb_customer].[dbo].[TMSMT_BOM] 
        WHERE column1 = '0' AND model = ?";

    // Execute cover query
    $coverParams = array($model);
    $coverResult = sqlsrv_query($conn, $coverQuery, $coverParams);

    if ($coverResult !== false) {
        $coverRow = sqlsrv_fetch_array($coverResult, SQLSRV_FETCH_ASSOC);
        $customerModel = $coverRow['CustomerModel'];
        $modelWithParenthesis = $coverRow['Model'];

        // Create a new PhpSpreadsheet object
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        
        // Create and set up the cover sheet
        $coverSheet = $spreadsheet->createSheet();
        $coverSheet->setTitle('Cover');
        $coverSheet->setCellValue('A5', 'Document Title:');
        $coverSheet->setCellValue('A6', 'PRODUCT STRUCTURE');
        $coverSheet->setCellValue('A8', 'Customer');
        $coverSheet->setCellValue('A9', 'Model');
        
        
        $coverSheet->setCellValue('B8', ':  WD SANDISK');
        
        $coverSheet->setCellValue('B9', ": {$customerModel}    ({$model})");
        $coverSheet->mergeCells('B9:C10');
        $coverSheet->getStyle('B9:C10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $coverSheet->getStyle('B9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$coverSheet->getStyle('B9')->getAlignment()->setWrapText(true);
        
        $coverSheet->setCellValue('D5', 'Document No:');
        $coverSheet->mergeCells('D6:G6');
        $coverSheet->mergeCells('D8:G8');
        $coverSheet->setCellValue('H5', 'Revision          :');
        $coverSheet->setCellValue('H7', 'Effective Date    :');
        $coverSheet->mergeCells('H5:H6');
		$coverSheet->mergeCells('H7:H8');
        $coverSheet->mergeCells('I7:I8');
        $coverSheet->mergeCells('I6:I7');
        $coverSheet->mergeCells('I5:I6');

        $coverSheet->getStyle('H7:I7')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
        $coverSheet->mergeCells('H9:I10');
        $coverSheet->getStyle('H9:I10')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $coverSheet->getStyle('H9:I10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $coverSheet->setCellValue('A11', 'Approved by')->getStyle('A11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $coverSheet->mergeCells('A11:I11');
        $coverSheet->setCellValue('A16', 'AMENDMENT LOG')->getStyle('A16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $coverSheet->mergeCells('A16:I16');
        $coverSheet->mergeCells('H49:I49');

        $data = [
            'A4'=> '    TM SMT SDN.BHD (13222005-T)',
            'G9'=> 'Date',
            'F9'=>'Signature',
            'E9'=> 'Name',
            'D9'=>  'Position',
            'D8' => 'Prepared by : ',
            'A12' => 'Position',
            'B12' => 'Name',
            'C12' => 'Signature',
            'E12' => 'Date',
            'F12' => 'Position',
            'G12' => 'Name',
            'H12' => 'Signature',
            'I12' => 'Date',
            'A17' => 'Revision',
            'B17' => 'Originator',
            'C17' => 'DCN',
            'C18' => 'No.',
            'D17' => 'Effective',
            'D18' => 'Date',
            'E17' => 'Description of Change',
            'H49'=>'REG NO.2010-003-DCC/A'
        ];
        
        // Loop through each cell, set value and apply alignment in one line
        foreach ($data as $cell => $value) {
            $coverSheet->setCellValue($cell, $value)
                       ->getStyle($cell)
                       ->getAlignment()
                       ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                       ->setVertical(Alignment::VERTICAL_CENTER);
        }
        $coverSheet->mergeCells('C12:D12');
        $coverSheet->mergeCells('C13:D13');
        $coverSheet->mergeCells('C14:D14');
        $coverSheet->mergeCells('C15:D15');
        $coverSheet->mergeCells('E17:I18')->getStyle('E17:I18')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        $allBorders = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $cells = ['A1:I49','E17:I18','D9', 'D10','E9','E10','F9','F10','G9','G10','A5:C10','D5:G7','D8:G8','H5:I6','H7:I8','H9:I10','A11:I11','A16:I16','A17:A18','B17:B18','C17:C18','D17:D18','E17:I18','A19:A48','B19:B48','C19:C48','D19:D48','E19:I48'];
		foreach ($cells as $cell) {
		$coverSheet->getStyle($cell)->applyFromArray($borderStyle);}

        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $rowRanges = ['15','14','13', '12']; // Row ranges to apply the border style

        // Loop through each column and apply borders to the specified range
        foreach ($columns as $column) {
            foreach ($rowRanges as $rowRange) {
                $range = $column . $rowRange;
                $coverSheet->getStyle($range)->applyFromArray($borderStyle);
            }
        }

        // Set font bold
        $cellCoordinates = ['A11','A5', 'A6', 'A8', 'A9', 'B8', 'B9', 'D5', 'D6', 'D8'];
        

        foreach ($cellCoordinates as $cell) {
            $coverSheet->getStyle($cell)->getFont()->setBold(true);
        }

        $upSize = ['B8','B9'];
        foreach ($upSize as $cell) {
            $coverSheet->getStyle($cell)->getFont()->setSize(16);
        }

        $imagePath = '../image/tmsmt.png';
        $objDrawing = new Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath($imagePath);
        $objDrawing->setCoordinates('A1');
        // list($width, $height) = getimagesize($imagePath);
        // $objDrawing->setWidthAndHeight($width, $height);
        $objDrawing->setWorksheet($coverSheet);

       
        $query1 = "SELECT
                    a.column1 AS [Level], 
                    a.column2 AS PartNum,
                    b.t_dsca AS [Desc],
                    '-' AS [MFG P/N],
                    a.column17 AS QPA,
                    '-' as 'Ref Designator',
                    a.column2 AS 'Customer P/N',
                     a.runningNum
                FROM 
                    [erplivedb_customer].[dbo].[TMSMT_BOM] a
                LEFT JOIN  
                    [erp].[dbo].ttcibd001800 b WITH (NOLOCK) ON LTRIM(b.t_item) = a.column2
                WHERE a.Parent = ? AND a.model = ? and a.column2 <> '' 
                ORDER BY a.runningNum";
        
        $params1 = array($model, $model);
        $result1 = sqlsrv_query($conn, $query1, $params1);


        // Step 2: Fetch all values from TMSMT_BOM_EXCLUDE_PART based on column1
        $query_exclude = "SELECT column1 FROM [erplivedb_customer].[dbo].[TMSMT_BOM_EXCLUDE_PART]";
        $result_exclude = sqlsrv_query($conn, $query_exclude);

        if ($result_exclude === false) {
            // Handle query execution error
            die(print_r(sqlsrv_errors(), true));
        }

        $exclude_list = array();
        while ($row_exclude = sqlsrv_fetch_array($result_exclude, SQLSRV_FETCH_ASSOC)) {
            $exclude_list[] = $row_exclude['column1'];
        }

        // Step 3: Filter results in PHP based on exclusion list
        $filtered_results = array();
        while ($row_data = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
            // Check if PartNum (or a.column2) is not in the exclude list
            if (!in_array($row_data['PartNum'], $exclude_list)) {
                $filtered_results[] = $row_data;
            }
        }
        // Create the main sheet and write data
        $mainSheet = $spreadsheet->createSheet();
        $mainSheet->setTitle('Main');
        
        
        $mainSheet->getStyle('B1:J5')->applyFromArray($borderStyle);
        $mainSheet->getStyle('E1:G5')->applyFromArray($borderStyle);
        $mainSheet->getStyle('H1:J5')->applyFromArray($allBorders);
  
        $mainSheet->mergeCells('I3:J3');
        $mainSheet->mergeCells('E2:G5');
        $mainSheet->mergeCells('H4:J5');
        $mainSheet->mergeCells('H1:H2');
        $mainSheet->mergeCells('I1:J2');
        $mainSheet->mergeCells('B1:C1');
        $mainSheet->mergeCells('B2:C2');
        $mainSheet->mergeCells('B3:C3');
        $mainSheet->mergeCells('B4:C4');
        $mainSheet->mergeCells('B5:C5');
        $mainSheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $upperHeaders = ['Document Title:', 'PRODUCT STRUCTURE LEVEL', 'Project:', 'Customer:', 'Model:', 'Revision:', 'Effective Date:','=Cover!$B$9','=Cover!$B$8','Document No :','=Cover!$D$6','=Cover!$I$5','=Cover!I7'];
        $upperColumns = ['B1', 'B2', 'B3', 'B4', 'B5', 'H1', 'H3','D5','D4','E1','E2','I1','I3'];
        foreach ($upperHeaders as $index => $header) {
            $mainSheet->setCellValue($upperColumns[$index], $header)->getStyle($upperColumns[$index])->getFont()->setBold(true);

        }

        // $mainSheet->mergeCells('D7:E7');
        $headers = [
            'B' => 'Parent',
            'C' => 'SMTT P/N',
            'D' => 'Customer Parameter (Description / Spec)',
            'F' => 'MFG P/N',
            'G' => 'QPA',
            'H' => 'Ref Designator',
            'I' => 'Customer P/N',
        ];
        
        // Set headers
        foreach ($headers as $column => $header) {
            $mainSheet->setCellValue($column . '7', $header); // Example: 'B7', 'C7', etc.
            $mainSheet->getStyle($column . '7')->getFont()->setBold(true);
        }
        $fillColor = [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF00FFFF'],
        ];
        
        // Apply fill to the range B8:H8
        $mainSheet->getStyle('B8:I8')->applyFromArray([
            'fill' => $fillColor,
        ]);
        
        $endColumn = 8;   // Ending column (H)
        $startRow = 8;    // Starting row
        $endRow = 300;    // Ending row

        // Loop through each column and apply horizontal center alignment
        for ($col = 2; $col <= $endColumn; $col++) {
            $mainSheet->getStyleByColumnAndRow($col, $startRow, $col, $endRow)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        $startRow = 8;
        foreach ($filtered_results as $row => $data) {
            $currentRow = $startRow + $row;
            
            $mainSheet->setCellValue('B' . $currentRow, $data['Level']);
            $mainSheet->setCellValue('C' . $currentRow, $data['PartNum']);
            $mainSheet->setCellValue('D' . $currentRow, $data['Desc']);
            $mainSheet->mergeCells('D' . $currentRow . ':E' . $currentRow);
            $mainSheet->setCellValue('F' . $currentRow, '-'); // MFG P/N is always '-'
            $mainSheet->setCellValue('G' . $currentRow, $data['QPA']);
            $mainSheet->setCellValue('H' . $currentRow, $data['Ref Designator']);
            $mainSheet->setCellValue('I' . $currentRow, $data['Customer P/N']);
        }
     
        $lastRow = 7;
        while ($mainSheet->getCell('H' . $lastRow)->getValue() != null) {
            $lastRow++;
        }
        $lastRow--;
        $range = 'B1:J' . $lastRow;
        $mainSheet->getStyle($range)->applyFromArray($borderStyle);
        $range = 'B7:I' . $lastRow;   
        $mainSheet->getStyle($range)->applyFromArray($allBorders);;



  


        // Prepare SQL query to get the Parent values
        $query2 = "SELECT DISTINCT Parent 
                    FROM [erplivedb_customer].[dbo].[TMSMT_BOM] 
                    WHERE model = ? AND column3 = 'A190-Top Level Card Assembly'
                    
                     ";

        $params2 = array($model);
        $result2 = sqlsrv_query($conn, $query2, $params2);

        // Create sheets for each Parent
        while ($parent_data = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            $parent = $parent_data['Parent'];
            $newSheet = clone $mainSheet;
            // $newSheet = new Worksheet($spreadsheet, $parent);
            // $highestRow = $newSheet->getHighestRow();
            // $highestColumn = $newSheet->getHighestColumn();
            // $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // for ($row = 1; $row <= $highestRow; $row++) {
            //     for ($col = 1; $col <= $highestColumnIndex; $col++) {
            //         $newSheet->setCellValueByColumnAndRow($col, $row, null);
            //     }
            // }
            $newSheet->setTitle($parent);
            $spreadsheet->addSheet($newSheet);
        }

        // Prepare SQL query to get Semis
        $query3 = "SELECT 
                    column2 AS Semi
                FROM 
                    [erplivedb_customer].[dbo].[TMSMT_BOM]
                WHERE 
                    column3 = 'A190-Top Level Card Assembly' AND model = ? 
               
                    ";

        $params3 = array($model);
        $result3 = sqlsrv_query($conn, $query3, $params3);

        // Create sheets and write data for each Semi
        while ($semi_data = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
            $semi = $semi_data['Semi'];

            // Prepare SQL query to get data based on Parent and model
                        $query4 = "SELECT
                a.column1 AS Level, 
                a.column2 AS PartNum,
                b.t_dsca AS [Desc],
                '-' AS [MFG P/N],
                column17 AS QPA,
                    CASE
                        when column22 = '' then '-'
                        else column22
                    end as 'Ref Designator',
                a.column2 AS 'Customer P/N',
                a.runningNum 

            FROM 
                [erplivedb_customer].[dbo].[TMSMT_BOM] a
            LEFT JOIN  
                [erp].[dbo].ttcibd001800 b WITH (NOLOCK) ON (LTRIM(b.t_item)) = a.column2
            WHERE 
                a.Parent = ?
                AND model = ?
                and  a.column2 <> ''
                

            UNION

            SELECT
                a.column1 AS Level, 
                a.column2 AS PartNum,
                b.t_dsca AS [Desc],
                '-' AS [MFG P/N],
                column17 AS QPA,
                    '-' as 'Ref Designator',
                a.column2 AS 'Customer P/N',
                a.runningNum 

            FROM 
                [erplivedb_customer].[dbo].[TMSMT_BOM] a
            LEFT JOIN  
                [erp].[dbo].ttcibd001800 b WITH (NOLOCK) ON (LTRIM(b.t_item)) = a.column2
            WHERE 
                a.Parent = ?
                AND model =  ?
                and  a.column2 = ?
            
            ORDER BY 
                runningNum
                                ";

            $params4 = array($semi, $model,$model,$model,$semi);
            $result4 = sqlsrv_query($conn, $query4, $params4);

            $filtered_results = array();
            while ($row_data = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC)) {
                // Check if PartNum (or a.column2) is not in the exclude list
                if (!in_array($row_data['PartNum'], $exclude_list)) {
                    $filtered_results[] = $row_data;
                }
            }
            $newSheet = clone $mainSheet;
            // $newSheet = new Worksheet($spreadsheet, $parent);
            $highestRow = $newSheet->getHighestRow();
            $highestColumn = $newSheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
        
            for ($row = 8; $row <= $highestRow; $row++) {
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $newSheet->setCellValueByColumnAndRow($col, $row, null);
                }
            }
            
            $newSheet->setTitle($semi);

            $spreadsheet->addSheet($newSheet);
            $spreadsheet->setActiveSheetIndexByName($semi);

                                
            $startRow = 8; // Start at row 7
            foreach ($filtered_results as $row => $data) {
                $currentRow = $startRow + $row;
                
                $newSheet->setCellValue('B' . $currentRow, $data['Level']);
                $newSheet->setCellValue('C' . $currentRow, $data['PartNum']);
                $newSheet->setCellValue('D' . $currentRow, $data['Desc']);
                $newSheet->mergeCells('D' . $currentRow . ':E' . $currentRow);
                $newSheet->setCellValue('F' . $currentRow, '-'); 
                $newSheet->setCellValue('G' . $currentRow, $data['QPA']);
                $newSheet->setCellValue('H' . $currentRow, $data['Ref Designator']);
                $newSheet->setCellValue('I' . $currentRow, $data['Customer P/N']);
            }
            
            $lastRow = 8;
            while ($newSheet->getCell('H' . $lastRow)->getValue() != null) {
                $lastRow++;
            }
            $lastRow--; 
            $range = 'B7:I' . $lastRow;
           
            $newSheet->getStyle($range)->applyFromArray($allBorders);
            
            $range2 = 'B1:J' . $lastRow;
            $newSheet->getStyle($range2)->applyFromArray($borderStyle);

        }

        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);
        // Get the index of the sheet by name
        $sheetName = $model;
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName($sheetName)
        );

        // Remove the sheet by index
        $spreadsheet->removeSheetByIndex($sheetIndex);

        $sheetCount = count($spreadsheet->getAllSheets());
        foreach ($spreadsheet->getAllSheets() as $sheetIndex => $sheet) {
            $pageNumber = $sheetIndex + 1; 
            $pageNumberCell = ($sheetIndex == 0) ? 'H9' : 'H4';
            $sheet->setCellValue($pageNumberCell, 'Page ' . $pageNumber . ' of ' . $sheetCount)->getStyle($pageNumberCell)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        }

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));
            $sheet = $spreadsheet->getActiveSheet();
        
            // Calculate column widths based on content
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }
        
        
        // Save Excel file
        $writer = new Xls($spreadsheet);
        $excelFilePath = 'download/' . $customerModel . $modelWithParenthesis . '_' . date('YmdHis') . '.xls';
        $writer->save($excelFilePath);

        // Response indicating success and file URL
        $response = array(
            'type'  => 2,
            'message' => 'BOM Model ' . $model . ' Downloaded',
            'success' => true,
            'fileUrl' => $excelFilePath
        );
        echo json_encode($response);
    } else {
        // Handle query error
        $errorMessage = sqlsrv_errors()[0]['message'];
        echo "Query Error: $errorMessage";
    }
} else {
    // Response indicating failure
    $response = array(
        'type'  => 1,
        'success' => false,
        'message' => 'Model parameter is missing'
    );
    echo json_encode($response);
}
?>
