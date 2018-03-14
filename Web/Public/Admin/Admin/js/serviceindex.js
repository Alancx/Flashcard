$(document).ready(function(){
  $('.listmem').css('height',$('.memberlist').height()-$('.chattop').height()+'px');
  $('.contents').css('height',$('.chatcontent').height()-$('.chattop').height()-$('.inputcontent').height()-10+'px');
  oFReader = new FileReader(),
  rFilter = /^(?:image\/jpeg|image\/png)$/i;

  oFReader.onload = function (oFREvent) {
     savesimg();
  };

  $('.content').css('max-width',$('.chatcontent').width()-110+'px');

  $('#editchat').bind('keypress',function(event){
          if(event.keyCode == "13")
          {
              $('.btnsend').click();
          }
      });

  $('.btnsend').click(function(){
    if($('.editchat').val()!=''){
      if($('.mselactive').length>0){
        sendmesg();
      } else{
          art.dialog.alert('选择会员!');
      }
    } else {
      art.dialog.alert('填写发送内容!');
      // tips('notice', '填写发送内容!', 1500, 'weui_icon_notice');
    }
  })
  connect();
})
//////选择发送图片/////
function selsimg(ifile){
  if (ifile.files.length === 0){
    return;
  }
  var oFile = ifile.files[0];
  if (!rFilter.test(oFile.type)) {
    tips('notice', '选择正确图片格式!', 1500, 'weui_icon_notice');
    return;
  }else{
    oFReader.readAsDataURL(oFile);
  }
}
//////确定选择idcard///////////////
function savesimg(){
  $.ajaxFileUpload({
    url: kefusimg_url, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'simg', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        //  console.log(data.datainfo);
         sendimg(data.datainfo);
      }
    },
    complete: function (e) {

    },
    error: function (data,status,e)//服务器响应失败处理函数
    {

    }
  })
}
function sendimg(imgurl){
  var msay_data = '{"type":"kimg","content":"'+imgurl+'","cid":"'+$('.mselactive').attr('data-cid')+'"}';
  ws.send(msay_data);
  htmls='<div class="chatto chatcontent">'+
  '<img src="'+kefuimg+'">'+
  '<img src="'+imgurl+'" class="chattoimg">'+
  '<span></span></div>';
  $('.mem'+$('.mselactive').attr("id")).append(htmls);
  $('.chattoimg').css('width',($('.chatcontent').width()-110)/2+'px');
  $.ajax({
    type:"post",
    url:sendmsg_url,
    data:"sendconter="+imgurl+"&kfid="+kefuid+"&mid="+$('.mselactive').attr("id")+"&type=1",
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
        if (msg.status == 'true') {
          // tips('notice', msg.datainfo, 1500, 'weui_icon_notice');
          console.log('保存成功!');
        } else{
          // tips('notice', '发送失败!', 1500, 'weui_icon_notice');
          console.log('保存失败!');
        }
    },
  })
  $('.contents').scrollTop( $('.contents')[0].scrollHeight );
}

