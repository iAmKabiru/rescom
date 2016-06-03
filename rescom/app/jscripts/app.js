/*
//============================================================+
// File name   : app.js
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
	if (/manage/i.test(url)) {
		changeCurrent("manage");
		return;
	} 
	if (/export/i.test(url)) {
		changeCurrent("export");
		return;
	}

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
			},
			error: function(response) {
				$.unblockUI();
				$modal.find('.modal-title').text('Technical Error');
				$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
				$modal.modal('show');	
			}
		});
	});

});

function changeCurrent(str) {
	$('li.current').removeClass('current');
	$('a[href*="'+str+'"]').parent('li').addClass('current');
}

$(document).ready(function() {
	$('.clear-fields').on('click', function(event) {
		event.preventDefault();
		$(this).closest('form')[0].reset();
		$('.passport-preview').css('display', 'none');
	});

	$('input[rel="txtToolTip"]').tooltip();

	$('#profile').click(function() {
		$('#sub-profile').toggle('display');
	});
});