var btnmenuX;//菜单按钮坐标信息
var btnmenuY;//菜单按钮坐标信息
$(document).ready(function () {
  var starX; //点击时的坐标信息可视区域
  var starY;//点击时的坐标信息可视区域
  var moveX;//滑动时的坐标信息可视区域
  var moveY;//滑动时的坐标信息可视区域
  //tab标签选择事件处理
  $('.prosel').click(function(){
    if(!$(this).hasClass('proselactive')){
      $('.prolist-'+$('.proselactive').attr("data-s")).css("display","none");
      $('.proselactive').removeClass('proselactive');
      $(this).addClass('proselactive');
      $('.prolist-'+$(this).attr("data-s")).css("display","block");

      if ($(this).attr("data-s")=='2') {
        if($('.prolist-2').attr('data-p')=='-1'){
          if(($('.pro-2').text()!='已全部加载完')&&($('.pro-2').text()!='正在加载···')){
            $('.pro-2').text('正在加载···');
            loadmore_2(-1);
          }
        }
      } else if ($(this).attr("data-s")=='3') {
        if($('.prolist-3').attr('data-p')=='-1'){
          if(($('.pro-3').text()!='已全部加载完')&&($('.pro-3').text()!='正在加载···')){
            $('.pro-3').text('正在加载···');
            loadmore_3(-1);
          }
        }
      } else if ($(this).attr("data-s")=='4') {
        if($('.prolist-4').attr('data-p')=='-1'){
          if(($('.pro-4').text()!='已全部加载完')&&($('.pro-4').text()!='正在加载···')){
            $('.pro-4').text('正在加载···');
            loadmore_4(-1);
          }
        }
      }
    }
  })
  // 产品列表开始滑动时的坐标信息及产品菜单显示隐藏///////////////
  $(document.body).on('touchstart',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      starX=touch.clientX;
      starY=touch.clientY;

      var tdom=event.originalEvent.target;
      if(!$(tdom).hasClass('czicon')){
        if((!$(tdom).parent().hasClass('editmenu'))&&(!$(tdom).parent().parent().hasClass('editmenu'))){
          $('.seleditmenu').removeClass('seleditmenu');
          $('.editmenu').css('display','none');
        }
      } else{
        btnmenuX=$(tdom).offset().left;
        btnmenuY=$(tdom).offset().top;
      }
    }
  })
  //出售中滑动事件
  $('.prolist-1').on('touchmove',function(event){
    // event.stopPropagation();
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件
          if(($('.pro-1').text()!='已全部加载完')&&($('.pro-1').text()!='正在加载···')){
            $('.pro-1').text('正在加载···');
            loadmore_1($(this).attr('data-p'));
          }
        }
      }
    }
  })
  //售馨的滑动事件
  $('.prolist-2').on('touchmove', function (event) {
    // event.stopPropagation();
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件
          if(($('.pro-2').text()!='已全部加载完')&&($('.pro-2').text()!='正在加载···')){
            $('.pro-2').text('正在加载···');
            loadmore_2($(this).attr('data-p'));
          }
        }
      }
    }
  })
  //仓库中滑动事件
  $('.prolist-3').on('touchmove', function (event) {
    // event.stopPropagation();
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件
          if(($('.pro-3').text()!='已全部加载完')&&($('.pro-3').text()!='正在加载···')){
            $('.pro-3').text('正在加载···');
            loadmore_3($(this).attr('data-p'));
          }
        }
      }
    }
  })
  //最新产品滑动事件
  $('.prolist-4').on('touchmove', function (event) {
    // event.stopPropagation();
    if (event.originalEvent.targetTouches.length == 1) {
      var touch = event.originalEvent.targetTouches[0];  // 把元素放在手指所在的位置
      moveX=touch.clientX;
      moveY=touch.clientY;
      if((starY-moveY)>0){
        if($(this)[0].scrollTop+$(this).height()>=$(this)[0].scrollHeight){
          event.preventDefault();//阻止其他事件
          if(($('.pro-4').text()!='已全部加载完')&&($('.pro-4').text()!='正在加载···')){
            $('.pro-4').text('正在加载···');
            loadmore_4($(this).attr('data-p'));
          }
        }
      }
    }
  })
  //加载使固定高度
  $('.prolists').css('height',$(window).height()-125+"px");
})
//////显示或者隐藏菜单/////
function sheditmenu(span){
  if($(span).hasClass('seleditmenu')){
    $(span).removeClass('seleditmenu');
    $('.editmenu').css('display','none');
  } else{
    $('.seleditmenu').removeClass('seleditmenu');
    $(span).addClass('seleditmenu');
    $('.editmenu').css('top',btnmenuY-95+"px");
    $('.editmenu').css('left',btnmenuX-120+"px");
    $('.editmenu').css('display','block');
    if($(span).attr('data-shelves')=='1'){
      $('.imgshelve').attr('src',imgurl + "xjpro.png");
      $('.lshelve').text('下架');
    } else{
      $('.imgshelve').attr('src',imgurl + "sjpro.png");
      $('.lshelve').text('上架');
    }
  }
}
////////点击编辑///////
function proedit(div){
  var proid= $('.seleditmenu').attr('data-proid');
  var updateproduct=updatepro.replace(/PRODUCTID/g,proid);
  window.location.href=updateproduct;
}
//////是否在销售////////////////
function proshelve(div){
  var proid= $('.seleditmenu').attr('data-proid');
  var shelve=$('.seleditmenu').attr('data-shelves');
  $.ajax({
    type:"post",
    url:editshelve,
    data:"ProId="+proid+"&IsShelves="+shelve,
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
        if (msg.status == 'true') {
          window.location.reload();//刷新当前页面.
        } else{
          tips('notice', '修改失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
//////删除商品////////////////
function prodel(div){
  var proid= $('.seleditmenu').attr('data-proid');
  $.ajax({
    type:"post",
    url:deletepro,
    data:"ProId="+proid,
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
        if (msg.status == 'true') {
          window.location.reload();//刷新当前页面.
        } else{
          tips('notice', '删除失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
function loadmore_1(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_1_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="prodata" data-proid='+data[key].ProId+'>'+
          '<img class="proimg" src='+data[key].ProLogoImg+'>'+'<div class="prodata-1">'+
          '<label class="protitle">'+data[key].ProTitle+'</label>'+
          '<label class="proprice">￥'+data[key].PriceRange+'</label>'+
          '<label class="propcount">库存:'+data[key].PCount+'<span class="czicon" '+
          ' data-proid='+data[key].ProId+' data-shelves='+data[key].IsShelves+' onclick="sheditmenu(this)"></span>'+
          '<span class="proscont">销量:'+data[key].SCount+'</span></label></div></div>';
        }
        $(".pro-1").before(htmls);
        $(".prolist-1").attr("data-p",ppage);
        $('.pro-1').text('加载更多');
      } else {
        $('.pro-1').text('已全部加载完');
      }
    }
  })
}
function loadmore_2(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_2_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="prodata" data-proid='+data[key].ProId+'>'+
          '<img class="proimg" src='+data[key].ProLogoImg+'>'+'<div class="prodata-1">'+
          '<label class="protitle">'+data[key].ProTitle+'</label>'+
          '<label class="proprice">￥'+data[key].PriceRange+'</label>'+
          '<label class="propcount">库存:'+data[key].PCount+'<span class="czicon" '+
          ' data-proid='+data[key].ProId+'  data-shelves='+data[key].IsShelves+' onclick="sheditmenu(this)"></span>'+
          '<span class="proscont">销量:'+data[key].SCount+'</span></label></div></div>';
        }
        $(".pro-2").before(htmls);
        $(".prolist-2").attr("data-p",ppage);
        $('.pro-2').text('加载更多');
      } else {
        $('.pro-2').text('已全部加载完');
      }
    }
  })
}
function loadmore_3(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_3_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="prodata" data-proid='+data[key].ProId+'>'+
          '<img class="proimg" src='+data[key].ProLogoImg+'>'+'<div class="prodata-1">'+
          '<label class="protitle">'+data[key].ProTitle+'</label>'+
          '<label class="proprice">￥'+data[key].PriceRange+'</label>'+
          '<label class="propcount">库存:'+data[key].PCount+'<span class="czicon" '+
          ' data-proid='+data[key].ProId+'  data-shelves='+data[key].IsShelves+' onclick="sheditmenu(this)"></span>'+
          '<span class="proscont">销量:'+data[key].SCount+'</span></label></div></div>';
        }
        $(".pro-3").before(htmls);
        $(".prolist-3").attr("data-p",ppage);
        $('.pro-3').text('加载更多');
      } else {
        $('.pro-3').text('已全部加载完');
      }
    }
  })
}
function loadmore_4(page){
  var ppage= parseInt(page)+1;
  var htmls='';
  $.ajax({
    type:"post",
    url:loadmore_4_url,
    data:"page="+ppage,
    dateType:"json",
    complete: function (e) {
      //
    },
    success:function(msg){
      if (msg.status == 'true') {
        var data = msg.info;
        for (var key in data){
          htmls+='<div class="prodata" data-proid='+data[key].ProId+'>'+
          '<img class="proimg" src='+data[key].ProLogoImg+'>'+'<div class="prodata-1">'+
          '<label class="protitle">'+data[key].ProTitle+'</label>'+
          '<label class="proprice">￥'+data[key].PriceRange+'</label>'+
          '<label class="propcount">库存:'+data[key].PCount+'<span class="czicon" '+
          ' data-proid='+data[key].ProId+'  data-shelves='+data[key].IsShelves+' onclick="sheditmenu(this)"></span>'+
          '<span class="proscont">销量:'+data[key].SCount+'</span></label></div></div>';
        }
        $(".pro-4").before(htmls);
        $(".prolist-4").attr("data-p",ppage);
        $('.pro-4').text('加载更多');
      } else {
        $('.pro-4').text('已全部加载完');
      }
    }
  })
}
