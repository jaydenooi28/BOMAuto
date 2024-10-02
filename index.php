<!DOCTYPE html>
<html lang="en" data-bs-theme="dark" class="bg-cover-1">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-Q8hvQzqrECNJKwDkQ mW0Y9WjPUHzIhwXlCEMEVLwZ ApplHGVXROTKuxYXh5zEzFsJkJG0FNcr7Asz/YlWj6OW" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
<!-- <img src="/inforLN/gtr/img/pen-fill.svg" alt="Pen Icon"> -->
<head>
    <meta charset="utf-8" />
    <title>TMSMT | Bill of Materials</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/png" href="/inforln/assets/img/company.png">
    <link href="/inforln/assets/css/vendor.min.css" rel="stylesheet" />
    <link href="/inforln/assets/css/app.min.css" rel="stylesheet" />

    <link href="/inforln/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/inforln/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style>
    .node circle {
      fill: #fff;
      stroke: steelblue;
      stroke-width: 3px;
    }

    .node text { font: 12px sans-serif; }

    .link {
      fill: none;
      stroke: #ccc;
      stroke-width: 2px;
    }

    .dropdown-menu.show {
        z-index: 999;
        }

ul, #myUL {
  list-style-type: none;
}

#myUL {
  margin: 0;
  padding: 0;
}
.caret-2 {
  cursor: pointer;
  -webkit-user-select: none; /* Safari 3.1+ */
  -moz-user-select: none; /* Firefox 2+ */
  -ms-user-select: none; /* IE 10+ */
  user-select: none;
}


.caret-2::before {
    content: "+";
    color: white;
    display: inline-block;
    margin-right: 6px;
    width: 20px; /* Adjust width as needed */
    height: 20px; /* Adjust height as needed */
    text-align: center;
    line-height: 20px; /* Adjust line height to center the text vertically */
}

.caret-3 {
    white-space: nowrap; /* Prevent wrapping */
    overflow: hidden; /* Hide overflowed content */
    text-overflow: ellipsis; /* Display ellipsis for overflowed content */
}

.caret-3::before {
    content: "\2022";
    color: white;
    display: inline-block;
    margin-right: 6px;
    width: 20px; /* Adjust width as needed */
    height: 20px; /* Adjust height as needed */
    text-align: center;
    line-height: 20px; /* Adjust line height to center the text vertically */
}

.caret-down::before {
    content: "-";
}

.nested {
  display: none;
}

.active {
  display: block;
}
.hide-column {
    display: none;
}
  
  </style>
</head>

<body>

<div id="app" class="app app-sidebar-collapsed">

<?php include("../../default/topbar.php") ?>
        

        <?php include("../../default/slider.php") ?>


        <button class="app-sidebar-mobile-backdrop" data-toggle-target=".app"
            data-toggle-class="app-sidebar-mobile-toggled"></button>
        <div id="content" class="app-content">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-fill">
           
                    <h1 class="page-header mb-0"> TMSMT Bill of Materials (BOM)</h1>
                </div>
                <div class="ms-auto">
                    <a href="#" class="btn btn-outline-theme" data-bs-toggle="modal" data-bs-target="#modal"
                    onclick="modal('btnAdd')"><i class="fa fa-plus-circle me-1"></i> Upload BOM</a> 
                </div>
                
            </div>
           
            <div class="row gx-4">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body pb-2">
                            <form id="searchForm">
                                <div class="row">
                                    <!-- <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label ">Date Range</label>
                                            <div class="input-group" id="daterange">
                                                <input type="text" name="daterange" class="form-control" value=""
                                                    placeholder="click to select the date range" />
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label class="form-label"></label>
                                            
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-4">
                                        <!-- <div class="mb-3">
                                            <label class="form-label">Search</label>
                                            <div class="input-group flex-nowrap">
                                                <input type="text" class="form-control text-uppercase" name="searchCustom"
                                                    placeholder="Model Number" autofocus/>
                                                <button type="button" class="btn btn-theme input-group-text" id="searchBtn"><i
                                                        class="bi bi-search"></i></button>
                                            </div>
                                        </div> -->


                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="hljs-container rounded-bottom">
                            <!-- html -->
                            <table id="myDataTable" class="table table-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>Date Uploaded</th>
                                        <th>Model</th>
                                        
                                        <!-- <th class="hide-column">Rev</th>
                                        <th>Total Part - Cus</th>
                                        <th>Total Part - Infor</th>-->
                                        <th>Unregistered Item</th>
                                        
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>

            </div>
          
            <div class="modal fade" id="modal" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content" id="modalContent">
                        <div class="text-center">
                            <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
  
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            
       
        </div>

        <?php require('../../default/rocket_loader.php') ?>

        <a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>

        <script src="/inforln/assets/js/jquery-3.6.3.min.js"></script>
        <script src="/inforln/assets/js/moment.min.js"></script>
        <script src="/inforln/assets/js/vendor.min.js" type="75585ce28ba806737c24d4bd-text/javascript"></script>
        <script src="/inforln/assets/js/app.min.js" type="75585ce28ba806737c24d4bd-text/javascript"></script>
        <script src="/inforln/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/inforln/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="/inforln/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="../../../inforln/assets/js/xlsx.full.min.js"></script>
        <script src="https://d3js.org/d3.v6.min.js"></script>

        
