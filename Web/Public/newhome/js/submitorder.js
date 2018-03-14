var alltotalprice = 0;
var ototalprice=0;
var redpackprice = 0;
var redid = '';
var totalprice = 0;
$(document).ready(function(){
  // 打开红包列表
  $('.redparts').on('tap',function(){
    $('.redpackmark').css('display','block');
  });
  // 关闭选择的红包
  $('.closeredpack').on('tap',function(){
    $('.redpackmark').css('display','none');
  });
  // 关闭选择的红包
  $('.redinfo').on('tap',function(){
    var mrid = $(this).attr('data-mrid');
    var rid = $(this).attr('data-rid');
    var redrules = $(this).attr('data-redrules');
    $('.redprice').attr('data-price',redrules);
    $('.redprice').attr('data-redid',rid);
    $('.redprice').attr('data-mredid',mrid);
    $('.redprice').text(parseFloat(redrules).toFixed(2));
    redpackprice=redrules;
    redid = rid;
    $('.redpackmark').css('display','none');
    settotalprice();
  });
  // 打开选择就餐人数
  $('.eatnums').on('tap',function(){
    $('.eatingnummark').css('display','block');
  });
  // 关闭选择就餐人数
  $('.closeeatingnum').on('tap',function(){
    $('.eatingnummark').css('display','none');
  });
  // 选择就餐人数
  $('.seleatnum').on('tap',function(){
    $('.seleatnumactive').removeClass('seleatnumactive');
    $(this).addClass('seleatnumactive');
    var eatnums = $(this).attr('data-nums');
    if (eatnums == '10+') {
      $('.showeatnum').text('10人以上');
    } else {
      $('.showeatnum').text(eatnums+'人');
    }
    $('.eatingnummark').css('display','none');
  });
  // 打开填写备注信息
  $('.eatremarks').on('tap',function(){
    $('.remarkmark').css('display','block');
  });
  // 关闭填写备注信息
  $('.closeremark').on('tap',function(){
    $('.remarkmark').css('display','none');
    $('.remarkcontent').val('');
  });
  // 确定填写备注信息
  $('.sureremark').on('tap',function(){
    var remarkcontent = $('.remarkcontent').val();
    $('.remarkmark').css('display','none');
    if ($.trim(remarkcontent) != '') {
      $('.showremark').text(remarkcontent);
    } else {
      $('.showremark').text('备注说明');
    }
  });
  // 选择备注固定话术
  $('.remark_list_info').on('tap',function(){
    var remarkcontent = $(this).text();
    $('.remarkcontent').val(remarkcontent);
  });
  // 是否显示进店红包
  if(inredprice != '') {
    $('.inredinfo').css('display','block');
    $('.inredprice').text(parseFloat(inredprice).toFixed(2));
  }
  setcartsinfo();
  setsubmitbtn();
});
// 进入时显示购物车信息
function setcartsinfo(){
  if (cartinfo !='') {
    var cartsArray = JSON.parse(cartinfo);
    var htmls ='';
    var clength = 0;
    $.each(cartsArray,function(index,item){
      htmls +='<div class="proinfo">'+
      '<img src="'+item['ProLogoImg']+'" alt="">'+
      '<span class="pname">'+item['ProName']+'</span>'+
      '<span class="prospec">('+item['ProSpec1']+')</span>'+
      '<span class="psnums">×'+item['snums']+'</span>'+
      '<span class="pprice">'+item['Price']+'</span>'+
      '</div>';
      clength++;
      totalprice = totalprice + (item['Price'] * item['snums']);
      ototalprice = ototalprice +(item['OldPrice'] * item['snums']);
    })
    $('.pro_list').html(htmls);
    $('.alltotalprice>.aprice').text(parseFloat(totalprice).toFixed(2));
    $('.totalprcie>span').text(parseFloat(totalprice).toFixed(2));
    alltotalprice = totalprice;
    $('.fullprice').text(parseFloat(ototalprice - alltotalprice).toFixed(2));
    if(clength >3){
      $('.pro_list').css('height',85*3 + 'px');
      $('.getmorepros').css('display','block');
      setgetmorebtn();
    }
  }
  settotalprice();
}

// 设置总价格
function settotalprice(){
  if (inredprice!='') {
    totalprice = alltotalprice - redpackprice - parseFloat(inredprice).toFixed(2);
  } else {
    totalprice = alltotalprice - redpackprice;
  }
  var yhprice = ototalprice - totalprice;

  if(totalprice <=0){
    $('.alltotalprice>.aprice').text('0.00');
    $('.totalprcie>span').text('0.00');
  } else {
    $('.alltotalprice>.aprice').text(parseFloat(totalprice).toFixed(2));
    $('.totalprcie>span').text(parseFloat(totalprice).toFixed(2));
  }
  $('.yhinfo>span').text(parseFloat(yhprice).toFixed(2));

}

// 设置展开更多点击事件
function setgetmorebtn(){
  $('.getmorebtn').on('tap',function(){
    if ($(this).attr('data-type')=='0') {
      $(this).attr('data-type','1');
      $(this).children('.showtext').text('收起更多');
      $(this).children('.showgeticon').removeClass('mui-icon-arrowdown').addClass('mui-icon-arrowup');
      $('.pro_list').css('height','auto');
    } else {
      $(this).attr('data-type','0');
      $(this).children('.showtext').text('展开更多');
      $(this).children('.showgeticon').removeClass('mui-icon-arrowup').addClass('mui-icon-arrowdown');
      $('.pro_list').css('height',85*3 + 'px');
    }
  })
}

// 提交按钮处理
function setsubmitbtn(){
  $('.submitbtn').on('tap',function(){
    if ($(this).hasClass('hassendorderactive')) {
      mui.toast('此订单已存在');
      return false;
    }
    if (cartinfo !='') {
      var eatingnum = $('.seleatnumactive').attr('data-nums');
      var remarkcontent = $('.remarkcontent').val();
      var senddata ={
        prosinfo : cartinfo,
        orderid :orderid,
        redpackprice :redpackprice,
        redid :redid,
        eatingnum:eatingnum,
        remarkcontent:remarkcontent,
        inredprice:inredprice,
      };
      $('.submitbtn').addClass('hassendorderactive');
      showwaiting('正在提交...');
      $.ajax({
        url:createorder_url,
        type:"post",
        data:senddata,
        dataType:"json",
        complete:function(){
          closeWaiting();
        },
        success:function(msg){
          if (msg.status == 'true') {
            mui.toast('订单提交成功');
            setTimeout(function(){
             window.location.href = payorder_url;
           },500);
          } else {
            if (msg.info=='ORDERHASD') {
              mui.toast('此订单已存在');
            } else {
              $('.submitbtn').removeClass('hassendorderactive');
              mui.toast('订单提交失败');
            }
          }
        },
        error:function(e){

        }
      });
    } else {
      mui.toast('无商品信息');
    }
  });
}
