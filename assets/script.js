$( document ).ready(function() {
	$(".thumbnail").css('width','300px');
	$(".thumbnail").css('height', '300px');

	$("#icon_link").click(function() {
		$(".topnav").toggleClass("responsive");
	});

});