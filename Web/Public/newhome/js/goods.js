$(document).ready(function(){
  // 点击已点
  $('.showgetpronum').on('tap',function(){
    if ($('.hasselprosmark').css('display')=='none') {
      $('.hasselprosmark').css('display','block');
    } else {
      $('.hasselprosmark').css('display','none');
    }
  });
  // 收起按钮
  $('.downcarts').on('tap',function(){
    if ($('.hasselprosmark').css('display')=='block') {
      $('.hasselprosmark').css('display','none');
    }
  });
  // 清空事件
  $('.cleanselpros').on('tap',function(){
    $('.selproslist').html('');
    settotalinfos();
    $('.shownumbtn>.showselnum').text('0');
    $('.shownumbtn>.showselnum').css('display','none');
    $('.shownumbtn>.minusbtn').css('display','none');
    setcartsinfo('cleancart','','','','','','');
  })
  // 点击收藏
  $('.setcollect').on('tap',function(){
    if ($(this).attr('data-type')=='0') {
      setprocollect('1',$(this).attr('data-pid'));
    } else {
      setprocollect('0',$(this).attr('data-pid'));
    }
  });
  // 去结算
  $('.gotopay').on('tap',function(){
    if ($('.selproslist>.inproinfo').length>0) {
      window.location.href = order_url;
    } else {
      mui.toast('请选择菜品');
    }
  });
  // 关闭属性选择
  $('.closeselprospec').on('tap',function(){
    $('.prospecmark').css('display','none');
    $('.pspecname').text('');
    $('.prospecprice').text('');
    $('.prospec_bottom>.shownumbtn').attr('data-pid','');
    $('.prospec_bottom>.shownumbtn').attr('data-pname','');
    $('.prospec_bottom>.shownumbtn').attr('data-pimg','');
    $('.prospec_bottom>.shownumbtn').attr('data-price','');
    $('.prospec_bottom>.shownumbtn').attr('data-cid','');
    $('.prospec_bottom>.shownumbtn').attr('data-plid','');
    $('.prospec_bottom>.shownumbtn').attr('data-plspec','');
    $('.prospec_bottom>.shownumbtn').html('');
    // $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
    // $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
    // $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
    // $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
    $('.prospec_bottom>.prospechas').text('');
    $('.prospec_info_list').html('');
  });
  // 选择商品详情还是评论
  $('.tabproinfo>div').on('tap',function(){
    if (!$(this).hasClass('tabproactive')) {
      $('.tabproactive').removeClass('tabproactive');
      $(this).addClass('tabproactive');
      var type = $(this).attr('data-type');
      $('.tab_pro_info').css('display','none');
      $('.tab_pro_info[data-type='+type+']').css('display','block');
    }
  });
  // 点击进行分享
  $('.showdianzan').on('tap',function(){
    $('.sharemark').css('display','block');
  })
  $('.sharemark').on('tap',function(){
    $('.sharemark').css('display','none');
  })
  setselspec();//打开选择规格
  setnuminput();//设置输入框规则
  // 首次进入设置购物车信息
  setcartslist();
})
// 首次进入设置购物车信息
function setcartslist(){
  if (cartslist!='') {
    var cartsArray = JSON.parse(cartslist);
    var htmls = '';
    $.each(cartsArray,function(index,item){
      var numhtml='';
      if (item['NumType']=='1') {
        numhtml = '<span class="minusbtn ggg minus_icon"></span>'+
        '<span class="showselnum">'+item['snums']+'</span>'+
        '<span class="plusbtn ggg plus_icon"></span>';
        var unithtml='<span>/份</span>';
      } else {
        numhtml='<input type="text" name="proweight" class="proweight" value="'+parseFloat(item['snums'])+'">';
        var unithtml='<span>/斤</span>';
      }
      htmls += '<div class="spitem inproinfo" data-pid="'+item['ProId']+'" data-cid="'+item['ClassType']+'" data-plid="'+item['ProIdCard']+'">'+
      '<img src="'+item['ProLogoImg']+'"/>'+
      '<div>'+
      '<p class="selpronameinfo">'+
        '<span class="selproname mui-ellipsis">'+item['ProName']+'</span>'+
        '<span class="selprospec mui-ellipsis">('+item['ProSpec1']+')</span>'+
      '</p>'+
      '<span class="jiage">'+item['Price']+unithtml+'</span>'+
      '<div class="shownumbtn"  data-pid="'+item['ProId']+'" data-pname="'+item['ProName']+'" data-pimg="'+item['ProLogoImg']+'" data-price="'+item['Price']+'" data-cid="'+item['ClassType']+'" data-plid="'+item['ProIdCard']+'" data-plspec="'+item['ProSpec1']+'" data-numtype="'+item['NumType']+'">'+
      numhtml+
      '</div>'+
      '</div>'+
      '</div>';
      if (item['ProId'] == $('.prosetcolnum').attr('data-pid')) {
        if (item['NumType'] == '1') {
          $('.prosetcolnum[data-pid='+item['ProId']+']').find('.minusbtn').css('display','block');
          $('.prosetcolnum[data-pid='+item['ProId']+']').find('.showselnum').css('display','block');
          $('.prosetcolnum[data-pid='+item['ProId']+']').find('.showselnum').text(item['snums']);
        } else {
          $('.prosetcolnum[data-pid='+item['ProId']+']').find('.proweight').val(parseFloat(item['snums']));
        }
      }
    });
    $('.selproslist').append(htmls);
    settotalinfos();
  }
  setnuminput();
  setplusbtn();
  setminusbtn();
}
// 设置加号事件
function setplusbtn(){
  $('.plusbtn').off('tap');
  $('.plusbtn').on('tap',function(){
    if ($(this).parent('.shownumbtn').attr('data-plid')=='') {
      mui.toast('请选择规格');
      return false;
    }
    var pid = $(this).parent('.shownumbtn').attr('data-pid');
    var plid = $(this).parent('.shownumbtn').attr('data-plid');
    var pspec = $(this).parent('.shownumbtn').attr('data-plspec');
    var cid = $(this).parent('.shownumbtn').attr('data-cid');
    var numtype = $(this).parent('.shownumbtn').attr('data-numtype');
    var selnumns = $(this).parent('.shownumbtn').children('.showselnum').text();
    selnumns = parseInt(selnumns) +1;
    if (selnumns > 0) {
      $(this).parent('.shownumbtn').children('.showselnum').css('display','block');
      $(this).parent('.shownumbtn').children('.minusbtn').css('display','block');
    }
    setselprosinfos(pid,selnumns,plid,pspec,cid,numtype);
  });
}
// 设置减号事件
function setminusbtn(){
  $('.minusbtn').off('tap');
  $('.minusbtn').on('tap',function(){
    if ($(this).parent('.shownumbtn').attr('data-plid')=='') {
      mui.toast('请选择规格');
      return false;
    }
    var pid = $(this).parent('.shownumbtn').attr('data-pid');
    var selnumns = $(this).parent('.shownumbtn').children('.showselnum').text();
    var plid = $(this).parent('.shownumbtn').attr('data-plid');
    var pspec = $(this).parent('.shownumbtn').attr('data-plspec');
    var cid = $(this).parent('.shownumbtn').attr('data-cid');
    var numtype = $(this).parent('.shownumbtn').attr('data-numtype');
    selnumns = parseInt(selnumns);
    if(selnumns > 0) {
      selnumns = parseInt(selnumns) - 1;
    }
    if (selnumns <= 0) {
      $(this).parent('.shownumbtn').children('.showselnum').css('display','none');
      $(this).parent('.shownumbtn').children('.minusbtn').css('display','none');
    }
    setselprosinfos(pid,selnumns,plid,pspec,cid,numtype);
  });
}
// 设置输入框规则
function setnuminput(){
  // 填写数量
  $('.proweight').off('input');
  $('.proweight').on('input',function(){
    var str=$(this).val();
    str = str.replace(/[^\d.]/g,""); //清除"数字"和"."以外的字符
    str = str.replace(/^\./g,""); //验证第一个字符是数字
    str = str.replace(/\.{2,}/g,"."); //只保留第一个, 清除多余的
    str = str.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    str = str.replace(/^(\-)*(\d+)\.(\d).*$/,'$1$2.$3'); //只能输入两个小数

    if(str.indexOf('.')<0 && str!=''){
      var setnums = parseFloat(str);
      $(this).val(setnums);
    } else if (str =='') {
      var setnums = 0;
      $(this).val('');
    } else {
      var setnums = str;
      $(this).val(setnums);
    }

    if ($(this).parent('.shownumbtn').attr('data-plid')=='') {
      mui.toast('请选择规格');
      $(this).val('0');
      return false;
    }
    var pid = $(this).parent('.shownumbtn').attr('data-pid');
    var selnumns = setnums;
    var plid = $(this).parent('.shownumbtn').attr('data-plid');
    var pspec = $(this).parent('.shownumbtn').attr('data-plspec');
    var cid = $(this).parent('.shownumbtn').attr('data-cid');
    var numtype = $(this).parent('.shownumbtn').attr('data-numtype');
    setselprosinfos(pid,selnumns,plid,pspec,cid,numtype);

  });
}
// 点击加和减事件处理已选列表信息
function setselprosinfos(pid,snum,plid,pspec,cid,numtype){
  var pid = pid;
  var plid = plid;
  var pspec = pspec;
  var cid = cid;
  var snum = snum;
  var numtype = numtype;
  var html = '';
  if ($('.selproslist>.inproinfo[data-pid='+pid+'][data-plid='+plid+']').length<=0) {
    var objdiv = $('.prosetcolnum[data-pid='+pid+']>.shownumbtn');
    if (numtype=='1') {
      var showhtml='<span class="minusbtn ggg minus_icon"></span>'+
      '<span class="showselnum">0</span>'+
      '<span class="plusbtn ggg plus_icon"></span>';
      var unithtml='<span>/份</span>';
    } else {
      var showhtml='<input type="text" name="proweight" class="proweight" value="0">';
      var unithtml='<span>/斤</span>';
    }
    html = '<div class="spitem inproinfo" data-pid="'+pid+'" data-cid="'+cid+'" data-plid="'+plid+'">'+
    '<img src="'+objdiv.attr('data-pimg')+'"/>'+
    '<div>'+
    '<p class="selpronameinfo">'+
      '<span class="selproname mui-ellipsis">'+objdiv.attr('data-pname')+'</span>'+
      '<span class="selprospec mui-ellipsis">('+pspec+')</span>'+
    '</p>'+
    '<span class="jiage">'+objdiv.attr('data-price')+unithtml+'</span>'+
    '<div class="shownumbtn"  data-pid="'+objdiv.attr('data-pid')+'" data-pname="'+objdiv.attr('data-pname')+'" data-pimg="'+objdiv.attr('data-pimg')+'" data-price="'+objdiv.attr('data-price')+'" data-cid="'+cid+'" data-plid="'+plid+'" data-plspec="'+pspec+'" data-numtype="'+numtype+'">'+
    showhtml+
    '</div>'+
    '</div>'+
    '</div>';
    $('.selproslist').append(html);
    setplusbtn();
    setminusbtn();
    setnuminput();
  }
  if (numtype =='1') {
    if (snum <=0) {
      $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']').remove();
      $('.shownumbtn[data-pid='+pid+'][data-plid='+plid+']>.showselnum').css('display','none');
      $('.shownumbtn[data-pid='+pid+'][data-plid='+plid+']>.minusbtn').css('display','none');
    }
    $('.shownumbtn[data-pid='+pid+'][data-plid='+plid+']>.showselnum').text(snum);
  } else {
    if (parseFloat(snum)<=0) {
      $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']').remove();
    } else {
      $('.shownumbtn[data-pid='+pid+'][data-plid='+plid+']>.proweight').val(snum);
    }
  }
  setcartsinfo('updatecart',pid,snum,plid,pspec,cid,numtype);
  settotalinfos();
}
// 计算商品数量和总价格
function settotalinfos(){
  var selproslist = $('.selproslist').children('.inproinfo');
  if (selproslist.length>0) {
    var totalprice = 0;
    var spronum = 0;
    selproslist.each(function(index,item){
      if ($(item).find('.shownumbtn').attr('data-numtype')=='1') {
        spronum +=parseInt($(item).find('.shownumbtn>.showselnum').text());
        totalprice += (parseInt($(item).find('.shownumbtn>.showselnum').text()) * parseFloat($(item).find('.shownumbtn').attr('data-price')));
      } else {
        spronum +=1;
        totalprice += (parseFloat($(item).find('.shownumbtn>.proweight').val()) * parseFloat($(item).find('.shownumbtn').attr('data-price')));
      }
    });
    $('.showgetpronum>span:last-child').text(spronum);
    $('.totalprice').text(parseFloat(totalprice).toFixed(2));
    if (!$('.showgetpronum').hasClass('showgetpronumactive')) {
      $('.showgetpronum').addClass('showgetpronumactive');
    }
  } else {
    $('.showgetpronum').removeClass('showgetpronumactive');
    $('.showgetpronum>span:last-child').text('0');
    $('.totalprice').text('0.00');
  }
}
// 修改后台购物车数量
function setcartsinfo(type,pid,pnum,plid,pspec,cid,numtype){
  var type = type;
  var pid = pid;
  var pnum = pnum;
  var plid = plid;
  var pspec = pspec;
  var cid = cid;
  var numtype = numtype;
  var senddata = {
    type:type,
    pid:pid,
    nums:pnum,
    plid:plid,
    pspec:pspec,
    cid:cid,
    numtype:numtype,
  };
  $.ajax({
    url:updatecart_url,
    type:"post",
    data:senddata,
    dataType:"json",
    complete:function(){

    },
    success:function(msg){

    },
    error:function(e){

    }
  });
}

