var showimglist= new Array();/////商品展示图列表
var BCstype="";/////保存数据是新增，还是修改
var delproattrlist= new Array();//////修改时删除的商品信息////////
////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
  $('.editbox').css('width',$('.partproitem').width()-80+"px");
	$('.attredit').css('width',$('.attritem').width()-50+"px");
	$('.jfedit').css('width',$('.partproitem').width()-140+"px");
	$('.outpart').css('height',$(window).height()-40+"px");
  $('.weui_mask').css('z-index',31);
  $('.weui_dialog').css('z-index',32);
	BCstype=$('#addpro').attr('data-type');
  if ($('.partattrs').children().length==0) {
		$('.partattrs').css('display','none');
	} else {
		$('.partattrs').css('display','block');
	}
  ////修改使设置分类选项////////////////////
	if(SClassid!=''){
		var classid ="";
		$.each(classdata,function(index,item){
			if((item.ClassId==SClassid)){
				$('#selclass-1').find("option[value='"+item.ClassId+"']").prop("selected",true);
			}
		})
	}
  ///修改时加载展示图片信息////
	if(Simages!=''){
		$.each(eval('('+Simages+')'),function(simgid,simgurl){
			var htmls='<div class="seledimg">'+
			'<img src="'+simgurl+'" class="simage">'+
			'</div>';
			$(".addimg").before(htmls);
			showimglist.push(simgurl);
		})
	}
  ////////////////////////////////////////////////////////////////
  ////////////商品名称同步到商品标题上////////////////////
  $('#proname').bind({
    blur:function(){
      if (this.value != ""){
        $('#protilte').val(this.value);
      }
    }
  })
  //////////////////////////////////////////////////////////////////////////////////////
  ///单击删除图片//////////////
  $('#esc').click(function(){
    hidetips('confirm');
  })
  $('#enter').click(function(){
    var type = $(this).attr('data-type');
    var simgsrc=$(this).attr('data-simg');
    if (type=='1') {
      $.each($('.simage'),function(i,k){
        if(simgsrc==$(k).attr('src')){
          showimglist.splice($.inArray($(k).attr('src'),showimglist),1);
          $(k).parent().remove();
        }
      })
    } else if (type=='2') {
      var _this = $(".prodetailcontents img[src='"+simgsrc+"']");
      var pobj = _this.parent('p');
      _this.remove();
      if (pobj.find('img').length<=0) {
        pobj.remove();
      }
    }
    hidetips('confirm');
  })
  $('.simage').on('touchstart',function(event){
    event.preventDefault();//阻止其他事件
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      $('#enter').attr('data-simg',$(this).attr('src'));
      $('#enter').attr('data-type','1');
      tips('confirm', '确定要删除图片?', 10000);
    }
  })
  //////////////////////////////////////////////////////////////////////////////////
  //选择分类事件
	$('#selclass-1').change(function(){
		// var classid =$('#selclass-1').val();
		// var option;
		// $('#selclass-2').empty();
		// option=$("<option>").val(-1).text("请选择");
		// $('#selclass-2').append(option);
		// if(classid!='-1'){
		// 	$.each(classdata,function(index,item){
		// 		if((item.ClassGrade==2)&&(item.ParentClassId==classid)){
		// 			option=$("<option>").val(item.ClassId).text(item.ClassName);
		// 			$('#selclass-2').append(option);
		// 		}
		// 	})
		// }
	})
  //////////////////////////////////////////////////////////////////////////////////
  /////////点击添加属性/////////////////
	$('.attrspro').click(function(){
    var attrcount=$('.partattrs').children().length;
    var htmls='<div class="partattr" data-attrid="add">'+
      '<div class="attritem">'+
        '<label>规格</label>'+
        '<input type="text" class="attredit" name="attrtext" value="" placeholder="商品属性">'+
      '</div>'+
      '<div class="attritem" style="display:none;">'+
        '<label>编码</label>'+
        '<input type="text" class="attredit" name="proinputcode" value="" placeholder="商品编码">'+
      '</div>'+
      '<div class="attritem" style="display:none;">'+
        '<label>条码</label>'+
        '<input type="text" class="attredit" name="procode" value="" placeholder="商品条码">'+
      '</div>'+
      '<div class="attritem" style="display:none;>'+
        '<label>价格</label>'+
        '<input type="number" class="attredit" name="proaprice" value="0" placeholder="商品价格">'+
      '</div>'+
      '<div class="attritem" style="display:none;">'+
        '<label>库存</label>'+
        '<input type="number" class="attredit" name="procount" value="0" placeholder="商品库存">'+
      '</div>'+
      '<div class="removeattr"><span onclick="delattr(this)"></span></div>'+
    '</div>';
    $('.partattrs').append(htmls);
		$('.partattrs').css('display','block');
    $('.attredit').css('width',$('.attritem').width()-50+"px");
	})
  ///////////////////////////////////////////////////////////////////////////////////
  ////保存商品信息///////////////
	$('#addpro').click(function(){
    if($('#hdimage').val()==''){
			tips('notice', '添加商品主图!', 1500, 'weui_icon_notice');
			return;
		} else if($('#proname').val()==''){
			tips('notice', '填写商品名称!', 1500, 'weui_icon_notice');
			return;
		} else if($('#selclass-1').val()== -1){
			tips('notice', '选择商品分类!', 1500, 'weui_icon_notice');
			return;
		} else if($('.partattrs').children().length==0){
			tips('notice', '添加商品属性!', 1500, 'weui_icon_notice');
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
      $('#proweight').val('0');
			// tips('notice', '填写商品重量!', 1500, 'weui_icon_notice');
			// return;
		}
    ///////获取填写信息/////////////////
		var Mimage=$('#hdimage').val();//商品主图
		var Proname=$('#proname').val();//商品名称
		var ClassType=$('#selclass-1').val();//类别编号
		var ClassName=$('#selclass-1 option:selected').text();//类别名称
		var Protitle=$('#protilte').val();//商品标题
		var Pronote=$('#pronote').val();//商品说明
		var Proprice=$('#proprice').val();//商品价格
		var Prosaleprice=$('#prosaleprice').val();//商品出售价格
    // var Prosaleprice='0';//商品出售价格
		var Pronumber=$('#pronumber').val();//商品编号
		var Procode=$('#procode').val();//商品条码
		var Proweight=$('#proweight').val();//商品重量
		var Promark=$('#promark').val();//备注说明
		var Prosearch=$('#prosearch').val();//检索关键字
		var Proecom=$('#proecom').val();//员工提成
		var Proonecom=$('#proonecom').val();//一级提成
		var Propcom=$('#propcom').val();//推广佣金
    var ProContent=$('.prodetailcontents').html();//商品详情
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
    // 是否按照重量计算
    if($('#checkbox_c4').prop("checked")){
      var numtype = '2';
    } else {
      var numtype = '1';
    }
    ////////商品属性json数据组合/////////////////
    var jsonattrlist={};
    var attris=false;
    $('.partattrs>.partattr').each(function(index,item){
      var protype=$(item).attr('data-attrid');
      var attritem=$(item).children(".attritem").eq(0).children(".attredit").val();
      // var attrnum=$(item).children('.attritem').eq(1).children('.attredit').val();
      // var attrcode=$(item).children(".attritem").eq(2).children(".attredit").val();
      // var attrprice=$(item).children(".attritem").eq(3).children(".attredit").val();
      // var attrcount=$(item).children(".attritem").eq(4).children(".attredit").val();
      if ($.trim(attritem)=='') {
        attris=false;
        return false;
      } else {
        attris=true;
      }
      jsonattrlist[index]={};
      jsonattrlist[index]['attr']=attritem;
      // jsonattrlist[index]['inputcode']=attrnum;
      // jsonattrlist[index]['code']=attrcode;
      // jsonattrlist[index]['price']=attrprice;
      // jsonattrlist[index]['count']=attrcount;
      jsonattrlist[index]['ptype']=protype;

      // if (Prosaleprice=='0') {
      //   Prosaleprice=attrprice;
      // } else {
      //   if (parseFloat(Prosaleprice)>parseFloat(attrprice)) {
      //     Prosaleprice=attrprice;
      //   }
      // }
    })
    if (attris==false) {
      tips('notice', '填写完整商品属性!', 1500, 'weui_icon_notice');
      return false;
    }
    jsonattrlist=JSON.stringify(jsonattrlist);
    /////////展示图信息////////////
		var jsonShowimg={};
		for (var i = 0; i < showimglist.length; i++) {
			jsonShowimg[i]=showimglist[i];
		}
		var showimgjson=JSON.stringify(jsonShowimg);
    /////////展示图信息end////////////
    //////////修改时删除的商品属性///////
    var jsondelattr={};
    for (var i = 0; i < delproattrlist.length; i++) {
      jsondelattr[i]=delproattrlist[i];
    }
    var delattrjson=JSON.stringify(jsondelattr);
    //////////修改时删除的商品属性end///////
    /////////商品信息组合//////////////////
    if($(this).attr('data-type')=='proadd'){
      var Ptype='proadd';
		} else{
      var Ptype=$(this).attr('data-pid');
		}
    var prodata={
      ProLogoImg:Mimage,
      ProName:Proname,
      ProShowImg:showimgjson,
      ClassType:ClassType,
      ClassName:ClassName,
      ProAttrList:jsonattrlist,
      ProTitle:Protitle,
      ProSubtitle:Pronote,
      Price:Proprice,
      PriceRange:Prosaleprice,
      ProNumber:Pronumber,
      Barcode:Procode,
      Weight:Proweight,
      Remarks:Promark,
      KeyWord:Prosearch,
      EmpCut:Proecom,
      Cut:Proonecom,
      ExtendCut:Propcom,
      IsUseConpon:Proconpon,
      Iszp:Progift,
      IsUseScore:Proisredeem,
      Score:Proredeem,
      NumType:numtype,
      Ptype:Ptype,
      Delproattr:delattrjson,
      ProContent:ProContent,
    };
    // console.log(prodata); return false;
    ////提交商品数据到后台进行商品保存//////////
    tips('waiting','保存数据中···');
    $.ajax({
      url:prosaveurl,
      type:"post",
      data:prodata,
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


    return false;



  });
  // 打开分类设置
  $('.set_class').click(function(){
    $('.markclass').css('display','block');
  });
  // 关闭分类设置
  $('.closeclass').click(function(){
    $('.markclass').css('display','none');
  });

  // 保存分类信息
  $('.saveclassinfo').click(function(){
    var cid= 'add';
    if ($('.selectclassactive').length>0) {
      cid =$('.selectclassactive').attr('data-id');
    }
    var classname=$('.classnameinfo').val();
    var classsort=$('.classsortinfo').val();
    if ($.trim(classname) != '' && $.trim(classsort) != '') {
      var senddata = {
        'cid':cid,
        'classname':classname,
        'classsort':classsort,
        'type':'1',
      }
      tips('waiting','分类保存中···');
      $.ajax({
        url:classupdate_url,
        type:"post",
        data:senddata,
        dataType:"json",
        success:function(msg){
          if (msg.status=='true'){
            var cinfo = msg.datainfo;
            var htmls='';
            var shtmls='<option value="-1">请选择</option>';
            $.each(cinfo,function(index,item){
              htmls+='<li class="classiteminfo" data-id="'+item['ClassId']+'" data-sort="'+item['ClassSort']+'">'+
  							'<span class="class_name">'+item['ClassName']+'</span>'+
  							'<span class="class_sort">('+item['ClassSort']+')</span>'+
  							'<span class="editclass">编辑</span>'+
  							'<span class="deleteclass">删除</span>'+
  						'</li>';
              shtmls+='<option value="'+item['ClassId']+'">'+item['ClassName']+'</option>';
            });
            $('.class_list>ul').html(htmls);
            $('#selclass-1').html(shtmls);
            setclassbtninfo();
            $('.classnameinfo').val('');
            $('.classsortinfo').val('');
            tips('notice', '分类保存成功!', 1500);
          } else{
            tips('notice', '分类保存失败!', 1500, 'weui_icon_notice');
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
    } else {
      tips('notice', '填写完整分类信息!', 1500, 'weui_icon_notice');
    }
  });
  // 打开商品详情设置
  $('.pitemright').click(function(){
    $('.prodetailsmark').css('display','block');
  });
  // 确定商品详情设置
  $('.prodetailssure').click(function(){
    $('.prodetailsmark').css('display','none');
    // console.log($('.prodetailcontents').html());
  });
  setdelderailimg();
setclassbtninfo();
  ///////////////////////////////////////////////////////////////////////////////////
})
//////////////////////////////////////////////////////////////////////////////////////
/////商品主图上传//////////////
oFReader = new FileReader(),
rFilter = /^(?:image\/jpeg|image\/png)$/i;
oFReader.onload = function (oFREvent) {
  qdhimage();
};
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
///////////////////////////////////////////////////////////////////////////////////
//////选择商品展示上传图片/////
/////商品展示上传//////////////
osFReader = new FileReader(),
rsFilter = /^(?:image\/jpeg|image\/png)$/i;
osFReader.onload = function (oFREvent) {
  qdsimage();
};
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
            $('#enter').attr('data-type','1');
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
///////////////////////////////////////////////////////////////////////////////////




//////选择商品详情展示图上传图片/////
/////商品详情展示上传//////////////
osdFReader = new FileReader(),
rsdFilter = /^(?:image\/jpeg|image\/png)$/i;
osdFReader.onload = function (oFREvent) {
  qdderailimage();
};
function seldetailimg(ifile){
	if (ifile.files.length === 0){
		return;
	}
	var oFile = ifile.files[0];
	if (!rsdFilter.test(oFile.type)) {
		tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
		return;
	}else{
		osdFReader.readAsDataURL(oFile);
	}
}
//////确定选择详情展示图///////////////
function qdderailimage(){
	var htmls="";
	$.ajaxFileUpload({
		url: proimagesave, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'detailimg', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
        var hostinfo = window.location.protocol + '//' + window.location.host;
        var imgurl =hostinfo + data.datainfo;
        html='<p><img src='+imgurl+'></p>';
        $('.prodetailcontents').append(html);
        setdelderailimg();
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
///////////////////////////////////////////////////////////////////////////////////

// 设置删除产品详情的图片信息
function setdelderailimg(){
  $('.prodetailcontents img').click(function(){
    $('#enter').attr('data-simg',$(this).attr('src'));
    $('#enter').attr('data-type','2');
    tips('confirm', '确定要删除图片?', 10000);
  });
}





////////删除属性///////
function delattr(span){
  if ($(span).parent().parent().attr('data-attrid')!='add') {
    delproattrlist.push($(span).parent().parent().attr('data-attrid'));
  }
	$(span).parent().parent().remove();
  if ($('.partattrs').children().length==0) {
		$('.partattrs').css('display','none');
	} else {
		$('.partattrs').css('display','block');
	}
}
// 设置分类修改编辑和删除按钮事件
function setclassbtninfo(){
  // 点击编辑分类
  $('.editclass').click(function(){
    if (!$(this).parents('.classiteminfo').hasClass('selectclassactive')) {
      $('.selectclassactive').find('.editclass').text('编辑');
      $('.selectclassactive').removeClass('selectclassactive');
      $(this).parents('.classiteminfo').addClass('selectclassactive');
      var classname = $(this).parents('.classiteminfo').find('.class_name').text();
      var classsort = $(this).parents('.classiteminfo').attr('data-sort');
      $('.classnameinfo').val(classname);
      $('.classsortinfo').val(classsort);
      $(this).text('取消');
    } else {
      $('.selectclassactive').removeClass('selectclassactive');
      $('.classnameinfo').val('');
      $('.classsortinfo').val('');
      $(this).text('编辑');
    }
  });
  // 删除分类信息
  $('.deleteclass').click(function(){
    var cid = $(this).parents('.classiteminfo').attr('data-id');
    var senddata = {
      'cid':cid,
      'type':'2',
    };
    tips('waiting','分类删除中···');
    $.ajax({
      url:classupdate_url,
      type:"post",
      data:senddata,
      dataType:"json",
      success:function(msg){
        if (msg.status=='true') {
          var cinfo = msg.datainfo;
          var htmls='';
          var shtmls='<option value="-1">请选择</option>';
          $.each(cinfo,function(index,item){
            htmls+='<li class="classiteminfo" data-id="'+item['ClassId']+'" data-sort="'+item['ClassSort']+'">'+
              '<span class="class_name">'+item['ClassName']+'</span>'+
              '<span class="class_sort">('+item['ClassSort']+')</span>'+
              '<span class="editclass">编辑</span>'+
              '<span class="deleteclass">删除</span>'+
            '</li>';
            shtmls+='<option value="'+item['ClassId']+'">'+item['ClassName']+'</option>';
          });
          $('.class_list>ul').html(htmls);
          $('#selclass-1').html(shtmls);
          setclassbtninfo();
          tips('notice', '删除成功!', 1500);
        } else{
          if(msg.datainfo=='delhasproError'){
            tips('notice', '此分类下存在商品,无法删除!', 1500, 'weui_icon_notice');
          } else{
          tips('notice', '分类删除失败!', 1500, 'weui_icon_notice');
          }
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
}
