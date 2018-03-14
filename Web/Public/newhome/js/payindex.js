$(document).ready(function(){
  if (oid != '') {
    if (parseFloat(orderprice)>=0) {
      $('.truezhifu').addClass('truezhifuactive');
    }
  }
  $('.truezhifu').on('tap',function(){
    if($(this).hasClass('truezhifuactive')){
      if (parseFloat(orderprice)>0) {
        if (paystatus=='true') {
          callpay();
        } else {
          mui.toast('获取支付信息失败');
        }
      } else {
        var senddata={
          oid:oid,
        }
        showwaiting('正在支付...');
        $.ajax({
          url:pay_url,
          type:"post",
          data:senddata,
          dataType:"json",
          complete:function(){
            closeWaiting();
          },
          success:function(msg){
            if (msg.status == 'true') {
              // alert("支付成功");
              mui.toast('支付成功');
              // setTimeout(function() { window.location.href=paysuccess_url;}, 1000);
              showshareinfo();
            } else {
              alert("支付失败");
            }
          },
          error:function(e){
            alert("支付失败");
          }
        });
      }
    }
  })
});

function showshareinfo(){
  $('.maskpacketnew').css('display','block');
  $('.showredinfo').css('display','block');
  $('.showgetredprice>span').text('分享');
  $('.showredtextinfo>span').text('点击立即分享可得红包');
  $('.redbtn_1').css('display','block');
  $('.redbtn_1>span').text('立即分享');
  $('.redbtn_2').css('display','block');
  $('.showredinfo>.showgetredprice>span::before').css('content','');
}
