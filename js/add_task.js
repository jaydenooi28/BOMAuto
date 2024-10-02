$("#btnAdd, #btnNoUse,#btnEdit").click(function(e) {
    var clickedButton = $(this); // Reference to the clicked button
    e.preventDefault();
    

    // Prevent multiple clicks and show spinner
    clickedButton.prop("disabled", true).find("span.spinner-border").removeClass("d-none");

    var inputFile = $("input[name='excel-file']")[0];
    var modelValue = $("input[name='model']").val();

    if (inputFile.files.length > 0) {
        var startTime = new Date().getTime(); // Get the start time
        
        var formData = new FormData();
        formData.append("excel-file", inputFile.files[0]);
        formData.append("model", modelValue);
        
        // Determine the URL and mode based on which button was clicked
        var url, mode;
        if (clickedButton.attr('id') === 'btnAdd') {
            url = "../bom/upload_form.php";
            mode = "btnAdd";
        } 
        else if (clickedButton.attr('id') === 'btnEdit') {
            url = "../bom/upload_form.php";
            mode = "Edit";
        }
        else if (clickedButton.attr('id') === 'btnNoUse') {
            url = "../bom/upload_form_NoUse.php";
            mode = "btnNoUse";
        }
        formData.append("mode", mode);  


        // Show the loading time label
        $(".label-timer").removeClass("d-none");

        // Start the timer to display loading time
        var timer = setInterval(function() {
            var currentTime = new Date().getTime();
            var elapsedTime = Math.floor((currentTime - startTime) / 1000);
            $("#loadingTime").text(elapsedTime);
        }, 1000);

        // Perform AJAX request
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(d) {               
                // Hide the loading time label
                $(".label-timer").addClass("d-none");

                // Clear the timer
                clearInterval(timer);
               
                
                    if (d.type != 1) {
                        $("#modal").modal('hide');  // Hides a modal with id "modal"
                        datatable.ajax.reload();    // Reloads a datatable via AJAX
                    }
              
               
                toast(d.type, d.message);
                setTimeout(function() {
                    // Re-enable the clicked button and hide spinner after a delay
                    clickedButton.prop("disabled", false).find("span.spinner-border").addClass("d-none");
                }, 600); // Delay for 600 milliseconds (0.6 seconds)
            }
        });
    } else {  
        // No file selected, enable the clicked button and show toast message
        clickedButton.prop("disabled", false).find("span.spinner-border").addClass("d-none");
        toast(1, "No file selected.");  
    }
});


