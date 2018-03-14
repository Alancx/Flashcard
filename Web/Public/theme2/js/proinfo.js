$(document).ready(function(){


  //商品信息标签页切换
  $(".tab").click(function(){
    $(".tab").attr('class','tab');
    $(this).addClass('tab-active');
    $(".i-box").hide();
    $("#"+$(this).attr('data-id')).show();
  });

  //收藏显示效果
  $("#clt").click(function(){
    var type=$(this).attr('data-s');

      $.ajax({
        url:clturl,
        type:"post",
        data:"pid="+pid+"&type="+type,
        dataType:"json",
        beforeSend:function(){
          tips('waiting','数据处理中...');
        },
        success:function(msg){
          $("#waiting").hide();
          // alert(msg);
          if (msg.status=='true') {
            if (type=='clted') {
              $("#clt").attr('src',imgurl+'shoucang.png');
              $('.clt').html('收藏');
              $('#clt').attr('data-s','noclt');
            };
            if (type=='noclt') {
              $("#clt").attr('src',imgurl+'yishoucang.png');
              $('.clt').html('已收藏');
              $('#clt').attr('data-s','clted');
            };
            // $("#clt").removeAttr('id');
          }else if (msg=='nologin') {
            tips('notice','请登录',2000,'weui_icon_notice')
            window.location.href=login;
          }else{
            tips('notice','操作失败',2000,'weui_icon_notice');
          }
        }
      });
  });
  //购物车模态框
  $("#car-close").click(function(){
    // $('.car-box').removeClass().addClass('car-box animated fadeOutDownBig').hide();
    // $('.shop-car').removeClass().addClass('shop-car animated zoomOut').hide();
    $('.shop-car').hide();
  });
  $(".buy-now,.add-car").click(function(){
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
        $('#moneyspan').html('￥'+msg.price);

        if (msg.status) 
        {
          $('#kc').html(msg.count-1);
          $('#nums').val('1');
          $('#sure').removeClass('disabled').attr('data-st','enable');

          var tempoStoreStr="";
          tempoStoreStr+='<option value="-1" selected="selected">本店</option>';
          $('#oStore').html(tempoStoreStr);

        }
        else
        {

          tips('notice','库存不足 ：(',2000);
          $('#kc').html('0');
          $('#nums').val('0');
          $('#sure').attr('class','btn btn-warning disabled').attr('data-st','disabled');

          var tempoStoreStr="";

          if (stype=="car") 
          {
            tempoStoreStr+='<option value="-1" selected="selected">本店</option>';
          }
          else
          {
            tempoStoreStr+='<option value="X" selected="selected">--请选择--</option>';
            tempoStoreStr+='<option value="0">平台总店</option>';
            
            if (msg.showStore) 
            {
               $.each(msg.storeData,function(k,v){
                tempoStoreStr+='<option value="'+v['id']+'">'+v['storename']+'</option>';
               });
            }
          }
          
          $('#oStore').html(tempoStoreStr);
        }

        if (stype=="car") {
          $('#oStoreDiv').hide();
        }
        else
        {
          if (msg.showStore) 
          {
            $('#oStoreDiv').show();
          }
          else
          {
            $('#oStoreDiv').hide();
          }
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

        if (nexttype=='car') 
        {
          tips('waiting','数据处理中...');
          $.ajax({
            url:addcarurl,
            type:"post",
            data:{"id":pid,"attr":attrs,"nums":carnums},
            dataType:"json",
            success:function(msg){
              $("#waiting").hide();
              if (msg.status=='true') {
                if (msg.info=='Success') {
                  tips('notice','添加成功，在购物车等亲~',1000);

                  $(".car-box").removeClass().addClass('car-box animated rollOut');
                  setTimeout("$('.shop-car').hide()","1500");
                }
              }
            }
          });
        };

        if (nexttype=='buy') 
        {
          tips('waiting','正在跳转...',100000);
          // return false;
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

          $('#moneyspan').html('￥'+msg.price);

          if (msg.status) 
          {
            $('#kc').html(msg.count-1);
            $('#nums').val('1');
            $('#sure').removeClass('disabled').attr('data-st','enable');

            var tempoStoreStr="";
            tempoStoreStr+='<option value="-1" selected="selected">本店</option>';
            $('#oStore').html(tempoStoreStr);
          }
          else
          {
            tips('notice','库存不足 ：(',2000,'weui_icon_notice');
            $('#kc').html('0');
            $('#nums').val('0');
            $('#sure').attr('class','btn btn-warning disabled').attr('data-st','disabled');

            var tempoStoreStr="";

            if (stype=='car') {
              tempoStoreStr+='<option value="-1" selected="selected">本店</option>';
            }
            else
            {
              tempoStoreStr+='<option value="X" selected="selected">--请选择--</option>';
              tempoStoreStr+='<option value="0">平台总店</option>';
              if (msg.showStore) 
              {
                 $.each(msg.storeData,function(k,v){
                  tempoStoreStr+='<option value="'+v['id']+'">'+v['storename']+'</option>';
                 });
              }
            }



            $('#oStore').html(tempoStoreStr);

          }

          if (stype=="car") {
            $('#oStoreDiv').hide();
          }
          else
          {
            if (msg.showStore) 
            {
              $('#oStoreDiv').show();
            }
            else
            {
              $('#oStoreDiv').hide();
            }
          }
        }
      });

    }
  });



  $('#oStore').change(function(e){

    if ($(this).val()=="X") {

      $('#kc').html('0');
      $('#nums').val('0');
      $('#sure').attr('class','btn btn-warning disabled').attr('data-st','disabled');
    }
    else
    {
      //初始化页面时拉取默认商品规格库存
      var defaults=$('.atr-select').attr("data-s");

      tips('waiting','数据加载中~');
      $.ajax({
        url:whurl,
        type:"post",
        data:{'pid':defaults,'isOther':'O','stoken':$(this).val()},
        dataType:"json",
        success:function(msg){
          $('#waiting').hide();
          $('#moneyspan').html('￥'+msg.price);

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
})
