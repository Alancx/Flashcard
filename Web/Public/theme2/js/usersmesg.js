$(document).ready(function () {
	var mydate = new Date();
	new YMDselect('umyear', 'ummonth', mydate.getFullYear(), mydate.getMonth() + 1);
	  $('.umplist').css('height', $(document).height() - 80 + "px");
});