</body>

</html>

<script>

$(document).ready(function() {

$(document).on("click", ".caret-2", function() {
    var caretId = $(this).attr("id"); // Get the id of the clicked span
    $("#" + caretId).parent().find(".nested").first().toggleClass("active"); // Find the first corresponding nested element and toggle its class
    $("#" + caretId).toggleClass("caret-down"); // Toggle the caret-down class for the clicked span
});
datatable = $('#myDataTable').DataTable({
        'processing': true,
        'serverSide': true,
        'searching': true,
        'ordering': false,
        'paging': true, // Enable pagination
        'lengthMenu': [10, 25, 50, 100], // Set length menu options
        'ajax': {
            'url': '../api/bom_datatable.php',
            'type': 'POST'
        },
        "dom": '<"top"<"row"<"col-sm-1"l><"col-sm-11"f>><"clear">>rt<"bottom"ip<"clear">>',
        'initComplete': function () {
        // Add placeholder to the search input
        $('.dataTables_filter input').attr('placeholder', 'Model Number');
    },
        "language": {
        "lengthMenu": " _MENU_ entries per page",
        "zeroRecords": "No matching records found",
        },
        'columns': [
            // { 'data': 'datecreated' },
            {'data': 'datecreated',
                // 'render': function (data, type, row) {
                //     // Parse the date string to a JavaScript Date object
                //     var date = new Date(data);

                //     // Get the hours and minutes
                //     var hours = date.getHours();
                //     var minutes = ('0' + date.getMinutes()).slice(-2);

                //     // Determine AM or PM
                //     var ampm = hours >= 12 ? 'PM' : 'AM';

                //     // Convert hours from 24-hour to 12-hour format
                //     hours = hours % 12;
                //     hours = hours ? hours : 12; // The hour '0' should be '12'

                //     // Format the date to 'Y-m-d H:i AM/PM' add
                //     var formattedDate = date.getFullYear() + '-' + 
                //         ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                //         ('0' + date.getDate()).slice(-2) + ' ' + 
                //         ('0' + hours).slice(-2) + ':' + minutes + ' ' + ampm;

                //     return formattedDate;
                // }
            },
            { 'data': 'model', 'searchable': true },
            // { 'data': 'rev' },
            // { 'data': 'Cust_Item' },
            // { 'data': 'Infor_Item' },
            { 'data': 'NotReg_Item' },
            // { 'data': 'LinkSIE' },
            {
                mRender: function (data, type, row) {
                    // if (row['LinkSIE'] === 'No') {
                        $model = row['model']; 
                        // $rev = row['rev'];
                        $not_reg_item = row['NotReg_Item'];
                        var btn = '';
                      
                        return '<center><button class="btn btn-dark btn-sm me-1 dropdown">'  
                        +'<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">'    
                        +'<i class="bi bi-list"></i> Menu '   
                        +'</a>'   
                        +'<div class="dropdown-menu">'
                        +'<a href="#" class="dropdown-item" onClick="btnBomExport(\''+$model +'\')"><i class="bi bi-download"></i> SMTT BOM</a>'
                        +'<a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal" onClick="modal(\'btnUnReg\',\''+$model +'\')"><i class="fa fa-question-circle"></i> Unregistered Item</a>'  
                        +'<a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal" onClick="modal(\'btnEdit\',\''+$model +'\')"><i class="bi bi-upload"></i> Re-upload</a>' 
                        +'</div> '    
                        +'</button>' 
                        +'<div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary d-none" role="status"><span class="sr-only">Loading...</span></div>' 
                        +'<div class="spinner-border spinner-grow-sm text-light d-none" id="spinner-' + $model + '" role="status"><span class="sr-only">Loading...</span></div>' 
                        +'</center>';
                     
                },
            },
        ],
        
    });

});
function btnBomExport(model) {
    $('#spinner-' + model).removeClass('d-none');
    // $('#loadingIndicator').show();
    $.ajax({
        url: 'downloadExcel.php',
        method: 'POST',
        data: { model: model },
        dataType: 'json',
        success: function (response) {
            // $('#loadingIndicator').hide();
            $('#spinner-' + model).addClass('d-none');
            // Check if the response indicates success
            if (response.success) {
                // Create a hidden link and trigger a click event to download the file
                var link = document.createElement('a');
                link.href = response.fileUrl;

                // Parse filename from the fileUrl
                var filename = response.fileUrl.split('/').pop();
                link.download = filename; // Set the filename

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                toast(2, response.message);
            } else {
                // Handle error
                console.error('Failed to download Excel file.');
                toast(1, response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            $('#spinner-' + model).addClass('d-none');
        }
    });
}


function downloadExcel(model) {
    $.ajax({
        type: "POST",
        url: 'downloadExcel.php',
        dataType: 'json',
        data: { model: model },
        success: function (d) {
            if (d && d.data) {
                $('#modalContent').html(d.data);
            } else {
                console.error('Invalid response data:', d);
            }
            
            if (d && d.java) {
                $.getScript(d.java);
            } else {
                console.error('JavaScript file not specified in response:', d);
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX error:', error);
            alert('AJAX error: ' + error);
            $("#model").modal('hide');
        }
    });
}



function modal(mode,model) {
    $('#modalContent').html('<div class="text-center">'
        +'<div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">'
        +'  <span class="visually-hidden">Loading...</span>'
        +' </div>'
    +'</div>'); 
    $.ajax({
        type: "POST",
        url: 'add_task.php',
        dataType: 'json',
        data: { mode: mode, model: model},
        success: function (d) {
            // alert('Error: ' + modal + ' ; ' +rev);
            $('#modalContent').html(d.data); 
            $.getScript(d.java);
        },
        error: function (xhr, status, error) {
            alert('Error: ' + error.message);
            $("#model").modal('hide');
        }
    });
}

function exportToExcel() {
    // Sample data
    var data = [
        ["model","Infor LN Rev","level", "parent", "part number", "description", "process", "qpa", "mfg", "mpn", "shelf life","msd"]
    ];

    // Create a new workbook
    var wb = XLSX.utils.book_new();

    // Convert data to a worksheet
    var ws = XLSX.utils.aoa_to_sheet(data);

    // Set column widths
    ws["!cols"] = [
        { wch: 10 }, // model
        { wch: 12 }, // Infor LN Rev
        { wch: 5 }, // level
        { wch: 10 }, // parent
        { wch: 20 }, // part number (adjust the width as per your requirement)
        { wch: 50 }, // description (adjust the width as per your requirement)
        { wch: 10 }, // process
        { wch: 5 }, // qpa
        { wch: 10 }, // location
        { wch: 10 }, // mfg
        { wch: 15 }, // mpn
        { wch: 5 } // mpn
    ];

    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

    // Save the workbook as an Excel file
    XLSX.writeFile(wb, "AddOn_BOM_Template.xlsx");
}





function toast(type,message)
    {
        if (type === 1) {
            $("#liveToast").removeClass().addClass("toast text-bg-danger border-0");
        } else if (type === 2) {
            $("#liveToast").removeClass().addClass("toast text-bg-success border-0");
        } else {
            $("#liveToast").removeClass().addClass("toast text-bg-warning border-0");
        }
        $('#toastMessage').html(message);
        $('.toast').toast('show');
    }
 
</script>

