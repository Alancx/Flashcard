var attrlist=new Array();/////选择的商品属性列表
var showimglist= new Array();/////商品展示图列表
var attrvlist=new Array();/////修改时规格，无法选择其他规格
var attrsvarray=new Array();////记录选择几中属性
var BCstype="";/////保存数据是新增，还是修改
$(document).ready(function () {
	$('.editbox').css('width',$('.partproitem').width()-80+"px");
	$('.jfedit').css('width',$('.partproitem').width()-140+"px");
	$('.outpart').css('height',$(window).height()-40+"px");
	BCstype=$('#addpro').attr('data-type');
	////修改使设置分类选项////////////////////
	if(SClassid!=''){
		var classid ="";
		var option;
		$.each(classdata,function(index,item){
			if((item.ClassGrade==2)&&(item.ClassId==SClassid)){
				$('#selclass-1').find("option[value='"+item.ParentClassId+"']").prop("selected",true);
				classid=item.ParentClassId;
				$.each(classdata,function(i,s){
					if((s.ClassGrade==2)&&(s.ParentClassId==classid)){
						option=$("<option>").val(s.ClassId).text(s.ClassName);
						$('#selclass-2').append(option);
					}
				})
				$('#selclass-2').find("option[value='"+SClassid+"']").prop("selected",true);
			}
		})
	}
	///修改时加载展示图片信息////
	if(Simages!=''){
		$.each(eval('('+Simages+')'),function(simgid,simgurl){
			var htmls='<div class="seledimg">'+
			'<img src='+simgurl+' class="simage">'+
			'</div>';
			$(".addimg").before(htmls);
			showimglist.push(simgurl);
		})
	}
	///长按删除图片//////////////
	$('#esc').click(function(){
		hidetips('confirm');
	})
	$('#enter').click(function(){
		var simgsrc=$(this).attr('data-simg');
		$.each($('.simage'),function(i,k){
			if(simgsrc==$(k).attr('src')){
				showimglist.splice($.inArray($(k).attr('src'),showimglist),1);
				$(k).parent().remove();
			}
		})
		hidetips('confirm');
	})
	$('.simage').on('touchstart',function(event){
		event.preventDefault();//阻止其他事件
		if (event.originalEvent.targetTouches.length == 1) {
			var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
			$('#enter').attr('data-simg',$(this).attr('src'));
			tips('confirm', '确定要删除图片?', 10000);
		}
	})

	///修改时加载的商品属性信息////////
	if(Attrsdata!=''){
		$.each(eval('('+Attrsdata+')'),function(aindex,aitem){
			$.each(aitem,function(avindex,avitem){
				$("#"+avindex).addClass('sactive');
				var Attrvs= $("#"+avindex).attr('data-attr')+'|'+$("#"+avindex).attr('data-attrvid')+'|'+$("#"+avindex).attr('data-attrvalue');
				attrlist.push(Attrvs);
			})
			attrvlist.push(aindex);
		})
		//////隐藏不能选择的属性/////////////
		$.each($('.AttrRow'),function(trindex,tritem){
			var data_attr=$(tritem).attr('data-attr');
			if($.inArray(data_attr,attrvlist)== -1){
				$(tritem).css('display','none');
			}
		})
	}
	///展开和关闭事件
	$('.pitemright').click(function(){
		if($(this).next().css('display')=='none'){
			$(this).next().css('display','block');
			$('>span',this).css('background-image',"url('" + imgurl + "Arrow-2.png')");
			$('>span',this).css('background-size','13px');
		} else{
			$(this).next().css('display','none');
			$('>span',this).css('background-image',"url('" + imgurl + "Arrow-1.png')");
			$('>span',this).css('background-size','7px');
		}

	})
	//选择分类事件
	$('#selclass-1').change(function(){
		var classid =$('#selclass-1').val();
		var option;
		$('#selclass-2').empty();
		option=$("<option>").val(-1).text("请选择");
		$('#selclass-2').append(option);
		if(classid!='-1'){
			$.each(classdata,function(index,item){
				if((item.ClassGrade==2)&&(item.ParentClassId==classid)){
					option=$("<option>").val(item.ClassId).text(item.ClassName);
					$('#selclass-2').append(option);
				}
			})
		}
	})
	//////选择商品属性/////////
	$('.attrS').click(function(){
		var Attrp=$(this).attr('data-attr').split('_')[1]+'_'+$(this).attr('data-attr').split('_')[2];
		var Attrs= $(this).attr('data-attr')+'|'+$(this).attr('data-attrvid')+'|'+$(this).attr('data-attrvalue');
		var attrsv=$(this).attr('data-attr');
		if((attrvlist.length>0) && ($.inArray(Attrp,attrvlist))== -1){
			return;
		}
		if((attrsvarray.length>=3) && ($.inArray(attrsv,attrsvarray)==-1)){
			return;
		}
		if($(this).hasClass('sactive')){
			$(this).removeClass('sactive');
			attrlist.splice($.inArray(Attrs,attrlist),1);
		} else{
			$(this).addClass('sactive');
			attrlist.push(Attrs);
		}
		var resattr=false;
		$.each($(this).parent().children(),function(i,k){
			if($(k).hasClass('sactive')){
				resattr=true;
			}
		})
		if(resattr){
			if($.inArray(attrsv,attrsvarray)==-1){
				attrsvarray.push(attrsv);
			}
		} else{
			attrsvarray.splice($.inArray(attrsv,attrsvarray),1);
		}
	})
	/////商品主图上传//////////////
	oFReader = new FileReader(),
	rFilter = /^(?:image\/jpeg|image\/png)$/i;
	oFReader.onload = function (oFREvent) {
		qdhimage();
	};
	/////商品展示上传//////////////
	osFReader = new FileReader(),
	rsFilter = /^(?:image\/jpeg|image\/png)$/i;
	osFReader.onload = function (oFREvent) {
		qdsimage();
	};
	////保存商品信息///////////////
	$('#addpro').click(function(){
		if($('#hdimage').val()==''){
			tips('notice', '添加商品主图!', 1500, 'weui_icon_notice');
			return;
		} else if($('#proname').val()==''){
			tips('notice', '填写商品名称!', 1500, 'weui_icon_notice');
			return;
		} else if(showimglist.length==0){
			tips('notice', '添加商品展示图,最少一张!', 1500, 'weui_icon_notice');
			return;
		} else if(($('#selclass-1').val()=='-1')||($('#selclass-2').val()=='-1')){
			tips('notice', '选择商品分类!', 1500, 'weui_icon_notice');
			return;
		} else if(attrlist.length==0){
			tips('notice', '选择商品属性!', 1500, 'weui_icon_notice');
			return;
		} else if($('#protilte').val()==''){
			tips('notice', '填写商品标题!', 1500, 'weui_icon_notice');
			return;
		} else if($('#proprice').val()==''){
			tips('notice', '填写商品价格!', 1500, 'weui_icon_notice');
			return;
		} else if($('#prosaleprice').val()==''){
			tips('notice', '填写商品出售价格!', 1500, 'weui_icon_notice');
			return;
		} else if($('#proweight').val()==''){
			tips('notice', '填写商品重量!', 1500, 'weui_icon_notice');
			return;
		}

		///////获取填写信息/////////////////
		var Mimage=$('#hdimage').val();//商品主图
		var Proname=$('#proname').val();//商品名称
		var ClassType=$('#selclass-2').val();//类别编号
		var ClassName=$('#selclass-2 option:selected').text();//类别名称
		var Protitle=$('#protilte').val();//商品标题
		var Pronote=$('#pronote').val();//商品说明
		var Proprice=$('#proprice').val();//商品价格
		var Prosaleprice=$('#prosaleprice').val();//商品出售价格
		var Pronumber=$('#pronumber').val();//商品编号
		var Procode=$('#procode').val();//商品条码
		var Proweight=$('#proweight').val();//商品重量
		var Promark=$('#promark').val();//备注说明
		var Prosearch=$('#prosearch').val();//检索关键字
		var Proecom=$('#proecom').val();//员工提成
		var Proonecom=$('#proonecom').val();//一级提成
		var Propcom=$('#propcom').val();//推广佣金
		///是否使用优惠券//////////
		if($('#checkbox_c1').prop("checked")){
			var Proconpon='1';
		} else {
			var Proconpon='0';
		}
		///是否为赠品//////////
		if($('#checkbox_c2').prop("checked")){
			var Progift='1';
		} else {
			var Progift='0';
		}
		///是否为积分兑换商品//////////
		if($('#checkbox_c3').prop("checked")){
			var Proisredeem='1';
			var Proredeem=$('#proredeem').val();
		} else {
			var Proisredeem='0';
			var Proredeem=$('#proredeem').val();
		}
		///////////商品属性信息/////////
		var jsonAttrs={};
		var ArrayAttrs=[];
		attrlist.sort(function(a,b){
			var m= (a.split("|")[0]).split("_")[0];
			var n=(b.split("|")[0]).split("_")[0];
			return m-n;
		});
		for (var i = 0; i < attrlist.length; i++) {
			var jsonAttrv={};
			var attrN=(attrlist[i].split("|")[0]).split("_")[1]+"_"+(attrlist[i].split("|")[0]).split("_")[2];
			jsonAttrv['_'+attrlist[i].split("|")[1]]=attrlist[i].split("|")[2];
			if(jsonAttrs[attrN]){
				jsonAttrs[attrN]['_'+attrlist[i].split("|")[1]]=attrlist[i].split("|")[2];
			} else{
				jsonAttrs[attrN]=jsonAttrv;
			}
			if($.inArray(attrN,ArrayAttrs)== -1){
				ArrayAttrs.push(attrN);
			}
		}
		var attrsv=combination(attrlist,ArrayAttrs.length);
		var jsonsttrv={};
		for (var i = 0; i < attrsv.length; i++) {
			var temAttr=attrsv[i].split(",");
			var attrvid="";
			var attrvv="";
			for (var j = 0; j < temAttr.length; j++) {
				attrvid+='_'+temAttr[j].split("|")[1];
				if(j+1==temAttr.length){
					attrvv+=temAttr[j].split("|")[2];
				}else{
					attrvv+=temAttr[j].split("|")[2]+'|';
				}
			}
			jsonsttrv[i]=attrvid+'|'+attrvv;
		}
		var attrjson=JSON.stringify(jsonAttrs);
		var attrvjson=JSON.stringify(jsonsttrv);
		if((attrvlist.length>0)&&(attrvlist.length!=ArrayAttrs.length)){
			tips('notice', '选择商品属性!', 1500, 'weui_icon_notice');
			return;
		}
		/////////展示图信息////////////
		var jsonShowimg={};
		for (var i = 0; i < showimglist.length; i++) {
			jsonShowimg[i]=showimglist[i];
		}
		var showimgjson=JSON.stringify(jsonShowimg);
		var prodatainfo="ProLogoImg="+Mimage+"&ProName="+Proname+"&ProShowImg="+showimgjson+'&ClassType='+ClassType+
		'&ClassName='+ClassName+'&ProAttrs='+attrvjson+'&ProAttrList='+attrjson+'&ProTitle='+Protitle+'&ProSubtitle='+Pronote+
		'&Price='+Proprice+'&PriceRange='+Prosaleprice+'&ProNumber='+Pronumber+'&Barcode='+Procode+
		'&Weight='+Proweight+'&Remarks='+Promark+'&KeyWord='+Prosearch+'&EmpCut='+Proecom+'&Cut='+Proonecom+
		'&ExtendCut='+Propcom+'&IsUseConpon='+Proconpon+'&Iszp='+Progift+'&IsUseScore='+Proisredeem+'&Score='+Proredeem+'';
		if($(this).attr('data-type')=='proadd'){
			prodatainfo=prodatainfo+'&Ptype=proadd';
		} else{
			prodatainfo=prodatainfo+'&Ptype=proupdate'+'&Proid='+$(this).attr('data-pid');
		}
		// console.log(prodatainfo);
		tips('waiting','保存数据中···');
		$.ajax({
			url:prosaveurl,
			type:"post",
			data:prodatainfo,
			dataType:"json",
			success:function(msg){
				if (msg.status=='true') {
					tips('notice', '保存成功!', 1500, 'weui_icon_toast');
					window.location.href=prosaveyes;
				} else{
					tips('notice', '保存失败!', 1500, 'weui_icon_notice');
				}
			},
			complete: function (e) {
				hidetips('waiting');
			},
			error: function (data,status,e)//服务器响应失败处理函数
			{
				//////
			}
		})

	})

})

