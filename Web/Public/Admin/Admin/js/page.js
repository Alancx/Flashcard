$(document).ready(function(){
  ///////高度设置///////
  $('.swiper-container-home').css('height',$('.swiper-container-home').width()/8*5+'px');
  $('.new_pro_3>img').css('height',$('.new_pro_3>img').width()/8*3+'px');
  //////////预览查看///////
  $('#lookhome').click(function(){
    if ($(this).text()=='查看预览') {
      $(this).text('取消预览');
      $('.overburden').css('display','none');
    } else {
      $(this).text('查看预览');
      $('.overburden').css('display','block');
    }
  })
  ///////轮播图上传保存按钮///////
  $('.lbimgsave').click(function(){
    var lbcount=$('.table-lb>tbody>tr').length+1;
    var lbhref='HTTP://';
    if (lbcount>2) {
      art.dialog.alert('最多设置2张!');
      return false;
    }
    if ($('#lb_img').val()=='') {
      art.dialog.alert('请上传图片!');
      return false;
    }
    if ($('#lb_href').val()!='') {
      lbhref=$('#lb_href').val();
    }
    var lbdata={
      'lbiurl':$('#lb_img').val(),
      'lbhref':lbhref,
      'stype':'0',
    };
    if ($('.lbimgurl').hasClass('lbactive')) {
      lbdata['type']='update';
      lbdata['sort']=$('.lbactive').attr('data-lbno');
      lbdata['hid']=$('.lbactive').attr('data-hid');
    } else {
      lbdata['type']='add';
      lbdata['hid']='add';
      lbdata['sort']='LB'+lbcount;
    }
    homeinfosave('0',lbdata);
  })
  ///////热卖商品上传保存按钮///////
  $('.prohot_imgsave').click(function(){
    var hotcount=$('.table-pro-hot>tbody>tr').length+1;
    var hproid=$("#pro-hot option:selected").val();
    var hproimg=$("#pro-hot option:selected").attr('data-img');
    if ($("#pro-hot option:selected").text()=='请选择') {
      return false;
    }
    var hotdata={
      'hpid':hproid,
      'hcount':hotcount,
      'stype':'1',
    };
    if ($('.hotproinfo').hasClass('hotactive')) {
      hotdata['type']='update';
      hotdata['hid']=$('.hotactive').attr('data-hid');
    } else {
      if (hotcount>2) {
        art.dialog.alert('热销商品最多选择2个!');
        return false;
      } else {
        hotdata['type']='add';
        hotdata['hid']='add';
      }
    }
    homeinfosave('1',hotdata);
  })
  ///////新品商品上传保存按钮///////
  $('.pronew_imgsave').click(function(){
    var newcount=$('.table-pro-new>tbody>tr').length+1;
    var hproid=$("#pro-new option:selected").val();
    var hproimg=$("#new_img").val();

    if ($("#pro-new option:selected").text()=='请选择') {
      art.dialog.alert('请选择商品!');
      return false;
    }
    if ($.trim(hproimg)=='') {
      art.dialog.alert('请上传图片!');
      return false;
    }
    var newdata={
      'hpid':hproid,
      'hcount':newcount,
      'stype':'2',
      'homeimg':hproimg,
    };
    if ($('.newproinfo').hasClass('newactive')) {
      newdata['type']='update';
      newdata['hid']=$('.newactive').attr('data-hid');
    } else {
        newdata['type']='add';
        newdata['hid']='add';
    }
    homeinfosave('2',newdata);
  })


})

