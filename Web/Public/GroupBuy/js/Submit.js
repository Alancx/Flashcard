$(function(){
	//提交
	$("#subtn").on('tap',function(){
		$(".mask").show(200);
		$(".zhifufangshi").show(300);
	})
	
	//取消提交
	$(".mask").on('tap',function(){
		$(".mask").hide(200);
		$(".zhifufangshi").hide(300);
	})
	
	//选择地址
$(".address").on('tap',function(){
	mui.toast('加载中...');
	$.ajax({
		url:getaddrdata,
		type:"post",
		data:"1=1",
		dataType:"json",
		success:function(msg){
			if (msg.status=='success') {
				mui.toast('加载完成');
				var data=msg.data;
				addrbox=data;
				var _html='';
				$.each(data,function(index,item){
					var ismr='<small class="cfonts"></small><small class="setmoren">设为默认</small>';
					if (item.IsDefault=='1') {
						ismr='<small class="cfonts true"></small><small class="setmoren">默认</small>';
					};
					var isdel='<span class="shanchu"><small class="cfonts delete"></small><small>删除</small></span>';
					if (item.ReceivingId==$('#addressid').val()) {
						isdel='';
					};
					_html+='<ul class="addr" data-id="'+item.ReceivingId+'"> <li> <span>'+item.Name+'</span>&nbsp;&nbsp;&nbsp;<span>'+item.Phone+'</span> <small class="cfonts mycls"></small> <p>'+item.Province+item.City+item.Area+item.Address+'</p> </li> <li> <span class="moren">'+ismr+'</span> <span class="caozuo"> '+isdel+' <span class="bianji"><small class="cfonts edit"></small><small>编辑</small></span> </span> </li> </ul>';
				})
				$('#SubScroll').html(_html);
				$('#AddrG').show(200);
			}else{
				mui.toast(msg.info);
			}
		}
	})
})	
	$("#AddrClose").on('tap',function(){
	$('#AddrG').hide(200);
})

	$(document).on('tap','.addr',function(){
		$('.mycls').removeClass('xuanzhong');
		$(this).find('.mycls').addClass('xuanzhong');
		// $('.addr').find('.xuanzhong').hide();
		// $(this).find('.xuanzhong').show();
	})











// 地址管理
$(function(){
	
	$(document).on('tap','.setmoren',function(){
		var _this=$(this);
		var rid=_this.parent().parent().parent().attr('data-id');
		mui.toast('处理中...');
		$.ajax({
			url:setmr,
			type:"post",
			data:"rid="+rid,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					_this.text('默认');
					_this.parent().children('.cfonts').addClass('true');
					_this.parents('.addr').siblings().find('.moren').children('.cfonts').removeClass('true');
					_this.parents('.addr').siblings().find('.moren').children('.setmoren').text('设为默认');
					_this.parents('.addr').prependTo('#SubScroll');
				}else{
					mui.toast('操作失败');
				}				
			}
		})
		
	})
	
	
	//删除
	$(document).on('tap','.shanchu',function(){
		var _this=$(this);
		mui.confirm('是否删除该地址','提示',['取消','确认'],function (e) {
			var rid=_this.parent().parent().parent().attr('data-id');
			$.ajax({
				url:deleteaddr,
				type:"post",
				data:"rid="+rid,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						mui.toast('删除成功');
						_this.parent().parent().parent().remove();
					}else{
						mui.toast(msg.info);
					}
				}
			})
		},'div')
	})
	
	//编辑
	$(document).on('tap','.bianji',function(){
		var _this=$(this);
		clradr();
		$('#saveaddr').attr('data-type','old');
		var rid=_this.parent().parent().parent().attr('data-id');
		$.each(addrbox,function(index,item){
			if (rid==item.ReceivingId) {
				$('#RecevingName').val(item.Name);
				$('#RecevingPhone').val(item.Phone);
				$('#address').val(item.Address);
				$('#sheng').text(item.Province);
				$('#shi').text(item.City);
				$('#qu').text(item.Area);
				$('#rid').val(item.ReceivingId);
			};
		})
		$('#AddrEdit').show();
})	
	
//新建
	$(".newadd").on('tap',function(){
		$('#saveaddr').attr('data-type','new');
		$('#AddrEdit').show();
		clradr();
})	
	//取消
	$("#EditClose").on('tap',function(){
		$('#AddrEdit').hide();
})	
	$('#saveaddr').on('tap',function(){
		var RecevingName=$('#RecevingName').val();
		var RecevingPhone=$('#RecevingPhone').val();
		var address=$('#address').val();
		var Province=$('#sheng').text();
		var City=$('#shi').text();
		var Area=$('#qu').text();
		var rid=$('#rid').val();
		console.log(Province+City+Area+address+RecevingPhone+RecevingName);
		if (RecevingName && RecevingPhone && Province && City && Area && address) {
			$.ajax({
				url:saveaddrurl,
				type:"post",
				data:"Phone="+RecevingPhone+"&Name="+RecevingName+"&Province="+Province+"&City="+City+"&Area="+Area+"&Address="+address+"&type="+$('#saveaddr').attr('data-type')+"&rid="+rid,
				dataType:"json",
				success:function(msg){
					if (msg.status=='success') {
						mui.toast('保存成功');
						$('.name').html(RecevingName);
						$('.phone').html(RecevingPhone);
						$('.ad').html(Province+City+Area+address);
						$('.addrinfo').html('<div class="namephone"> <span class="name">'+RecevingName+'</span>&nbsp;&nbsp;&nbsp;<span class="phone">'+RecevingPhone+'</span> </div> <div class="ad"> '+Province+City+Area+address+' <input type="hidden" name="" id="addressid" value="'+msg.rid+'" /></div>');
						$('#AddrEdit').hide();
						$('#AddrG').hide(200);
						//后续处理
					}else{
						mui.toast(msg.info);
					}
				}
			})
		}else{
			mui.toast('请完善收获地址信息');
		}
	})

	$(document).on('tap','#AddrOk',function(){
		var _this=$('.xuanzhong');
		var rid=_this.parent().parent().attr('data-id');
		if (rid) {
			$.each(addrbox,function(index,item){
				if (rid==item.ReceivingId) {
					$('.addrinfo').html('<div class="namephone"> <span class="name">'+item.Name+'</span>&nbsp;&nbsp;&nbsp;<span class="phone">'+item.Phone+'</span> </div> <div class="ad"> '+item.Province+item.City+item.Area+item.Address+' <input type="hidden" name="" id="addressid" value="'+item.ReceivingId+'" /></div>');
					$('#AddrG').hide(200);
				};
			})
		}else{
			mui.alert('请选择收获地址');
		}
	})
	$(document).on('tap','.zhifufangshi',function(){
		mui.toast('处理中...');
		$.ajax({
			url:saveorder,
			type:"post",
			data:"count="+count+"&glid="+glid+"&type="+ttype+"&rid="+$('#addressid').val()+"&gyid="+gyid,
			dataType:"json",
			success:function(msg){
				if (msg.status=='success') {
					//跳转到我的团购
					// mui.toast('处理成功');
					// window.location.href=mygroup;
					window.location.href=gopaying+'?ID='+msg.ID+'&type='+ttype;
					//去支付
				}else{
					mui.toast(msg.info);
				}
			}
		})
	})
	
	
})










	
	
	
})

function clradr(){
	$('#RecevingPhone').val('');
	$('#RecevingName').val('');
	$('#sheng').text('');
	$('#shi').text('');
	$('#qu').text('');
	$('#address').val('');
}
