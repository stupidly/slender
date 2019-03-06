$(function () {
	initMessages();
});

function initMessages(timeout = 2000){
	$("div.toast")
		.toast({
			delay: timeout
		})
		.toast("show");
}