/////删除table当前行数据/////////
function delmsghome(btn){
  var trs=$(btn).parent().parent();
  var hid=trs.attr('data-hid');
  if (trs.hasClass('lbimgurl')) {  ///////删除轮播图信息//////
    var lbdata={
      'hid':hid,
      'type':'0',
    };
    $.ajax({
      type:"post",
      url:delhomeurl,
      data:lbdata,
      dateType:"json",
      complete: function(e){
        ////
      },
      success: function(msg){
        if (msg.status == 'true') {
          trs.remove();
          swiper_home.removeAllSlides();
          if ($('.lbimgurl').length==0) {
            swiper_home.appendSlide('<div class="swiper-slide"><img src="http://placehold.it/800x500" class="imgslide"></div>');
          } else {
            $('.lbimgurl').each(function(index,item){
              $(item).attr('id','LB'+(index+1));
              $(item).attr('data-lbno','LB'+(index+1));
              $(item).children('td').eq(0).html('LB'+(index+1));
              swiper_home.appendSlide('<div class="swiper-slide"><img src="'+imgurl_href+$(item).attr('data-iurl')+'" class="imgslide"></div>');
            });
          }
          swiper_home.startAutoplay();
          art.dialog.alert('删除成功!');
        } else{
          art.dialog.alert('删除失败!');
        }
      },
    })
  } else if (trs.hasClass('newproinfo')) {
    var lbdata={
      'hid':hid,
      'type':'1',
    };
    $.ajax({
      type:"post",
      url:delhomeurl,
      data:lbdata,
      dateType:"json",
      complete: function(e){
        ////
      },
      success: function(msg){
        if (msg.status == 'true') {
          var scoreid = trs.attr('data-sore');
          $('.new_pro_3').eq(scoreid).remove();
          trs.remove();
          $('.table-pro-new>tbody>tr').each(function(index,item){
            $(item).attr('id','NEW'+(index+1));
            $(item).attr('data-sore',index);
            $(item).children('td').eq(0).html('NEW'+(index+1));
          });
          art.dialog.alert('删除成功!');
        } else{
          art.dialog.alert('删除失败!');
        }
      },
    })
  }
}
/////修改table当前行数据/////////
function updatemsghome(btn){
  var trs=$(btn).parent().parent();
  if (trs.hasClass('lbimgurl')) {
    $('.lbactive').removeClass('lbactive');
    trs.addClass('lbactive');
    $('#lb_href').val(trs.attr('data-lbhref'));
    $('#lb_img').val(trs.attr('data-iurl'));
  } else if (trs.hasClass('hotproinfo')) {
    $('.hotactive').removeClass('hotactive');
    trs.addClass('hotactive');
  } else if (trs.hasClass('newproinfo')) {
    $('.newactive').removeClass('newactive');
    trs.addClass('newactive');
    $('#new_img').val(trs.attr('data-iurl'));
  }
}
/////////保存首页信息///////////
function homeinfosave(type,info){
  var htype=type;
  var hinfo=info;
  $.ajax({
    type:"post",
    url:savehomeurl,
    data:hinfo,
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
      if (msg.status == 'true') {
        if (htype=='0') {
          var lbcount=$('.table-lb>tbody>tr').length+1;
          var lbhref='HTTP://';
          if ($('#lb_href').val()!='') {
            lbhref=$('#lb_href').val();
          }
          if ($('.lbimgurl').hasClass('lbactive')) {
            $('.lbactive').attr('data-iurl',$('#lb_img').val());
            $('.lbactive').attr('data-lbhref',lbhref);
            $('.lbactive>td>img').attr('src',imgurl_href+$('#lb_img').val());
            $('.lbactive>td').eq(1).html(lbhref);
            $('.lbactive>td').eq(3).html($('#lb_img').val());
            $('.lbactive').removeClass('lbactive');
          } else{
            var htmls='<tr id ="LB'+lbcount+'" class="lbimgurl" data-lbno="LB'+lbcount+'" data-iurl="'+$('#lb_img').val()+'"data-lbhref="'+lbhref+'" data-hid="'+msg.info+'">'+
            '<td>LB'+lbcount+'</td>'+
            '<td>'+lbhref+'</td>'+
            '<td><img src="'+imgurl_href+$('#lb_img').val()+'" alt="" style="width:150px;"/></td>'+
            '<td>'+$('#lb_img').val()+'</td>'+
            '<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button>'+
            '<button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td>'+
            '</tr>';
            $('.table-lb').append(htmls);
          }
          $('#lb_img').val('');
          $('#lb_href').val('');
          swiper_home.removeAllSlides();
          $('.lbimgurl').each(function(index,item){
            swiper_home.appendSlide('<div class="swiper-slide"><img src="'+imgurl_href+$(item).attr('data-iurl')+'" class="imgslide"></div>');
          })
          swiper_home.startAutoplay();
        } else if (htype=='1') {
          var hotcount=$('.table-pro-hot>tbody>tr').length+1;
          var hproid=$("#pro-hot option:selected").val();
          var hproimg=$("#pro-hot option:selected").attr('data-img');
          var hproname=$("#pro-hot option:selected").text();
          var hproprice=parseFloat($("#pro-hot option:selected").attr('data-price')).toFixed(2);
          if ($('.hotproinfo').hasClass('hotactive')) {
            var hotid=$('.hotactive').attr('id').substr(3,1);
            $('.hotactive').attr('data-iurl',hproimg);
            $('.hotactive').attr('data-pid',hproid);
            $('.hotactive').attr('data-pname',hproname);
            $('.hotactive').attr('data-price',hproprice);
            $('.hotactive>td').eq(1).html(hproid);
            $('.hotactive>td').eq(2).html(hproname);
            $('.hotactive>td').eq(3).html(hproprice);
            $('.hotactive>td').eq(5).html(hproimg);
            $('.hotactive>td>img').attr('src',imgurl_href+hproimg);
            $('.hotactive').removeClass('hotactive');
          } else {
            var htmls='<tr id="HOT'+hotcount+'" class="hotproinfo" data-iurl="'+hproimg+'" data-pid="'+hproid+'" data-pname="'+hproname+'" data-price="'+hproprice+'" data-hid="'+msg.info+'">'+
            '<td>HOT'+hotcount+'</td>'+
            '<td>'+hproid+'</td>'+
            '<td>'+hproname+'</td>'+
            '<td>'+hproprice+'</td>'+
            '<td><img src="'+imgurl_href+hproimg+'" alt="" style="width:150px;"/></td>'+
            '<td>'+hproimg+'</td>'+
            '<td style="width:50px;"><button type="button" class="btn btn-warning btn-xs" onclick="updatemsghome(this)">修改</button></td>'+
            '</tr>';
            $('.table-pro-hot').append(htmls);
            var hotid=hotcount;
          }
          if (hotid=='1') {
            var hothtml='<img src="'+imgurl_href+hproimg+'" alt="">'+
            '<label>'+hproname+'<span>￥'+hproprice+'</span></label>';
            $('.hot-pro-1').html(hothtml);
          } else {
            var hothtml='<div><label>'+hproname+'</label><label>￥'+hproprice+'</label></div><img src="'+imgurl_href+hproimg+'" alt="">';
            $('.hot-pro-2').html(hothtml);
          }
        }else if (htype=='2') {
          var newcount=$('.table-pro-new>tbody>tr').length+1;
          var hproid=$("#pro-new option:selected").val();
          var hproimg=$("#new_img").val();
          var hproname=$("#pro-new option:selected").text();
          var hproprice=parseFloat($("#pro-new option:selected").attr('data-price')).toFixed(2);
          if ($('.newproinfo').hasClass('newactive')) {
            var newid=$('.newactive').attr('data-sore');
            $('.newactive').attr('data-iurl',hproimg);
            $('.newactive').attr('data-pid',hproid);
            $('.newactive').attr('data-pname',hproname);
            $('.newactive').attr('data-price',hproprice);
            $('.newactive>td').eq(1).html(hproid);
            $('.newactive>td').eq(2).html(hproname);
            $('.newactive>td').eq(3).html(hproprice);
            $('.newactive>td').eq(5).html(hproimg);
            $('.newactive>td>img').attr('src',imgurl_href+hproimg);
            $('.newactive').removeClass('newactive');
            $('.new_pro_3').eq(newid).children('img').attr('src',imgurl_href+hproimg);
          } else {
            var newid=newcount;
            var htmls='<tr id="NEW'+newcount+'" class="newproinfo" data-iurl="'+hproimg+'" data-pid="'+hproid+'" data-pname="'+hproname+'" data-price="'+hproprice+'" data-hid="'+msg.info+'">'+
            '<td>NEW'+newcount+'</td>'+
            '<td>'+hproid+'</td>'+
            '<td>'+hproname+'</td>'+
            '<td>'+hproprice+'</td>'+
            '<td><img src="'+imgurl_href+hproimg+'" alt="" style="width:150px;"/></td>'+
            '<td>'+hproimg+'</td>'+
            '<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">'+
            '删除</button><button type="button" class="btn btn-warning btn-xs btn-update"'+ 'onclick="updatemsghome(this)">修改</button></td>'+
            '</tr>';
            $('.table-pro-new').append(htmls);
            var newhtml='<div class="new_pro_3">'+
  				  	'<img src="'+imgurl_href+hproimg+'" alt=""></div>';
            $('.pro-line-3').append(newhtml);
          }

          $("#new_img").val('');
          $('.new_pro_3>img').css('height',$('.new_pro_3>img').width()/8*3+'px');
        }
        art.dialog.alert('保存成功!');
      } else{
        art.dialog.alert('保存失败!');
      }
    },
  })
}
