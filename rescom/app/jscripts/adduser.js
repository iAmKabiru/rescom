$(document).ready(function() {
	$('#usertype').on('change', function() {
		$val = $(this).val();
		switch ($val) {
			case "Student":
				$('#addstaff-admin').css("display", "none");
				$('#addstudent-admin').css("display", "block");
				break;
			case "Staff":
				$('#addstaff-admin').css("display", "block");
				$('#addstudent-admin').css("display", "none");
				break;
			case "Select Type":
				$('#addstudent-admin').css("display", "none");
				$('#addstaff-admin').css("display", "none");
				break;
		}
	});

	
	$modal = $('#confirmModal');
	$('#addstaff-admin').on('submit', function(event) {
		event.preventDefault();
		$modal.find('.modal-title').text('Operation Confirmation');
		$modal.find('#msg').text('You are about to add a staff to the platform, please ensure that you have selected the appropriate access level.');
		$modal.modal('show');
	});

	$modal.on('show.bs.modal', function() {
		$('#add').on('click', function() {
			$modal.modal('hide');
			addStaff();
		});
	});

	$('#addstudent-admin').on('submit', function(event) {
		event.preventDefault();
		$modal = $('#infoModal');
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/user-data.php',
			contentType: false,
			processData: false,
			data: new FormData(this),
			success: function(response) {
				console.log(response);
				$.unblockUI();
				switch (response) {
					case 'invalid_date':
						$modal.find('.modal-title').text('Date Error');
						$modal.find('#msg').html('The date of birth: <q><b>'+$("#dob").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_num':
						$modal.find('.modal-title').text('Number Format Error');
						$modal.find('#msg').html('The number in class: <q><b>'+$("#numinclass").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_pnum':
						$modal.find('.modal-title').text('Date Phone Number');
						$modal.find('#msg').html('The parent phone number: <q><b>'+$("#parentnumber").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_ext':
						$modal.find('.modal-title').text('Invalid File Extension');
						$modal.find('#msg').html('The file you uploaded is not a valid image file, please review and check that it has one of the following extensions: jpg, jpeg, png');
						$modal.modal('show');
						break;
					case 'invalid_size':
						$modal.find('.modal-title').text('File Upload Error');
						$modal.find('#msg').html('The size file you uploaded is too large, please review and check that it is less than 2MB in size');
						$modal.modal('show');
						break;
					case 'exist':
						$modal.find('.modal-title').text('Operation Error');
						$modal.find('#msg').html('The specified student with Admission Number: <q><b>'+$("#admin-no").val()+'</b></q> already exists in the platform, please review and try again.');
						$modal.modal('show');
						break;
					case 'numexist':
						$modal.find('.modal-title').text('Operation Error');
						$modal.find('#msg').html('Another Student in the selected class already has the specified number in class, please review and try again!');
						$modal.modal('show');
						break;
					case 'onaderror':
						$modal.find('.modal-title').text('Operation Error');
						$modal.find('#msg').html('The Student\'s Class on Admission Cannot be Higher than his Current Class, please review and try again!');
						$modal.modal('show');
						break;
					case 'failed': 
						$modal.find('.modal-title').text('Techical Error');
						$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
						$modal.modal('show');	
						break;
					case 'success':
						$modal.find('.modal-title').text('Operation Successful');
						$modal.find('#msg').html('The Student has been added to the platform successfully! <br /> Admission Number: <b>'+$("#admin-no").val()+'</b>');
						$modal.modal('show');	
						break;
				}
			},
			error: function() {
				$.unblockUI();
				$modal.find('.modal-title').text('Technical Error');
				$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
				$modal.modal('show');	
			}
		});
	});

	$('#addstudent-base').on('submit', function(event) {
		event.preventDefault();
		$modal = $('#infoModal');
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/user-data.php',
			contentType: false,
			processData: false,
			data: new FormData(this),
			success: function(response) {
				console.log(response);
				$.unblockUI();
				switch (response) {
					case 'invalid_date':
						$modal.find('.modal-title').text('Date Error');
						$modal.find('#msg').html('The date of birth: <q><b>'+$("#dob").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_num':
						$modal.find('.modal-title').text('Number Format Error');
						$modal.find('#msg').html('The number in class: <q><b>'+$("#numinclass").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_pnum':
						$modal.find('.modal-title').text('Date Phone Number');
						$modal.find('#msg').html('The parent phone number: <q><b>'+$("#parentnumber").val()+'</b></q> you provided is invalid, please review and try again.');
						$modal.modal('show');
						break;
					case 'invalid_ext':
						$modal.find('.modal-title').text('Invalid File Extension');
						$modal.find('#msg').html('The file you uploaded is not a valid image file, please review and check that it has one of the following extensions: jpg, jpeg, png');
						$modal.modal('show');
						break;
					case 'invalid_size':
						$modal.find('.modal-title').text('File Upload Error');
						$modal.find('#msg').html('The size file you uploaded is too large, please review and check that it is less than 2MB in size');
						$modal.modal('show');
						break;
					case 'exist':
						$modal.find('.modal-title').text('Operation Error');
						$modal.find('#msg').html('The specified student with Admission Number: <q><b>'+$("#admin-no").val()+'</b></q> already exists in the platform, please review and try again.');
						$modal.modal('show');
						break;
					case 'failed': 
						$modal.find('.modal-title').text('Techical Error');
						$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
						$modal.modal('show');	
						break;
					case 'success':
						$modal.find('.modal-title').text('Operation Successful');
						$modal.find('#msg').html('The Student has been added to the platform successfully! <br /> Admission Number: <b>'+$("#admin-no").val()+'</b>');
						$modal.modal('show');	
						break;
				}
			},
			error: function() {
				$.unblockUI();
				$modal.find('.modal-title').text('Technical Error');
				$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
				$modal.modal('show');	
			}
		});
	});

	$('#passport').on('change', function(event) {
		preview(document.getElementById('passport'));
		$src = $('#passport').val()//.replace("C:\\fakepath\\", "../images/");
		$preview = $('.passport-preview');
		$preview.css('display', 'block');
		$preview.html('<img src="'+$src+'" width="240" height="240" />');
	});
});

function preview(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('.passport-preview')
				.css('display', 'block')
				.html('<img src="'+e.target.result+'" width="240" height="240" />');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function addStaff() {
	$form = $('#addstaff-admin');
	$modal = $('#infoModal');
	$username = $('#username').val();
	$password = $('#password').val();
	$.blockUI();
	$.ajax({
		method: 'post',
		url: '../controllers/user-data.php',
		dataType: 'text',
		data: $form.serialize(),
		success: function(response){
			$.unblockUI();
			switch (response) {
				case 'exist': 
					$modal.find('.modal-title').text('Username Error');
					$modal.find('#msg').text('The username you supplied already exist in the platform. Please choose another username and try again!.');
					$modal.modal('show');	
					break;
				case 'failed': 
					$modal.find('.modal-title').text('Techical Error');
					$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
					$modal.modal('show');	
				case 'success':
					$modal.find('.modal-title').text('Operation Successful');
					$modal.find('#msg').html('The Staff has been added to the platform successfully! <br /> Username: <b>'+$username+'</b> <br />Password: <b>'+$password+'</b>');
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
}