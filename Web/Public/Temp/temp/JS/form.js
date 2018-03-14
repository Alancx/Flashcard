var public_url="a.php?shop/";
$(function () {
	setInterval(function showimg(){$("img[src='']:eq(0)").attr("src",$("img[src='']:eq(0)").attr("u"));},300);
	$(".kucun span").html(0);
	for(var i=0;i<$(".right").length;i++){
		$(".right").eq(i).find(".cvalue").eq(0).addClass("checked")
	}
	$(".cvalue").click(function () {
		$(this).addClass("checked");
		$(this).siblings().removeClass("checked");
		var str="";
		for(var i=0;i<$(".cvalue.checked").length;i++){
			str+=" × "+$(".cvalue.checked").eq(i).html();
		}
		str=str.substr(3);
		var attri=$(".product").attr("u").split('@@@');
		var num=0;
		for(var i=0;i<attri.length;i++){
			if(str==attri[i]){
				num=attri[i+1];				
			}
		}
		$(".kucun span").eq(0).html(num);
		$("#num").val(1);
	})
	$(".cvalue").eq(0).click();
	$("#sub").click(function () {
		var num = $("#num").val();
		if(num>1)num--;
		$("#num").val(num);
	})
	$("#num").blur(function(){
		if(parseInt($(this).val())!=$(this).val())$(this).val(1);
		if($(this).val()*1>$(".kucun span").html()*1){
			$(this).val($(".kucun span").html()*1);
		}
		if($(this).val()*1<1){
			$(this).val(1);
		}
	})
	$('.canshu table tr td:first').css("width","155");
	$("#share_out").click(function(){
			$(".share1").css("display","inline-block");
			$(".share3").css("display","inline-block");
		})
	$(".close").click(function(){
			$(".share1").css("display","none");
			$(".share3").css("display","none");
		})
	$("#add").click(function () {
		var num = $("#num").val();
		if(num<$(".kucun span").html()*1)num++;
		$("#num").val(num);
	})
	$('.foot_right').click(function(){
		$(".foot_right").css('background-color','#000');
		setTimeout(function(){
			$(".foot_right").css('background-color','#d7161b');
		},200);
		if($("#num").val()*1>$(".kucun span").html()*1){
			alert("库存不足！");
			return false;
		}
		var attri="";
		for(var i=0;i<$(".cvalue.checked").length;i++){
			attri+=$(".cvalue.checked").eq(i).html()+" × "
		}
		attri=attri.substr(0,attri.length-3);
		$.get(public_url+"cart/add/"+$("#id").val()+"@@@"+encodeURIComponent(attri)+"@@@"+$("#num").val(),function(data,status){
			$("#shuliang").html(data);
		});
	})	
});
function selectTag(showContent,selfObj){
	// 操作标签
	var tag = document.getElementById("tags").getElementsByTagName("li");
	var taglength = tag.length;
	for(i=0; i<taglength; i++){
		tag[i].className = "";
	}
	selfObj.className = "selectTag";
	// 操作内容
	for(i=0; j=document.getElementById("tagContent"+i); i++){
		j.style.display = "none";
	}
	document.getElementById(showContent).style.display = "block";	
}