var ajaxLock= false;//////锁定提交防止重复提交
$(document).ready(function(){
  $('.prolist').css('height',$('.allconternts').height()-$('.part_top').outerHeight()-$('.pro_list').outerHeight()-$('.submitpro').outerHeight()+'px');

  ///////////修改的时候////////////////
  if (whid!='0') {
    $('.prolist>.proitem').each(function(index,item){
      var pcid=$(item).attr('data-pcid');
      $('#ps_'+pcid).children('.suresel').addClass('sureselactive');
    })
  }

  /////////选择进货商品///////////////
  $('.suresel').click(function(){
    if ($(this).hasClass('sureselactive')) {
      $(this).removeClass('sureselactive');
    } else {
      $(this).addClass('sureselactive');
    }
  })
  /////////确定选择的商品////////////////////
  $('.suregselpro').click(function(){
      var htmls='';
    $('.getproinfo>.proitem').each(function(index,item){
      if ($(item).children('.suresel').hasClass('sureselactive')) {
        if ($('.prolist>#pl_'+$(item).attr('data-pcid')).length<=0) {
          htmls='<div class="proitem plitem" id="pl_'+$(item).attr('data-pcid')+'" data-pid="'+$(item).attr('data-pid')+'" data-pcid="'+$(item).attr('data-pcid')+'" data-pattr="'+$(item).attr('data-pattr')+'" data-pname="'+
          $(item).attr('data-pname')+'" data-cid="'+$(item).attr('data-cid')+'" data-pimg="'+$(item).attr('data-pimg')+'" data-cosp="'+$(item).attr('data-cosp')+'" data-price="'+$(item).attr('data-price')+'">'+
            '<span onclick="delselpro(this)"></span>'+
            '<img src="'+$(item).attr('data-pimg')+'" alt="">'+
            '<div class="pcontent">'+
              '<label class="pname">'+$(item).attr('data-pname')+'</label>'+
              '<label class="pattr">'+$(item).attr('data-pattr')+'</label>'+
              '<label class="pprice">售价:<span>￥'+$(item).attr('data-price')+'</span>&emsp;&emsp;进价:<span>￥'+$(item).attr('data-cosp')+'</span></label></div>'+
            '<div class="pnum">'+
            '<label>数量</label>'+
            '<input type="number" class="nums" name="" value="0">'+
            '</div></div>';
            $('.prolist').append(htmls);
            htmls='';
        }
      } else {
        $('.prolist>#pl_'+$(item).attr('data-pcid')).remove();
      }
    })
    $(".nums").keydown(function (e) {
    var code = parseInt(e.keyCode);
    if (code >= 96 && code <= 105 || code >= 48 && code <= 57 || code == 8) {
        return true;
    } else {
        return false;
    }
})
    $('.nums').on('input',function(){
      var str=$(this).val();
      if (str=='') {
        $(this).val('0');
      } else {
        str=parseInt(str);
        str=str+''.replace(/./g,'');
        $(this).val(str);
      }
    })
    $(".proconver").hide(100);
  })
  ////////提交订货申请//////////////////////
  $('.submitproL').click(function(){
    if (ajaxLock==true) {
      return;
    }
    //InWarehouseNumber   inputWarehouse   inputName   inDate     inType  Remarks   prolist
    var inputVar={
      id:'',
      whid:$(".house").attr('data-wid'),
      nums:0,
      whname:$(".house").attr('data-wname'),
      ipid:$(".oname").attr('data-uid'),
      ipname:$(".oname").attr('data-uname'),
      idate:$(".odate").attr('data-date'),
      itype:4,
      istatus:$(this).attr('data-type'),
      remarks:'',
    };
    var proarray={};
    if ($('.prolist>.proitem').length<=0) {
      tips('notice','请选择商品',2000,'weui_icon_notice');
      return false;
    }

    var tempNowProObj=null;
    var totalprice=0.00;
    var hasz=false;
    $('.prolist>.proitem').each(function(index,item){
      tempNowProObj=$(item);
      proarray[tempNowProObj.attr("data-pcid")]={
        price:tempNowProObj.attr('data-cosp'),
        nums:tempNowProObj.find(".nums").val(),
        cid:tempNowProObj.attr("data-cid"),
      };
      if((tempNowProObj.find('.nums').val()=='0')||(tempNowProObj.find('.nums').val()=='')){
        hasz=true;
      }
      if (tempNowProObj.attr('data-cosp')!='null') {
        totalprice=parseFloat(totalprice)+(parseFloat(tempNowProObj.attr('data-cosp')*parseFloat(tempNowProObj.find(".nums").val())));
      }
      tempNowProObj=null;
    });
    if (hasz==true) {
      tips('notice','填写选择商品的数量',2000,'weui_icon_notice');
      return false;
    }
    totalprice=parseFloat(totalprice).toFixed(2);
    ajaxLock=true;
    $.ajax({
      url:addwh_url,
      type:"post",
      data:{mt:inputVar,st:proarray,tp:totalprice,saveType:whid},
      dataType:"json",
      beforeSend:function(){
        tips('waiting','数据处理中...');
      },
      success:function(msg)
      {
        if (msg.status)
        {
          tips('notice','保存成功',2000,'weui_icon_notice');

          setTimeout(function(e){
              window.location.href=send_url;
          },2000);
        }
        else
        {
          tips('notice','操作失败',2000,'weui_icon_notice');
        }
      },
      complete:function()
      {
        $("#waiting").hide();
        ajaxLock=false;
      }
    });
  })
  ////////////////////////////////
});
///////////删除已选择的商品///////////////
function delselpro(span){
  var pcid=$(span).parent().attr('data-pcid');
  $(span).parent().remove();
  $('#ps_'+pcid+'>.suresel').removeClass('sureselactive');
}

function selectProFun(){
  $(".proconver").show(100);
}
