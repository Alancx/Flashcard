$(document).ready(function(){


  //商品信息标签页切换
  $(".tab").click(function(){
    $(".tab").attr('class','tab');
    $(this).addClass('tab-active');
    $(".i-box").hide();
    $("#"+$(this).attr('data-id')).show();
  });

  //购物车模态框
  $("#car-close").click(function(){
    $('.shop-car').hide();
  });

  $(".buy-now").click(function(){
    // $(".shop-car").removeClass().addClass('shop-car animated zoomIn').show();

    //初始化页面时拉取默认商品规格库存
    var defaults=$('.atr-select').attr("data-s");

    tips('waiting','数据加载中~');

    var stype=$(this).attr('data-s');

    $.ajax({
      url:whurl,
      type:"post",
      data:{'pid':defaults,'lat':latitude,'lon':longitude},
      dataType:"json",
      success:function(msg){
        $('#waiting').hide();
        $('#moneyspan').html(msg.price+'积分');

        if (msg.status) 
        {
          $('#kc').html(msg.count-1);
          $('#nums').val('1');
          $('#sure').removeClass('disabled').attr('data-st','enable');

        }
        else
        {

          tips('notice','库存不足 ：(',2000);
          $('#kc').html('0');
          $('#nums').val('0');
          $('#sure').attr('class','btn btn-warning disabled').attr('data-st','disabled');

        }

      }
    });

    var addtype=$(this).attr('data-s');
    $('.shop-car').show();
    $('#sure').attr('data-s',addtype);
    $(".car-box").removeClass().addClass('car-box animated fadeInUpBig').show();
  });



  $("#sure").click(function(){

    var carnums=$("#nums").val();
    if (carnums=="0") {
      tips('notice','商品数量不能为0 ：（',2000,'weui_icon_notice');

    }
    else
    {

      if ($(this).attr('data-st')=='enable') {
        var nexttype=$(this).attr('data-s');
        
        var attrids=$('.atr-select');
        var attrs=attrids.attr("data-s");

        if (nexttype=='buy') 
        {
          tips('waiting','正在跳转...',100000);

          $(".car-box").removeClass().addClass('car-box animated rollOut');
          //array('type'=>'G','GoodsInfo'=>'RESPACETEMP','oStoken'=>'RESPACESTOKEN','isOther'=>'RESPACEISOTHER')
          orderInfoStr='{"'+attrs+'":{"id":"'+pid+'","attr":"'+attrs+'","nums":'+carnums+'}}';
          orderUrl=orderUrl.replace(/RESPACETEMP/g,orderInfoStr);
          orderUrl=orderUrl.replace(/RESPACESTOKEN/g,$('#oStore').val());

          window.location.href=orderUrl;
        }

      };

    }

  });

  //规格选中
  $('.attrs-info span').click(function(){
    var statu=$(this).attr('data-st');

    var stype=$('#sure').attr('data-s');

    if (statu=='0') 
    {
      var prt=$(this).parent();
      prt.children('.atr-select').attr('data-st','0').attr('class','atr');
      $(this).attr('data-st','1').attr('class','atr-select');
      var selected=$('.atr-select').attr("data-s");

      tips('waiting','数据加载中~');
      $.ajax({
        url:whurl,
        type:"post",
        data:{'pid':selected,'lat':latitude,'lon':longitude},
        dataType:"json",
        success:function(msg){
          $('#waiting').hide();

          $('#moneyspan').html(msg.price+'积分');

          if (msg.status) 
          {
            $('#kc').html(msg.count-1);
            $('#nums').val('1');
            $('#sure').removeClass('disabled').attr('data-st','enable');
          }
          else
          {
            tips('notice','库存不足 ：(',2000,'weui_icon_notice');
            $('#kc').html('0');
            $('#nums').val('0');
            $('#sure').attr('class','btn btn-warning disabled').attr('data-st','disabled');

          }
        }
      });

    }
  });

  //数量加减
  $("#add").click(function(){
    var stocks=parseInt($("#kc").html());
    var num=parseInt($("#nums").val());
    if (stocks<1) {
      tips('notice','库存不足',2000,'weui_icon_notice');
      return false;
    }else{
      if ($("#sure").attr('data-st')=='disabled') {
        $("#sure").removeClass('disabled').attr('data-st','enable');
      }
      var newnum=num+1;
      var newstocks=stocks-1;
      $("#nums").val(newnum);
      $("#kc").html(newstocks);
    }
  })
  $("#less").click(function(){
    var stocks=parseInt($("#kc").html());
    var num=parseInt($("#nums").val());
    if (num<1) {
      $("#sure").attr('class','btn btn-warning disabled').attr('data-st','disabled');
      return false;
    }else{
      var newnum=num-1;
      var newstocks=stocks+1;
      if (newnum<1) {
        $("#sure").addClass('disabled').attr('data-st','disabled');
      }
      $("#nums").val(newnum);
      $("#kc").html(newstocks);
    }
  })
});
