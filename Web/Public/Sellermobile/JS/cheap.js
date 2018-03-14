$(document).on('change', '.xia select', function() {
	var number=$(this).val();
	var _htmls='';
	$('.zhong').html(_htmls);
	for(var i=1;i <= number;i++) {
		_htmls +='<label >第'+i+'次￥</label>' + '<input type="text" data-level="Lv'+i+'"/>';
	}
	$('.zhong').html(_htmls);
}

)