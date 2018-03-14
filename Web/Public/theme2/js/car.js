$(document).ready(function(){
  //选择按钮点击操作
  $('.chose').click(function(){
    var datas=$(this).attr('data-s'); //获取当前按钮标识
    var dataa=$(this).attr('data-a');  //获取当前分类id
    var status=$(this).attr('data-st');  //获取当前按钮选中状态
    var pid=$(this).attr('data-pid');  //获取当前操作商品id
    // var pnum=parseInt($('#'+pid+' .p-nums').html());  //获取当前操作商品数量
    var allmoney=parseFloat($('.car-money').attr('data-s'));  //获取购物车当前价格
    var allnums=parseInt($('.car-nums').attr('data-s'));  //获取购物车当前数量
    // console.log($('#'+pid+' .p-nums').html());

////////////////////////////////////////////////////////选择单个门店的商品
// alert(dataa);
var nowShopObj=$(".chose[data-s='all'][data-st='1']");
var nowShop='';
if (nowShopObj.length>0) {

  nowShop=nowShopObj.first().attr('data-a');
  if (dataa!=nowShop) {
      tips('alert','每次只能同时结算一家门店的商品');
      return false;
  }

}




///////////////////////////////////////////////////////////
    if (datas=='all')
    {  //分类按钮点击操作
      var cid=$(this).parent().parent().attr('id');
      var isedit=$('#'+cid+' .edit').attr('data-s');
      var sons=$('#'+dataa+' .getprice');  //该分类下商品单价
      var sonsnums=$('#'+dataa+' .p-nums');  //改分类下商品数量
      var mysons=$('.'+cid);
      var sonsprice=0;
      var addsonsprice=0;

      if (isedit=='edited')
      { //判断当前商品是否在选中状态
        tips('alert','请完成编辑后进行选择操作');
        return false;
      }

      for (var i = 0; i < sons.length; i++)
      {
        if (mysons[i].getAttribute('data-st')=='1') {
          var sprice=parseFloat(sons[i].innerHTML);
          var snum=parseInt(sonsnums[i].innerHTML);
          sonsprice+=sprice*snum;
          // console.log(sonsprice);
        }
        var sprice=parseFloat(sons[i].innerHTML);
        var snum=parseInt(sonsnums[i].innerHTML);
        addsonsprice+=sprice*snum;
      }  //计算当前分类下商品总额与数量
      // console.log(addsonsprice);
      if (status=='0')
      {  //根据按钮状态操作
        var numsall=sons.length+allnums;
        $(this).attr('src',imgurl+'xuanze2.png').attr('data-st','1');
        $('.'+dataa).attr('src',imgurl+'xuanze2.png').attr('data-st','1');
        $('.car-money').html((allmoney+addsonsprice).toFixed(2)).attr('data-s',(allmoney+addsonsprice));
        $('.car-nums').show().html('('+numsall+')').attr('data-s',numsall);
      }
      else
      {
        var numsall=allnums-sons.length;
        $(this).attr('src',imgurl+'weixuanze.png').attr('data-st','0');
        $('.'+dataa).attr('src',imgurl+'weixuanze.png').attr('data-st','0');
        $('.car-money').html((allmoney-sonsprice).toFixed(2)).attr('data-s',(allmoney-sonsprice));
        $('.car-nums').show().html('('+numsall+')').attr('data-s',numsall);
      }
    }
    else
    {
      //分类下商品选择按钮操作
      var thisprice=parseFloat($('#'+pid+' .getprice').html()); //当前商品价格
      var thisnum=parseInt($('#'+pid+' .p-nums').html());  //当前商品数量
      var lsprice=thisprice*thisnum;  //计算选中总额
      var cid=$(this).parent().parent().parent().attr('id');
      var isedit=$('#'+cid+' .edit').attr('data-s');
      if (isedit=='edited') { //判断当前商品是否为选中状态
        tips('alert','请完成编辑后进行选中操作');
        return false;
      }
      if (status=='0')
      {
        var numsall=allnums+1;  //单个操作数量+1 （）
        $(this).attr('src',imgurl+'xuanze2.png').attr('data-st','1');
        $('.'+datas+'-all').attr('src',imgurl+'xuanze2.png').attr('data-st','1');
        $('.car-money').html((allmoney+lsprice).toFixed(2)).attr('data-s',(allmoney+lsprice));
        $('.car-nums').show().html('('+numsall+')').attr('data-s',numsall);
      }
      else
      {
        var numsall=allnums-1;
        $(this).attr('src',imgurl+'weixuanze.png').attr('data-st','0');
        $('.car-money').html((allmoney-lsprice).toFixed(2)).attr('data-s',(allmoney-lsprice));
        $('.car-nums').show().html('('+numsall+')').attr('data-s',numsall);
      }
      // console.log(datas);
      var sons=$('.'+datas);
      var chosestatu=true;
      for (var i = 0; i < sons.length; i++) {
        if (sons[i].getAttribute('data-st')=='1') {
          chosestatu=false;
          break;
        }
      }
      // console.log(chosestatu);
      if (chosestatu==true) {
        $('.'+datas+'-all').attr('src',imgurl+'weixuanze.png').attr('data-st','0');
      }
    }
    var allsons=$('.chose');
    var allstatus=false;
    for (var i = 0; i < allsons.length; i++) {
      if (allsons[i].getAttribute('data-st')=='0') {
        allstatus=true;
        break;
      }
    }
    // console.log(allstatus);
    if (allstatus==true) {
      // console.log(datas);
      $("#select-all").attr('data-st','no');
      $("#select-all img").attr('src',imgurl+'weixuanze.png')
    }else{
      $("#select-all").attr('data-st','yes');
      $("#select-all img").attr('src',imgurl+'xuanze2.png')
    }
  });
  //点击图片跳转到商品详情页////
  $(".p-img").click(function(){
    var purl=$(this).attr('data-url');
   window.location.href=purl;
  })

  //全选按钮操作
  $("#select-all").click(function(){
    var alledit=$('.edit');
    var isedit=false;
    for (var i = 0; i < alledit.length; i++) {
      if (alledit[i].getAttribute('data-s')=='edited') {
        isedit=true;
        break;
      }
    }
    if (isedit==true) {
      tips('alert','请完成商品的编辑状态之后选择');
      return false;
    }
    var status=$(this).attr('data-st'); //获取当前按钮状态
    var nums=parseInt($('.car-nums').attr('data-s')); //获取当前购物车选中商品数量
    var allprice=$(".getprice");  //allprice.length购物车内商品种类数量
    var allnums=$('.p-nums');
    var allmoney=0;
    // alert('123');
    for (var i = 0; i < allprice.length; i++) {
      var price=parseFloat(allprice[i].innerHTML);
      var num=parseInt(allnums[i].innerHTML);
      allmoney+=price*num;  //计算购物车内所有商品价格总和
    }
    // console.log(status);
    if (status=='no') {  //当前未选中状态操作
      $(this).attr('data-st','yes');
      $('.chose').attr('src',imgurl+'xuanze2.png').attr('data-st','1');
      $(this).find('img').attr('src',imgurl+'xuanze2.png');
      // console.log(allmoney);
      $('.car-nums').show().html('('+allprice.length+')').attr('data-s',allprice.length);  //数量赋值
      $('.car-money').html(allmoney.toFixed(2)).attr('data-s',allmoney);  //价格赋值
      // console.log($(this).attr('data-st'));
    }else{  //当前状态选中操作
      // console.log($(this).attr('data-st')+'1');
      $('.chose').attr('src',imgurl+'weixuanze.png').attr('data-st','0');
      $(this).find('img').attr('src',imgurl+'weixuanze.png');
      $(this).attr('data-st','no');
      $('.car-nums').hide().attr('data-s','0');
      $('.car-money').html('0.00').attr('data-s','0');
    }
  })
  //全选按钮操作-end
  //结算
  $('.gopay').click(function(){
    var allpro=$('.chose');
    var allproid='';
    $.each(allpro,function(index,item){
      if ($(item).attr('data-st')=='1') {
        var tempId=$(item).attr('data-idcard');
        if (tempId) {
          allproid+=tempId+',';
        };
      };
    });
    // console.log(allproid);
    // return true;
    if (allproid) {
        $(".pro-box").removeClass().addClass('pro-box animated rollOut');
        // $('.pro-box').remove();//未确定效果
        tips('notice','前往结算~',1000);
        window.location.href=orderurl+"?type=C&GoodsInfo="+allproid;
    }else{
      tips('notice','请选择一件商品后结算',1500,'weui_icon_notice');
    }
  })
  //编辑功能
  $('.edit').click(function(){
    var fid=$(this).parent().parent().attr('id');
    var sons=$('#'+fid+' .p-infos .base-info');
    var sonsedit=$('#'+fid+' .p-infos .edit-info');
    var sonsval=$('#'+fid+' .edit-info input');
    var sonsnum=$('#'+fid+' .p-nums');
    var datas=$(this).attr('data-s');
    var fstatus=$('.'+fid+'-all').attr('data-st');
    if (fstatus=='0') {
      if (datas=='edit') {
        for (var i = 0; i < sons.length; i++) {
          sons[i].setAttribute('style','display:none;');
          sonsedit[i].setAttribute('style','display:block;');
          sonsval[i].value=sonsnum[i].innerHTML
        }
        $(this).attr('data-s','edited').html('完成');
      }else{
        for (var i = 0; i < sons.length; i++) {
          sonsedit[i].setAttribute('style','display:none;');
          sons[i].setAttribute('style','display:block;');
          sonsnum[i].innerHTML=sonsval[i].value;
        }
        $(this).attr('data-s','edit').html('编辑');
      }
    }else{
      tips('alert','请取消商品的选中状态之后进行编辑');
    }
    // console.log(sons.length);
  })

  //编辑完成
  //加减数量
  $('.less').click(function(){
    var clname=$(this).parent().parent().parent().attr('id');
    var idcard=$(this).parent().parent().parent().attr('data-idcard');
    var plang=$('.'+clname);
    var now=0
    if (plang.length==1) {
      now=$('#'+clname+' .edit-info input').val();
    }else{
      $.each(plang,function(index,item){
        if ($(item).attr('data-idcard')==idcard) {
          // console.log($(item).find('input').val());
          now =parseInt($(item).find('input').val());
        }
      })
    }
    var newnum=now-1;
    var proidcard=idcard;
    if (newnum<1) {
      newnum=1;
    }else{
      tips('waiting','数据处理中...');
      $.ajax({
        url:editcarurl,
        type:"post",
        data:{"id":clname,"attr":proidcard.replace(clname,''),"nums":-1},
        dataType:"json",
        success:function(msg){
          $('#waiting').hide();
          // console.log(msg);
          if (msg.status=='true') {
            var plang=$('.'+clname);
            if (plang.length==1) {
              $('#'+clname+' .edit-info input').val(newnum);
            }else{
              $.each(plang,function(index,item){
                if (item.getAttribute('data-idcard')==idcard) {
                  $(item).find('input').val(newnum);
                }
              })
            }
          }
        }
      })
    }
    // $('#'+clname+' .edit-info input').val(newnum);
  })
  $('.add').click(function(){
    var clname=$(this).parent().parent().parent().attr('id');
    var idcard=$(this).parent().parent().parent().attr('data-idcard');
    var plang=$('.'+clname);
    var now=0
    if (plang.length==1) {
      now=$('#'+clname+' .edit-info input').val();
    }else{
      $.each(plang,function(index,item){
        if ($(item).attr('data-idcard')==idcard) {
          // console.log($(item).find('input').val());
          now =parseInt($(item).find('input').val());
        }
      })
    }
    var newnum=parseInt(now)+parseInt(1);
    // console.log(newnum);
    var proidcard=idcard;
    tips('waiting','数据处理中...');
    $.ajax({
      url:editcarurl,
      type:"post",
      data:{"id":clname,"attr":proidcard.replace(clname,''),"nums":+1},
      dataType:"json",
      success:function(msg){
        $('#waiting').hide();
        // console.log(msg);
        if (msg.status=='true') {
          if (plang.length==1) {
            $('#'+clname+' .edit-info input').val(newnum);
          }else{
            $.each(plang,function(index,item){
              if (item.getAttribute('data-idcard')==idcard) {
                // console.log(item.getAttribute('data-idcard')+'-----'+idcard);
                $(item).find('input').val(newnum);
                // console.log($(item).find('input').val()+'----'+newnum);
              }
            })
          }
          // $('#'+clname+' .edit-info input').val(newnum);
        }
      }
    })
  })
  //删除
  $('.delete').click(function(){
    var proid=$(this).parent().parent().attr('id');
    var idcard=$(this).parent().parent().attr('data-idcard');
    $('#enter').attr('data-s',proid);
    $('#enter').attr('data-idcard',idcard);
    tips('confirm','确定要删除此商品吗？')
  })
  $('#esc').click(function(){$('#confirm').hide()});
  $('#enter').click(function(){
    $('#confirm').hide();
    var proid=$("#enter").attr('data-s');
    // console.log(proid);
    var cid=$('#'+proid).parent().parent().parent().attr('id');
    var idcard=$("#enter").attr('data-idcard');
    // var attr=idcard.replace('')
    // $('#'+proid).parent().remove();
    $.ajax({
      url:delcarurl,
      type:"post",
      data:{"id":proid,"attr":idcard.replace(proid,'')},
      dataType:'json',
      beforeSend:function(){
        tips('waiting','正在删除...');
      },
      success:function(msg){
        $("#waiting").hide();
        var plang=$('.'+proid);
        if (msg.status=='true') {
          if (plang.length==1) {
            $('#'+proid).parent().remove();
          }else{
            $.each(plang,function(index,item){
              if (item.getAttribute('data-idcard')==idcard) {
                $(item).parent().remove();
              }
            })
          }
          // $('#'+proid).parent().remove();
          tips('notice','已删除!','1500');
          var sons=$('#'+cid+' .p-box');
          // console.log(sons.length);
          if (sons.length=='0') {
            $('#'+cid).parent().remove();
          }
        }else{
          tips('notice','删除失败','1500','weui_icon_notice');
        }
      }
    })
    // var sons=$('#'+cid+' .p-box');
    // // console.log(sons.length);
    // if (sons.length=='0') {
    //   $('#'+cid).parent().remove();
    // }
  })

  //模态框操作
  $("#car-close").click(function(){
    // $('.car-box').removeClass().addClass('car-box animated fadeOutDownBig').hide();
    // $('.shop-car').removeClass().addClass('shop-car animated zoomOut').hide();
    $('.shop-car').hide();
  })
  $(".moreats").click(function(){
    var pid=$(this).parent().parent().parent().attr('id');
    $.ajax({
      url:readAttrs,
      type:"post",
      data:"pid="+pid,
      dataType:"json",
      success:function(msg){
        var atrs='';
        // console.log(msg);
        $.each(msg.attrs,function(index,item){
          atrs+="<span><b>"+index.split('_')[0]+"</b></span>"
          // console.log(index+'--'+item);
          $.each(item,function(inc,itm){
            // console.log(inc+'--'+itm);
            atrs+="<span class='atr' data-st='0' data-s='"+inc+"' onclick=scheck(this);>"+itm+"</span>";
          })
        })
        // console.log(atrs);
        $('.attrs-info').html(atrs);
        $("#sure").attr('data-s',pid);
        $("#car-title").html(msg.ProTitle);
        $(".car-img").attr('src',msg.ProLogoImg);
        $('#car-price').html(msg.Pricerange);
      }
    })
    $('.shop-car').show();
    $(".car-box").removeClass().addClass('car-box animated fadeInUpBig').show();
  })
  $("#sure").click(function(){
    var atrs=$('.atr-select');
    var attr='';
    var attrname='规格：';
    $.each(atrs,function(index,item){
      attr+=item.getAttribute('data-s');
      attrname+=item.innerHTML+',';
    })
    var pid=$("#sure").attr('data-s');
    var num=$('#'+pid+' .edit-info input').val();
    var idcard=$("#"+pid).attr('data-idcard');
    $.ajax({
      url:editcarurl,
      type:"post",
      data:{"id":pid,"attr":idcard.replace(pid,''),"nums":num,"status":"changeattr","newattr":attr},
      dataType:"json",
      success:function(msg){
        if (msg.status=='true') {
          $('#'+pid+' .edit-info .ats').html(attrname);
          $('.shop-car').hide();
        }
      }
    })
    // $('.shop-car').hide();
    // tips('notice','添加成功，在购物车等亲~',1000);
  })

  //alert提示框关闭
  $('#alertenter').click(function(){$('#alert').hide()});
  //商品属性选中状态操作
  $('.atr').click(function(){
    var statu=$(this).attr('data-st');
    if (statu=='0') {
      $(this).attr('data-st','1').attr('class','atr-select');
    }else{
      $(this).attr('data-st','0').attr('class','atr');
    }
  })

  //规格选中
  $('.attrs-info span').click(function(){
    var statu=$(this).attr('data-st');
    if (statu=='0') {
      var prt=$(this).parent();
      prt.children('.atr-select').attr('data-st','0').attr('class','atr');
      $(this).attr('data-st','1').attr('class','atr-select');
      var selected=$('.atr-select');
      var spid=pid+'';
      $.each(selected,function(index,item){
        spid+=item.getAttribute('data-s')
      })
      tips('waiting','数据加载中~');
      // console.log(spid);
    }
  })

})
