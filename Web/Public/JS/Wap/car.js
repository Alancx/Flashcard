// $(document).ready(function(){
// 	$(".attr span").click(function(){
// 		if ($(this).attr('class')=='atv') {
// 			$(this).attr('class','actv');
// 		}else{
// 			$(this).attr('class','atv');
// 		}
// 	})

// 	$("#min").click(function(){
// 		var num=$("#nums").val();
// 		var newnum=parseInt(num)-1;
// 		var sy=$("#allnums").val();
// 		var newsy=parseInt(sy)+1;
// 		if (num<=1) {
// 			$("#nums").val(1);
// 		}else{
// 			$("#nums").val(newnum);
// 			$("#sy").html(newsy);
// 			$("#allnums").val(newsy);
// 		}
// 	})
// 	$("#add").click(function(){
// 		var num=$("#nums").val();
// 		var newnum=parseInt(num)+1;
// 		var sy=$("#allnums").val();
// 		var newsy=parseInt(sy)-1;
// 		$("#nums").val(newnum);
// 		$("#allnums").val(newsy);
// 		$("#sy").html(newsy);
// 	})
// 	$("#nums").blur(function(){
// 		var nums=$("#nums").val();
// 		var sy=$("#all").val();
// 		if (!nums) {
// 			$("#nums").val(1);
// 			$("#sy").html(sy);
// 		}
// 		if (parseInt(nums)<=1) {
// 			$("#nums").val(1);
// 			$("#sy").html(sy);
// 		}else{
// 			$("#sy").html(parseInt(sy)-parseInt(nums));
// 		}
// 	})

// });
