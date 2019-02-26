$(function () {
	initFlash();
});

function initFlash(timeout = 2000){
	$(".alert-autoclosing").delay(timeout).fadeOut("slow", function () {
		$(this).remove();
	});
}