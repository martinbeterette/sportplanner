$(document).ready(function() {

	$('.profile-button').on('click', function() {
		$('.profile-dropdown').css('display', 'block');
	});

	$('.profile-dropdown').on('mouseleave', function() {
   		$('.profile-dropdown').css('display', 'none');
	});

});