$(document).ready(function() {

    $('#resulttype').on('change', function() {
        switch ($(this).val()) {
            case "Student Result Sheet":
            case "Class Score Sheet":
                $('#getstdresults-form').css("display", "block");
                break;
            case "Select Type":
                $('#getstdresults-form').css("display", "none");
                break;
        }
    });

    $('#getstdresults-form').on('submit', function(event) {
        $modal = $('#infoModal');
        event.preventDefault();
        var arr = $(this).serialize();
        var arr2 = ['&type=' + $('#resulttype').val()];
        if (validateForm()) {
            console.log(arr.concat(arr2));
            $.blockUI();
            $.ajax({
                method: "post",
                url: "../views/students-resulttable.php",
                data: arr.concat(arr2),
                success: function(response) {
                    $('.studentsresult-list').html(response);
                    $.unblockUI();
                },
                error: function(response) {
                    $.unblockUI();
                    $modal.find('.modal-title').text('Technical Error');
                    $modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
                    $modal.modal('show');
                }
            });
        }
    });

});

function validateForm() {
    $session = $('#session').val();
    $year = $('#year').val();

    if ($year.length < 4) {
        $('#year').focus();
        return false;
    }

    return true;
}