//设置是否收藏当前商品
function setprocollect(ctype,pid){
  var ctype = ctype;
  var pid = pid;
  $.ajax({
    url:setshopcollect_url,
    type:"post",
    data:{'ctype':ctype,'pid':pid},
    dataType:"json",
    complete:function(){

    },
    success:function(msg){
      if (msg['status'] == 'true') {
        if (msg.info=='1') {
          $('.setcollect').attr('data-type','1');
          $('.setcollect').removeClass('xin_icon').addClass('xin_fillicon setcollectactive');
        } else {
          $('.setcollect').attr('data-type','0');
          $('.setcollect').removeClass('xin_fillicon setcollectactive').addClass('xin_icon')
        }
      }
    },
    error:function(e){

    }
  });
}


// 打开规格选择
function setselspec(){
  $('.selSpec').off('tap');
  $('.selSpec').on('tap',function(){
    var cid = $(this).parent('.shownumbtn').attr('data-cid');
    var pid = $(this).parent('.shownumbtn').attr('data-pid');
    var price = $(this).parent('.shownumbtn').attr('data-price');
    var pname = $(this).parent('.shownumbtn').attr('data-pname');
    var pimg = $(this).parent('.shownumbtn').attr('data-pimg');
    var numtype = $(this).parent('.shownumbtn').attr('data-numtype');
    var plist = JSON.parse(plinfo);
    if (plist.length > 0) {
      $('.pspecname').text(pname);
      if (numtype=='1') {
        $('.prospecprice').html(price+'<span>/份</span>');
      } else {
        $('.prospecprice').html(price+'<span>/斤</span>');
      }
      $('.prospec_bottom>.shownumbtn').attr('data-pid',pid);
      $('.prospec_bottom>.shownumbtn').attr('data-pname',pname);
      $('.prospec_bottom>.shownumbtn').attr('data-pimg',pimg);
      $('.prospec_bottom>.shownumbtn').attr('data-price',price);
      $('.prospec_bottom>.shownumbtn').attr('data-cid',cid);
      $('.prospec_bottom>.shownumbtn').attr('data-numtype',numtype);
      $('.prospec_bottom>.shownumbtn').attr('data-plid','');
      $('.prospec_bottom>.shownumbtn').attr('data-plspec','');
      $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
      $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
      $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
      $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
      $('.prospec_bottom>.prospechas').text('');
      if (numtype == '1') {
        var html='<span class="minusbtn ggg minus_icon"></span>'+
        '<span class="showselnum">0</span>'+
        '<span class="plusbtn ggg plus_icon"></span>';
        $('.prospec_bottom>.shownumbtn').html(html);
        $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
        $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
        $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
        $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
        setplusbtn();
        setminusbtn();
      } else {
        var html='<input type="text" name="proweight" class="proweight" value="0">';
        $('.prospec_bottom>.shownumbtn').html(html);
        setnuminput();
      }
      var htmls='';
      $.each(plist,function(index,item){
        htmls+='<span class="pspec_info mui-ellipsis" data-pid="'+pid+'" data-plid="'+item['ProIdCard']+'" data-plspec="'+item['ProSpec']+'"><span>'+item['ProSpec']+'</span></span>';
      });
      $('.prospec_info_list').html(htmls);
      selectspecactive();//选择商品属性
      $('.prospecmark').css('display','block');
    } else {
      mui.toast('打开失败，请重试或刷新页面');
    }
  })
};
// 选择商品属性
function selectspecactive(){
  $('.pspec_info').off('tap');
  $('.pspec_info').on('tap',function(){
    if (!$(this).hasClass('pspec_infoactive')) {
      $('.prospec_bottom>.shownumbtn').attr('data-plid','');
      $('.prospec_bottom>.shownumbtn').attr('data-plspec','');
      $('.pspec_infoactive').removeClass('pspec_infoactive');
      $(this).addClass('pspec_infoactive');
      var pid=$(this).attr('data-pid');
      var plid=$(this).attr('data-plid');
      var pspec=$(this).attr('data-plspec');
      $('.prospec_bottom>.shownumbtn').attr('data-plid',plid);
      $('.prospec_bottom>.shownumbtn').attr('data-plspec',pspec);
      $('.prospec_bottom>.prospechas').text('('+pspec+')');
      var numtype = $('.prospec_bottom>.shownumbtn').attr('data-numtype');
      var hasprolength= $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']').length;
      if (hasprolength>0) {
        if (numtype == '1') {
          pronum = $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']>div>.shownumbtn>.showselnum').text();
          $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','block');
          $('.prospec_bottom>.shownumbtn>.showselnum').css('display','block');
          $('.prospec_bottom>.shownumbtn>.showselnum').text(pronum);
          $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
        } else {
          pronum = $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']>div>.shownumbtn>.proweight').val();
          $('.prospec_bottom>.shownumbtn>.proweight').val(pronum);
        }
      } else {
        if (numtype == '1') {
          $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
          $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
          $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
        } else {
          $('.prospec_bottom>.shownumbtn>.proweight').val('0');
        }
      }
    }
  })
}
