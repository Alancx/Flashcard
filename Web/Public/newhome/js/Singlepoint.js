var scrolltype = '1';
$(document).ready(function(){
  // 左侧分类默认第一个被选中
  $('.lefttab>ul>li:first-child').addClass('classactive');
  //左侧分类点击事件
  mui('.lefttab').on('tap', 'li', function() {
    if (!$(this).hasClass('classactive')) {
      $('.classactive').removeClass('classactive');
      $(this).addClass('classactive');
    }
    scrolltype = '0';
    var i = $(this).index('.lefttab>ul>li');
    var rightlitop = $('.righttab>ul>li').eq(i).position().top;
    var rightultop = $('.righttab>ul').position().top;
    $('.righttab').animate({
      scrollTop: rightlitop - rightultop
    }, 100,function(){
      scrolltype = '1';
    });
  });

  //右侧滚动
  $(".righttab").on("scroll", function() {
    if(scrolltype != '1') {
      return false;
    }
    var _this = $(this);
    //滚动到标杆位置,左侧导航加actives
    if (_this[0].scrollTop + _this.height() >= _this[0].scrollHeight - 1) {
      $('.lefttab>ul>li').removeClass('classactive');
      $('.lefttab>ul>li:last-child').addClass('classactive');
    } else {
      $('.righttab>ul>li').each(function(index, item) {
        var target = parseInt($(item).position().top);
        var i = index;
        if(target <= 0) {
          $('.lefttab>ul>li').removeClass('classactive').eq(i).addClass('classactive');
        }
      });
    }
  });
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
    $('.prospec_bottom>.prospechas').text('');
    $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
    $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
    $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
    $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
    $('.prospec_info_list').html('');
  });
  // 显示共同点餐二维码
  $('.showcode').on('tap',function(){
    $('.codemark').css('display','block');
  })
  // 关闭共同点餐二维码
  $('.codemark').on('tap',function(){
    $('.codemark').css('display','none');
  })

  // 关闭商品详情
  $('.closeproinfo').on('tap',function(){
    $('.proinfo_mark').css('display','none');
  })
  setplusbtn();
  setminusbtn();
  setnuminput();//设置输入框规则
  setselspec();//打开选择规格
  showproinfo();//打开商品详情
  // 首次进入设置购物车信息
  setcartslist();
});

// 首次进入设置购物车信息
function setcartslist(){
  if (cartslist!='') {
    var cartsArray = JSON.parse(cartslist);
    $.each(cartsArray,function(index,item){
      setselprosinfos(item['pid'],parseFloat(item['nums']),item['plid'],item['pspec'],item['cid'],item['numtype']);
      $('.outproinfo[data-pid='+item['pid']+'][data-plid='+item['plid']+']').find('.minusbtn').css('display','block');
      $('.outproinfo[data-pid='+item['pid']+'][data-plid='+item['plid']+']').find('.showselnum').css('display','block');
      $('.outproinfo[data-pid='+item['pid']+'][data-plid='+item['plid']+']').find('.showselnum').text(item['nums']);
    })
  }
}

