var temp=null;
$(document).ready(function(){

	$("#cartModal").on("shown.bs.modal",function(e){
		//alert($(e.relatedTarget).attr("pdata-pid"));

		$.ajax({
          //提交数据的类型 POST GET
          type: "POST",
          //提交的网址
          url: "{:U('Index/GetClass')}",
          //提交的数据
          data: {type: 'GetClass'},
          //返回数据的格式
          datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
          //在请求之前调用的函数

          beforeSend: function () {

          },
          //成功返回之后调用的函数
          success: function (data) {
              if (data.status==0) {

              }
              else
              {

              }
          },
          //调用出错执行的函数
          error: function () {
              //请求出错处理
          },
          //调用执行后调用的函数
          complete: function (XMLHttpRequest, textStatus) {

          }
      });
	});


	$(".attr span").click(function(){
		if ($(this).attr('class')=='atv') {
			$(this).attr('class','actv');
		}else{
			$(this).attr('class','atv');
		}
	});

	$("#min").click(function(){
		var num=$("#nums").val();
		var newnum=parseInt(num)-1;
		var sy=$("#allnums").val();
		var newsy=parseInt(sy)+1;
		if (num<=1) {
			$("#nums").val(1);
		}else{
			$("#nums").val(newnum);
			$("#sy").html(newsy);
			$("#allnums").val(newsy);
		}
	});
	$("#add").click(function(){
		var num=$("#nums").val();
		var newnum=parseInt(num)+1;
		var sy=$("#allnums").val();
		var newsy=parseInt(sy)-1;
		$("#nums").val(newnum);
		$("#allnums").val(newsy);
		$("#sy").html(newsy);
	})
	$("#nums").blur(function(){
		var nums=$("#nums").val();
		var sy=$("#all").val();
		if (!nums) {
			$("#nums").val(1);
			$("#sy").html(sy);
		}
		if (parseInt(nums)<=1) {
			$("#nums").val(1);
			$("#sy").html(sy);
		}else{
			$("#sy").html(parseInt(sy)-parseInt(nums));
		}
	})

});
