$(document).ready(function(){
  // 点击星星
  $('.starsinfo>.xing_icon').on('tap',function(){
    $(this).parent('.starsinfo').children('.xing_icon').removeClass('setstartactive');
    var index = $(this).index();
    for (var i = 0; i <= index; i++) {
      $(this).parent('.starsinfo').children('.xing_icon').eq(i).addClass('setstartactive');
    }
  });
  // 点击提交按钮
  $('.sendbtn').on('tap',function(){
    sendevaluationinfo();
  });
});
// 提交评价信息
function sendevaluationinfo(){
  var shopevalinfo = {};
  var prosevalinfo = new Array();
  var prosevaljson = {};
  var stoken = $('.evalinfopart').attr('data-stoken');
  var oid = $('.evalinfopart').attr('data-oid');
  // 门店评价信息
  var shopservicestart = $('.shopservice>.starsinfo>.setstartactive').length;
  var shopprosstart = $('.sevalinfos>.shoppros>.starsinfo>.setstartactive').length;
  var shopvaltext = $('.shopevalpart>.sevalinfos>.inputtextinfo>.inputinfo').val();
  // 商品评价信息
  $('.prolistpart>.proevalpart').each(function(index,item){
    var pid = $(item).attr('data-pid');
    var olid = $(item).attr('data-olid');
    var selprostart = $(item).find('.setstartactive').length;
    var selprovaltext = $(item).find('.inputinfo').val();
    if (selprostart !=0 && $.trim(selprovaltext)!='') {
      prosevalinfo.push(index);
      prosevaljson[index]={};
      prosevaljson[index]['pid']=pid;
      prosevaljson[index]['olid']=olid;
      prosevaljson[index]['start']=selprostart;
      prosevaljson[index]['content']=selprovaltext;
      prosevaljson[index]['stoken']=stoken;
    }
  });
  if((shopservicestart!=0 && shopprosstart!=0 && $.trim(shopvaltext)!='') || prosevalinfo.length >0 ){
    if(shopservicestart!=0 && shopprosstart!=0 && $.trim(shopvaltext)!=''){
      shopevalinfo['shoptart'] = shopservicestart;
      shopevalinfo['prostart'] = shopprosstart;
      shopevalinfo['content'] = shopvaltext;
      shopevalinfo['stoken'] = stoken;
    }

    var senddata = {
      'oid':oid,
      'shopevalinfo':shopevalinfo,
      'prosevalinfo':prosevaljson
    };
    showwaiting('正在提交...');
    $.ajax({
      url:setuserevaluation_url,
      type:"post",
      data:senddata,
      dataType:"json",
      complete:function(){
        closeWaiting();
      },
      success:function(msg){
        if (msg.status == 'true') {
          mui.toast('评论提交成功');
          setTimeout(function(){
           window.location.href = userorders_url;
         },500);
        } else {
            mui.toast('评论提交失败');
        }
      },
      error:function(e){
        mui.toast('评论提交失败');
      }
    });

  } else {
    mui.toast('至少完整评价一条');
  }
}
