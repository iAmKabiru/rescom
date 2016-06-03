$(document).ready(function() {

	$('#getstudents-form').on('submit', function(event) {
		$modal = $('#infoModal');
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

	$('#year').prop('disabled', true);
	$('#term').prop('disabled', true);	

	$('#session').on('change', function() {

		var data = {
			getyear: 'getyear',
			sessionid: $(this).val()
		}

		$.ajax({
			method: "post",
			url: "../controllers/compile.php",
			//dataType: "json",
			data: data,
			success: function(response) {
				$year = $('#year');
				$term = $('#term');
				//var res = $.parseJSON(response);
				$year.html("");
				$year.prepend('<option value="'+response+'">'+response+'</option>');
				$year.prop('disabled', false);
				$term.prop('disabled', false);
			}
		});
	});

	$('#class').on('change', function() {
		var data = {
			getsessions: 'getsessions'
			//classid: $(this).val()
		}
		$.ajax({
			method: "post",
			url: "../controllers/compile.php",
			//dataType: "json",
			data: data,
			success: function(response) {
				console.log(response);
				$session = $('#session');
				$year = $('#year');
				var res = $.parseJSON(response);
				$session.html("");
				$year.html("");	
				if (res.length == 0){
					$session.prop('disabled', true);
					return false;
				}
				res.forEach(function(obj) {
					$session.prepend('<option value="'+obj.sessionid+'">'+obj.session+'</option>');
				});
				$session.prepend('<option selected="selected">Select Session</option>');
				$session.prop('disabled', false);
			}
		});
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

function compile(adno, classidx, session, term, yr) {
	if (confirm("You are about to compile result sheet for the selected student, please verify that the session and term selected are the appropriate ones. Continue?")) {
		var data = {
			admissionno: adno,
			classid: classidx,
			sessionid: session,
			termid: term,
			year: yr,
			compileform: "compileform"
		};


		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: data,
			success: function(response) {
				$('.content').find('.section-panel').find("h1.panel-title").text("Result Data");
				$('.content').find('.section-panel').find("div.panel-body").html(response);
			}
		});
	}
}

function edit(adno, classidx, session, term, yr) {
	if (confirm("You are about to edit result sheet for the selected student, please verify that the session and term selected are the appropriate ones. Please note that once edited, the old result data will be overwritten by the new result data. Continue?"))
	{
		var data = {
			admissionno: adno,
			classid: classidx,
			sessionid: session,
			termid: term,
			year: yr,
			editresult: "editresult"
		};


		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: data,
			success: function(response) {
				$('.content').find('.section-panel').find("h1.panel-title").text("Result Data");
				$('.content').find('.section-panel').find("div.panel-body").html(response);
			}
		});	
	}
}

function getTotal(input) {
	var value = 0;

	$total = $(input);

	$total.parent().prevAll().find('input').not('input[class*="subjectid"]').each(function() {
		value += parseInt($(this).val());
	});

	//value = value*1 + parseInt($total.val());

	if (value > 100) {
		//alert('The total score for each course cannot be greater than a 100 percentile');
		$total.attr('value', 0);
		//return false;
	} else {
		$total.attr('value', value);
		$grade = $total.parent().nextAll().find('input[class="grade"]');
		$inter = $total.parent().nextAll().find('input[class="inter"]');
		

		if ($('#calcAvg').val() == "true") {
			calculateAverage($total.parent().prevAll().find('input.subjectid'), value);
		}

		if (value >= 75) {
			$grade.attr('value', 'A');
			$inter.attr('value', 'Excellent');
		} else if (value >= 65) {
			$grade.attr('value', 'B');
			$inter.attr('value', 'Good');
		} else if (value >= 55) {
			$grade.attr('value', 'C');
			$inter.attr('value', 'Credit');
		} else if (value >= 50) {
			$grade.attr('value', 'D');
			$inter.attr('value', 'Pass');
		} else {
			$grade.attr('value', 'E');
			$inter.attr('value', 'Fail');
		}
	}	
}

function calculateAverage($subjectid, total) {
	var data = {
			admissionno: $('#admissionno').val(),
			classid: $('#classid').val(),
			sessionid: $('#sessionid').val(),
			termid: $('#termid').val(),
			year: $('#year').val(),
			subjectid: $subjectid.val(),
			score: total,
			computeavg: "computeavg"
	};
	$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: data,
			success: function(response) {
				$average = $total.parent().nextAll().find('input[class="average"]').attr('value', response);
			}
	});
}

