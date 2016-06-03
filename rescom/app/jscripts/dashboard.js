$(document).ready(function() {
	$('#search-trigger').on('click', function() {
		$(this).parent().find('.search-box').toggle("display");
	});
});