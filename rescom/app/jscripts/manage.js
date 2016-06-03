$(document).ready(function() {
	$('#add-session-form').on('submit', function(event) {
		event.preventDefault();
		$modal = $('#infoModal');
		if (!(/[0-9]{4}\/{1}[0-9]{4}/i.test($('#session').val()))) {
			$('#session').focus();
			return;
		}
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/manage-ci.php',
			data: $(this).serialize(),
			success: function(response) {
				$.unblockUI();
				console.log(response);
				switch (response) {
					case 'success':
						$modal.find('.modal-title').text('Operation Successful');
						$modal.find('#msg').text('The specified session has been successfully added to the platform.');
						$modal.modal('show');
						break;
					case 'failed':
						$modal.find('.modal-title').text('Operation Failed');
						$modal.find('#msg').text('The session could not be added to the platform because it already exists or it may be a general technical error. If this error persists please contact the system administrator');
						$modal.modal('show');	
						break;
				}
			},
			error: function(response) {
				$.unblockUI();
				$modal.find('.modal-title').text('Technical Error');
				$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
				$modal.modal('show');	
			}
		});
	});

    $('#edit-user').on('click', function() {
        $('.add-session').css('display', 'none');
        $('.remove-user').css('display', 'none');
        $('.add-to-session').css('display', 'none');
        $('.edit-user').css('display', 'block');
    });

})