function process_form1(form) {
	event.preventDefault();
	$form = $(form);
	var res = confirm("Click OK to continue if the student's result data entered is correct to the best of your knowledge, otherwise click cancel and review!");
	if (res) {
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: $form.serialize(),
			success: function(response) {
				$.unblockUI();
				$('.content').find('.section-panel').find("h1.panel-title").text("Result Data");
				$('.content').find('.section-panel').find("div.panel-body").html(response);
			}
		});		
	}
	//console.log($form.serialize());
}

function process_form2(form) {
	event.preventDefault();
	$form = $(form);
	$modal = $('#infoModal');
	var res = confirm("Click OK to compile if the student's result data entered is correct to the best of your knowledge, otherwise click cancel and review!");
	if (res) {
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: $form.serialize(),
			success: function(response) {
				$.unblockUI();
				switch (response) {
					case 'success':
					{
						$modal.find('.modal-title').text('Operation Successful!');
						$modal.find('#msg').text('The Result has been compiled successfully for the specified student. This window will refresh in 5 seconds...');
						$modal.modal('show');
						setTimeout(function() {
							location.reload(true);
						}, 5000);
					} break;
					case 'failed':
					{
						$modal.find('.modal-title').text('Technical Error');
						$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
						$modal.modal('show');		
					} break;
				}
			}
		});		
	}
	//console.log($form.serialize());
}

function process_edit_form1(form) {
	event.preventDefault();
	$form = $(form);
	var res = confirm("Click OK to continue if the student's result data entered is correct to the best of your knowledge, otherwise click cancel and review!");
	if (res) {
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: $form.serialize(),
			success: function(response) {
				$.unblockUI();
				$('.content').find('.section-panel').find("h1.panel-title").text("Result Data");
				$('.content').find('.section-panel').find("div.panel-body").html(response);
			}
		});		
	}
	console.log($form.serialize());
}

function process_edit_form2(form) {
	event.preventDefault();
	$form = $(form);
	$modal = $('#infoModal');
	var res = confirm("Click OK to edit and compile if the student's result data entered is correct to the best of your knowledge, otherwise click cancel and review!");
	if (res) {
		$.blockUI();
		$.ajax({
			method: 'post',
			url: '../controllers/compile.php',
			data: $form.serialize(),
			success: function(response) {
				//console.log(response);
				$.unblockUI();
				switch (response) {
					case 'success':
					{
						$modal.find('.modal-title').text('Operation Successful!');
						$modal.find('#msg').text('The Result has been edited and compiled successfully for the specified student. This window will refresh in 5 seconds...');
						$modal.modal('show');
						setTimeout(function() {
							location.reload(true);
						}, 5000);
					} break;
					case 'failed':
					{
						$modal.find('.modal-title').text('Technical Error');
						$modal.find('#msg').text('A technical error has occured during operation process, please try again. If this error persists please contact the system administrator.');
						$modal.modal('show');		
					} break;
				}
			}
		});		
	}
	//console.log($form.serialize());
}

function addComment(input) {
	$element = $(input);
	switch ($element.attr('class')) {
		case 'A': {
			$text = $element.parent().prevAll().text();
			if (/principal/i.test($text)) {
				$('#principalcomment').val('Excellent performance keep going higher');
			} else if (/form/i.test($text)) {
				$('#formcomment').val('An excellent result, keep it up');
			}
		} break;
		case 'B':{
			$text = $element.parent().prevAll().text();
			if (/principal/i.test($text)) {
				$('#principalcomment').val('Brilliant performance');
			} else if (/form/i.test($text)) {
				$('#formcomment').val('A good performance, do not relent; you can do better');
			}
		} break;
		case 'C':{
			$text = $element.parent().prevAll().text();
			if (/principal/i.test($text)) {
				$('#principalcomment').val('Good effort, keep it up');
			} else if (/form/i.test($text)) {
				$('#formcomment').val('He/She is a serious student');
			}
		} break;
		case 'D': {
			$text = $element.parent().prevAll().text();
			if (/principal/i.test($text)) {
				$('#principalcomment').val('Concentrate on your weak areas');
			} else if (/form/i.test($text)) {
				$('#formcomment').val('A good performance, do not relent; you can do better');
			}
		} break;
		case 'E': {
			$text = $element.parent().prevAll().text();
			if (/principal/i.test($text)) {
				$('#principalcomment').val('There is room for improvement, work harder next term');
			} else if (/form/i.test($text)) {
				$('#formcomment').val('He/She needs to sit up');
			}
		} break;
	}
}