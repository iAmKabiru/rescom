/*
//============================================================+
// File name   : script.js
//
// Description : JavaScript file
//
// Author: Ozoka Lucky Orobo
//
// (c) Copyright:
//               GenTech
//
//============================================================+
*/


function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode;
	return !(charCode > 31 && (charCode < 48 || charCode > 57)  && charCode != 46);
}

$(document).ready(function() {
	var url = location.href;
	if (/dashboard/i.test(url)) {
		changeCurrent("dashboard");
		return;
	} 
	if (/newuser/i.test(url)) {
		changeCurrent("newuser");
		return;
	}
	if (/compile/i.test(url)) {
		changeCurrent("compile");
		return;
	}
	if (/results/i.test(url)) {
		changeCurrent("results");
		return;
	} 
	if (/export/i.test(url)) {
		changeCurrent("export");
		return;
	}
});

$(document).ready(function() {
	$('#login-form').submit(function(event) {
		event.preventDefault();
		$.ajax({
			method: "post",
			url: "../main/controllers/login.php",
			data: $(this).serialize(),
			success: function(response) {
				console.log(response);
				if (response == "success") {
					location.href = "../main/views/index.php";
				} else if (response == "failed") {
					$logininfo = $('.login-info');
					$logininfo.css('background-color', '#a94442');
					$logininfo.text("Invalid username or password! Please ensure that you have been authorized to use this platform.");
				}
			}
		});
	});


	$('input[rel="txtToolTip"]').tooltip();

	$('#profile').click(function() {
			$('#sub-profile').toggle('display');
	});

	$('#search-trigger').on('click', function() {
		$(this).parent().find('.search-box').toggle("display");
	})

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

	$('.clear-fields').on('click', function(event) {
		event.preventDefault();
		$(this).closest('form')[0].reset();
		$('.passport-preview').css('display', 'none');
	});


	document.getElementById('passport').addEventListener('change', function(event) {
		$src = $('#passport').val()//.replace("C:\\fakepath\\", "../images/");
		alert($src);
		$preview = $('.passport-preview');
		$preview.css('display', 'block');
		$preview.html('<img src="'+$src+'" width="240" height="240" />');
	});

});

function changeCurrent(str) {
	$('li.current').removeClass('current');
	$('a[href*="'+str+'"]').parent('li').addClass('current');
}

$(document).ready(function() {
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

});

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

$(document).ready(function() {
	$('#getstudents-form').on('submit', function(event) {
		event.preventDefault();
		if (validateForm()) {
			console.log($(this).serialize());
			$.blockUI();
			$.ajax({
				method: "post",
				url: "../views/students-table.php",
				data: $(this).serialize(),
				success: function(response) {
					$('.students-list').html(response);
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


	if (!(/[0-9]{4}\/{1}[0-9]{4}/i.test($session))) {
		$('#session').focus();
		return false;
	}

	if ($year.length < 4) {
		$('#year').focus();
		return false;
	}

	return true;
}