// 设置外层滚动事件
function setallproinfoscroll(){
  $('.allproinfo').off('scroll');
  $('.allproinfo').on('scroll',function(){
    var _this = $(this);
    if (_this[0].scrollTop + _this.height() + 10 >= _this[0].scrollHeight) {
      $('.allproinfo').off('scroll');
      $('.contentstop').css('display','none');
      $(this).css('overflow','hidden');
      if ($('.current').index() == '0') {
        $('.propart>div').css('overflow','auto');
        $('.propart>div').scrollTop(1);
        righttabscroll();
        lefttabscroll();
      } else if ($('.current').index() == '1') {
        $('.evaluatepart').css('overflow','auto');
        $('.evaluatepart').scrollTop(1);
        evaluatescroll();
      } else if ($('.current').index() == '2') {
        $('.businesspart').css('overflow','auto');
        $('.businesspart').scrollTop(1);
        businessscroll();
      }
    }
  });
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
    var objdiv = $('.outproinfo[data-pid='+pid+']>div>.shownumbtn');
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
    var plist = JSON.parse(allproinfo)[cid]['pros'][pid]['prolist'];
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
      $('.prospec_bottom>.prospechas').text('');
      $('.prospec_bottom>.shownumbtn>.minusbtn').css('display','none');
      $('.prospec_bottom>.shownumbtn>.showselnum').text('0');
      $('.prospec_bottom>.shownumbtn>.showselnum').css('display','none');
      $('.prospec_bottom>.shownumbtn>.plusbtn').css('display','block');
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
        htmls+='<span class="pspec_info mui-ellipsis" data-pid="'+pid+'" data-plid="'+item['ProIdCard']+'" data-plspec="'+item['ProSpec']+'"><span class="mui-ellipsis">'+item['ProSpec']+'</span></span>';
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
      $('.prospec_bottom>.prospechas').text('('+pspec+')');
      $('.prospec_bottom>.shownumbtn').attr('data-plid',plid);
      $('.prospec_bottom>.shownumbtn').attr('data-plspec',pspec);
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

// 打开商品详情
// $('.outproinfo>img').on('tap',function(){
//   $('.proinfo_mark').css('display','block');
// })
// 打开商品详情
function showproinfo(){
  $('.outproinfo>img').off('tap');
  $('.outproinfo>img').on('tap',function(){
    var cid = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-cid');
    var pid = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-pid');
    var price = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-price');
    var pname = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-pname');
    var pimg = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-pimg');
    var numtype = $(this).parent('.outproinfo').find('.shownumbtn').attr('data-numtype');
    var p_info = JSON.parse(allproinfo)[cid]['pros'][pid];

    if (p_info['prolist'].length > 0) {
      $('.pro_name').text(pname);
      $('.pro_sale').text('已售:'+p_info['SalesCount']);
      var showimg = '';
      showimg = '<img src="'+p_info['ProLogoImg']+'"/>';
      showimg = showimg + htmlspecialchars_decode(p_info['ProContent']);
      $('.pro_img_list').html(showimg);
      if (numtype=='1') {
        $('.prospec_price').html(price+'<span>/份</span>');
      } else {
        $('.prospec_price').html(price+'<span>/斤</span>');
      }
      $('.pro_spec_bottom>.shownumbtn').attr('data-pid',pid);
      $('.pro_spec_bottom>.shownumbtn').attr('data-pname',pname);
      $('.pro_spec_bottom>.shownumbtn').attr('data-pimg',pimg);
      $('.pro_spec_bottom>.shownumbtn').attr('data-price',price);
      $('.pro_spec_bottom>.shownumbtn').attr('data-cid',cid);
      $('.pro_spec_bottom>.shownumbtn').attr('data-numtype',numtype);
      $('.pro_spec_bottom>.shownumbtn').attr('data-plid','');
      $('.pro_spec_bottom>.shownumbtn').attr('data-plspec','');
      $('.pro_spec_bottom>.prospe_chas').text('');

      if (numtype == '1') {
        var html='<span class="minusbtn ggg minus_icon"></span>'+
        '<span class="showselnum">0</span>'+
        '<span class="plusbtn ggg plus_icon"></span>';
        $('.pro_spec_bottom>.shownumbtn').html(html);
        $('.pro_spec_bottom>.shownumbtn>.minusbtn').css('display','none');
        $('.pro_spec_bottom>.shownumbtn>.showselnum').text('0');
        $('.pro_spec_bottom>.shownumbtn>.showselnum').css('display','none');
        $('.pro_spec_bottom>.shownumbtn>.plusbtn').css('display','block');
        setplusbtn();
        setminusbtn();
      } else {
        var html='<input type="text" name="proweight" class="proweight" value="0">';
        $('.pro_spec_bottom>.shownumbtn').html(html);
        setnuminput();
      }
      var htmls='';
      $.each(p_info['prolist'],function(index,item){
        htmls+='<span class="p_spec_info mui-ellipsis" data-pid="'+pid+'" data-plid="'+item['ProIdCard']+'" data-plspec="'+item['ProSpec']+'"><span class="mui-ellipsis">'+item['ProSpec']+'</span></span>';
      });
      $('.pro_spec_info_list').html(htmls);
      selectprospecactive();//选择商品属性
      $('.proinfo_mark').css('display','block');

      var imglist = $('.pro_info_part').height() - $('.pro_info_part>.pro_info_top').outerHeight() - $('.pro_info_part>.pro_info_bottom').outerHeight()+10;
      $('.pro_info_part>.pro_img_list').css('height',imglist+'px');
    } else {
      mui.toast('打开失败，请重试或刷新页面');
    }
  })
};

// 选择商品属性
function selectprospecactive(){
  $('.p_spec_info').off('tap');
  $('.p_spec_info').on('tap',function(){
    if (!$(this).hasClass('p_spec_infoactive')) {
      $('.pro_spec_bottom>.shownumbtn').attr('data-plid','');
      $('.pro_spec_bottom>.shownumbtn').attr('data-plspec','');
      $('.p_spec_infoactive').removeClass('p_spec_infoactive');
      $(this).addClass('p_spec_infoactive');
      var pid=$(this).attr('data-pid');
      var plid=$(this).attr('data-plid');
      var pspec=$(this).attr('data-plspec');
      $('.pro_spec_bottom>.prospe_chas').text('('+pspec+')');
      $('.pro_spec_bottom>.shownumbtn').attr('data-plid',plid);
      $('.pro_spec_bottom>.shownumbtn').attr('data-plspec',pspec);
      var numtype = $('.pro_spec_bottom>.shownumbtn').attr('data-numtype');
      var hasprolength= $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']').length;
      if (hasprolength>0) {
        if (numtype == '1') {
          pronum = $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']>div>.shownumbtn>.showselnum').text();
          $('.pro_spec_bottom>.shownumbtn>.minusbtn').css('display','block');
          $('.pro_spec_bottom>.shownumbtn>.showselnum').css('display','block');
          $('.pro_spec_bottom>.shownumbtn>.showselnum').text(pronum);
          $('.pro_spec_bottom>.shownumbtn>.plusbtn').css('display','block');
        } else {
          pronum = $('.inproinfo[data-pid='+pid+'][data-plid='+plid+']>div>.shownumbtn>.proweight').val();
          $('.pro_spec_bottom>.shownumbtn>.proweight').val(pronum);
        }
      } else {
        if (numtype == '1') {
          $('.pro_spec_bottom>.shownumbtn>.minusbtn').css('display','none');
          $('.pro_spec_bottom>.shownumbtn>.showselnum').css('display','none');
          $('.pro_spec_bottom>.shownumbtn>.showselnum').text('0');
        } else {
          $('.pro_spec_bottom>.shownumbtn>.proweight').val('0');
        }
      }
    }
  })
}

function htmlspecialchars(str){
  if (str=='' || str == null) {
    str ='';
  }
  str = str.replace(/&/g, '&amp;');
  str = str.replace(/</g, '&lt;');
  str = str.replace(/>/g, '&gt;');
  str = str.replace(/"/g, '&quot;');
  str = str.replace(/'/g, '&#039;');
  return str;
}

function htmlspecialchars_decode(str){
  if (str=='' || str == null) {
    str ='';
  }
  str = str.replace(/&amp;/g, '&');
  str = str.replace(/&lt;/g, '<');
  str = str.replace(/&gt;/g, '>');
  str = str.replace(/&quot;/g, '"');
  str = str.replace(/&#039;/g, "'");
  return str;
}
