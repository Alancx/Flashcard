// $(document).on('click','.edit',function(){
	$('.edit').click(function(){
	if($(this).parent().next('.eject').css('display')=='none'){
		$(this).parent().next().css('display','block');
	} else{
		$(this).parent().next().css('display','none');
	}
})