function sendmesg() {
  var msay_data = '{"type":"ksay","content":"'+$('.editchat').val()+'","cid":"'+$('.mselactive').attr('data-cid')+'"}';
  ws.send(msay_data);
  htmls='<div class="chatto chatcontent">'+
  '<img src="'+kefuimg+'">'+
  '<label class="content">'+$('.editchat').val()+'</label>'+
  '<span></span></div>';
  $('.mem'+$('.mselactive').attr("id")).append(htmls);
  $('.content').css('max-width',$('.chatcontent').width()-110+'px');
  $.ajax({
    type:"post",
    url:sendmsg_url,
    data:"sendconter="+$('.editchat').val()+"&kfid="+kefuid+"&mid="+$('.mselactive').attr("id"),
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
        if (msg.status == 'true') {
          // tips('notice', msg.datainfo, 1500, 'weui_icon_notice');
          console.log('保存成功!');
        } else{
          // tips('notice', '发送失败!', 1500, 'weui_icon_notice');
          console.log('保存失败!');
        }
    },
  })
  $('.editchat').val('');
  $('.editchat').focus();
  $('.contents').scrollTop( $('.contents')[0].scrollHeight );
}
///选择通话会员////////
function selmem(li){
  if(!$(li).hasClass('mselactive')){
    $('.mem'+$('.mselactive').attr("id")).css('display','none');
    $('.mselactive').removeClass('mselactive');
    $(li).addClass('mselactive');
    $('.memname').text($(li).text()+'聊天中');
    $('.memname').attr("data-mid",$(li).attr("id"));
    if($('.mem'+$(li).attr("id")).length>0){
      $('.mem'+$(li).attr("id")).css('display','block');
    } else{
      htmls='<div class="mem'+$(li).attr("id")+'"></div>';
      $('.contents').append(htmls);
    }
    $('#'+$(li).attr("id")+'>span').css('display','none');
    $('.contents').scrollTop( $('.contents')[0].scrollHeight );
  }
}
// 连接服务端
function connect() {
  // 创建websocket
  ws = new WebSocket("ws://120.77.207.78:9292");
  // 当socket连接打开时
  ws.onopen = function(){
    var login_data = '{"type":"klogin","kf_id":"'+kefuid+'"}';
    ws.send(login_data);
    console.log("连接成功");
  };
  // 当有消息时根据消息类型显示不同信息
  ws.onmessage = onmessage;
  ws.onclose = function() {
    console.log("连接关闭，定时重连");
    //  connect();
  };
  ws.onerror = function() {
    console.log("出现错误");
  };
}
/////onmessage//////////
function onmessage(e){
  console.log(e.data);
  var data = eval("("+e.data+")");
  switch(data['type']){
    case 'klogin':
    var clients_list=data['clients_list'];
    $.each(clients_list,function(index,item){
      htmls='<li class="limember" id="'+item['client_id']+'" data-cid="'+index+
      '" onclick="selmem(this)"><img src="'+item['client_img']+'">'+item['client_name']+'<span></span></li>'
      $('.listmem').append(htmls);
    })
    $('.listmem li:first-child').click(); //默认选择第一个用户
    break;
    case 'logout':
    if($('#'+data['client_id']).hasClass('mselactive')){
      $('.memname').text('选择在线客户');
      $('.memname').attr("data-mid","");
        $('.mem'+data['client_id']).css('display','none');
    }
    $('#'+data['client_id']).remove();
    break;
    case 'mlogin':
    htmls='<li class="limember" id="'+data['client_id']+'" data-cid="'+data['cid']+
    '" onclick="selmem(this)"><img src="'+data['client_img']+'">'+data['client_name']+'<span></span></li>'
    $('.listmem').append(htmls);
    break;
    case 'msay':
    htmls='<div class="chatfrom chatcontent">'+
    '<img src="'+$('#'+data['client_id']+'>img').attr('src')+'">'+
    '<label class="content">'+data['content']+'</label>'+
    '<span></span></div>';
    if($('.mem'+data['client_id']).length>0){
      $('.mem'+data['client_id']).append(htmls);
    } else{
      $('.contents').append('<div class="mem'+data['client_id']+'" style="display:none;"></div>');
        $('.mem'+data['client_id']).append(htmls);
    }
    if(!$('#'+data['client_id']).hasClass('mselactive')){
          $('#'+data['client_id']+'>span').css('display','block');
    }
    $('.content').css('max-width',$('.chatcontent').width()-110+'px');
    break;
    case 'mimg':
    htmls='<div class="chatfrom chatcontent">'+
    '<img src="'+$('#'+data['client_id']+'>img').attr('src')+'">'+
    '<img src="'+data['content']+'" class="chatfromimg">'+
    '<span></span></div>';
    if($('.mem'+data['client_id']).length>0){
      $('.mem'+data['client_id']).append(htmls);
    } else{
      $('.contents').append('<div class="mem'+data['client_id']+'" style="display:none;"></div>');
        $('.mem'+data['client_id']).append(htmls);
    }
    if(!$('#'+data['client_id']).hasClass('mselactive')){
          $('#'+data['client_id']+'>span').css('display','block');
    }
    // $('.content').css('max-width',$('.chatcontent').width()-110+'px');
    $('.chatfromimg').css('width',($('.chatcontent').width()-110)/2+'px');
    break;
  }
  $('.contents').scrollTop( $('.contents')[0].scrollHeight );
}
