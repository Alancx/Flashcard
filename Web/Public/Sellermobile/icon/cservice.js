$(function(){
  oFReader = new FileReader(),
  rFilter = /^(?:image\/jpeg|image\/png)$/i;

  oFReader.onload = function (oFREvent) {
    savesimg();
  };

  $(window).resize(function(){
      $('.chatlist').css('height',$(window).height()-$('.chatedit').outerHeight(true)+'px');
      $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
  })
  $('.chatedit').resize(function(){
    $('.chatlist').css('height',$(window).height()-$('.chatedit').outerHeight(true)+'px');
    $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
  })
  $(window).resize();
  $('.content').css('max-width',$('.chatcontent').width()-110+'px');
  $('.chattoimg').css('width',($('.chatcontent').width()-110)/2+'px');
  $('.chatfromimg').css('width',($('.chatcontent').width()-110)/2+'px');
  $('.btnsend').click(function(){
    if($('.editchat').text()!=''){
      sendmesg();
    } else {
      tips('notice', '填写发送内容!', 1500, 'weui_icon_notice');
    }
  })
  connect();
  // getmesg('1');
  // $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
})
function getmesg(page){
  $.ajax({
    type:"post",
    url:getmsg_url,
    data:"&kfid="+kefuid+"&mid="+memid+"&page="+page,
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
        if (msg.status == 'true') {
          var chatsdata=msg.datainfo;
            for (var key in chatsdata){
              if(chatsdata[key].type=='from'){
                var htmls='<div class="chatfrom chatcontent">'+
                  '<img src="'+kefuimg+'">'+
                  '<label class="content">'+chatsdata[key].content+'</label>'+
                  '<span></span></div>';
                $('.chatlist').prepend(htmls);
                $('.content').css('max-width',$('.chatcontent').width()-110+'px');
              } else {
                var htmls='<div class="chatto chatcontent">'+
                  '<img src="'+memimg+'">'+
                  '<label class="content">'+chatsdata[key].content+'</label>'+
                  '<span></span></div>';
                $('.chatlist').prepend(htmls);
                $('.content').css('max-width',$('.chatcontent').width()-110+'px');
              }
            }
            if(msg.allend=='true'){
              $('.chatlist').attr('data-page','-1');
            } else{
              var cpage=parseInt($('.chatlist').attr('data-page'))+1;
              $('.chatlist').attr('data-page',cpage);
            }
        } else{
          // console.log('读取失败!');
        }
    },
  })
}
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
    url: usersimg_url, //用于文件上传的服务器端请求地址
    secureuri: false, //是否需要安全协议，一般设置为false
    fileElementId: 'simg', //文件上传域的ID
    dataType: 'json', //返回值类型 一般设置为json
    success: function (data, status)  //服务器成功响应处理函数
    {
      if (data.status=='true'){
        // console.log(data.datainfo);
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
  var msay_data = '{"type":"mimg","client_id":"'+memid+'","content":"'+imgurl+'","kf_id":"'+kefuid+'"}';
  ws.send(msay_data);
  var htmls='<div class="chatto chatcontent">'+
    '<img src="'+memimg+'">'+
    '<img src="'+imgurl+'" class="chattoimg">'+
    '<span></span></div>';
  $('.chatlist').append(htmls);
  $('.chattoimg').css('width',($('.chatcontent').width()-110)/2+'px');
  $.ajax({
    type:"post",
    url:sendmsg_url,
    data:"sendconter="+imgurl+"&kfid="+kefuid+"&mid="+memid+"&type=1",
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
  $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
}

function sendmesg() {
  var msay_data = '{"type":"msay","client_id":"'+memid+'","content":"'+$('.editchat').text()+'","kf_id":"'+kefuid+'"}';
    ws.send(msay_data);
    var htmls='<div class="chatto chatcontent">'+
      '<img src="'+memimg+'">'+
      '<label class="content">'+$('.editchat').text()+'</label>'+
      '<span></span></div>';
    $('.chatlist').append(htmls);
    $('.content').css('max-width',$('.chatcontent').width()-110+'px');
  $.ajax({
    type:"post",
    url:sendmsg_url,
    data:"sendconter="+$('.editchat').text()+"&kfid="+kefuid+"&mid="+memid,
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
  $('.editchat').text('');
  $('.editchat').focus();
  $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
}
// 连接服务端
function connect() {
   // 创建websocket
   ws = new WebSocket("ws://120.77.207.78:9292");
   // 当socket连接打开时
   ws.onopen = function(){
     var login_data = '{"type":"mlogin","client_id":"'+memid+'","client_name":"'+memname+'","client_img":"'+memimg+'","kf_id":"'+kefuid+'"}';
      ws.send(login_data);
    console.log("连接成功");
   };
   // 当有消息时根据消息类型显示不同信息
   ws.onmessage = onmessage;
   ws.onclose = function() {
    console.log("连接关闭");
    //  connect();
   };
   ws.onerror = function(err) {
    console.log("出现错误");
   };
}
/////onmessage//////////
function onmessage(e){
  console.log(e.data);
  var data = eval("("+e.data+")");
  switch(data['type']){
    case 'mlogin':
    if(data['online']=='true'){
      var htmls='<div class="time"><span>'+kefuname+'在线</span></div>';
    } else{
      var htmls='<div class="time"><span>'+kefuname+'下线</span></div>';
    }
    $('.chatlist').append(htmls);
    break;
    case 'klogin':
    var htmls='<div class="time"><span>'+kefuname+'在线</span></div>';
    $('.chatlist').append(htmls);
    break;
    case 'logout':
    var htmls='<div class="time"><span>'+kefuname+'下线</span></div>';
    $('.chatlist').append(htmls);
    break;
    case 'ksay':
    var htmls='<div class="chatfrom chatcontent">'+
      '<img src="'+kefuimg+'">'+
      '<label class="content">'+data['content']+'</label>'+
      '<span></span></div>';
    $('.chatlist').append(htmls);
    $('.content').css('max-width',$('.chatcontent').width()-110+'px');
    break;
    case 'kimg':
    var htmls='<div class="chatfrom chatcontent">'+
      '<img src="'+kefuimg+'">'+
      '<img src="'+data['content']+'" class="chatfromimg">'+
      '<span></span></div>';
    $('.chatlist').append(htmls);
    $('.chatfromimg').css('width',($('.chatcontent').width()-110)/2+'px');
    break;
  }
  $('.chatlist').scrollTop( $('.chatlist')[0].scrollHeight );
}