//////选择商品主图上传图片/////
function selhimage(ifile){
	if (ifile.files.length === 0){
		return;
	}
	var oFile = ifile.files[0];
	if (!rFilter.test(oFile.type)) {
		tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
		return;
	}else{
		oFReader.readAsDataURL(oFile);
	}
}
//////选择商品展示上传图片/////
function selsimage(ifile){
	if (ifile.files.length === 0){
		return;
	}
	var oFile = ifile.files[0];
	if (!rsFilter.test(oFile.type)) {
		tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
		return;
	}else{
		osFReader.readAsDataURL(oFile);
	}
}

//////确定选择主图///////////////
function qdhimage(){
	$.ajaxFileUpload({
		url: proimagesave, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'selhimg', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
				$("#hdimage").val(data.datainfo);
				$("#himage").attr("src",data.datainfo);
			}else{
				$("#hdimage").val('');
				tips('notice', '上传失败!', 1500, 'weui_icon_notice');
			}
		},
		complete: function (e) {

		},
		error: function (data,status,e)//服务器响应失败处理函数
		{
			tips('notice', '上传失败!', 1500, 'weui_icon_notice');
		}
	})
}

//////确定选择展示图///////////////
function qdsimage(){
	var htmls="";
	$.ajaxFileUpload({
		url: proimagesave, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'selswimg', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
				htmls='<div class="seledimg">'+
				'<img src='+data.datainfo+' class="simage">'+
				'</div>';
				$(".addimg").before(htmls);
				$('.simage').on('touchstart',function(event){
					event.preventDefault();//阻止其他事件
					if (event.originalEvent.targetTouches.length == 1) {
						var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
						$('#enter').attr('data-simg',$(this).attr('src'));
						tips('confirm', '确定要删除图片?', 10000);
					}
				})
				showimglist.push(data.datainfo);
			}else{
				tips('notice', '上传失败!', 1500, 'weui_icon_notice');
			}
		},
		complete: function (e) {

		},
		error: function (data,status,e)//服务器响应失败处理函数
		{
			tips('notice', '上传失败!', 1500, 'weui_icon_notice');
		}
	})
}
///////属性数组组合算法///////////////////
function combination(arr,len){
	var result=[];
	if(len==1){
		for (var i = 0; i < arr.length; i++) {
			result.push(arr[i]);
		}
	} else if(len==2){
		for (var i = 0; i < arr.length-1; i++) {
			for (var j = i+1; j < arr.length; j++) {
				if(arr[i].split("|")[0]!=arr[j].split("|")[0]){
					result.push(arr[i]+','+arr[j]);
				}
			}
		}
	} else{
		for(var i=0;i<arr.length-(len-1);i++){
			var temp=arr[i];
			var atemp="";
			var temparr=[];
			atemp=arr[i].split("|")[0];
			temparr.push(atemp);
			for(var start=i;start<arr.length-(len-1);start++){
				for(var n=1;n<len-1;n++){
					temp+=","+arr[start+n];
					atemp=arr[start+n].split("|")[0];
					temparr.push(atemp);
				}
				for(var k=start+n;k<arr.length;k++){
					var temparrs=[];
					temparrs=temparr.slice();
					atemp=arr[k].split("|")[0];
					temparrs.push(atemp);
					if(!isRepeat(temparrs)){
						result.push(temp+","+arr[k]);
					}
				}
				temp=arr[i];
				temparr=[];
				atemp=arr[i].split("|")[0];
				temparr.push(atemp);
			}
		}
	}
	return result;
}
///////判断数组是否存在相同元素//////////////
function isRepeat(arr){
	var hash = {};
	for(var i in arr) {
		if(hash[arr[i]])
		return true;
		hash[arr[i]] = true;
	}
	return false;
}
