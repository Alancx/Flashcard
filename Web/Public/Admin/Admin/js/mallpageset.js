$(document).ready(function(){
  $('.swiper-container-home').css('height',$('.swiper-container-home').width()/8*5+'px');
  ////////引导图设置///////
  $('.bootimgsave').click(function(){
    var boothref='HTTP://';
    if ($('#boot_img').val()=='') {
      art.dialog.alert('请上传图片!');
      return false;
    }
    if ($('#boot_href').val()!='') {
      boothref=$('#boot_href').val();
    }
    $('.bootimgurl').attr('data-iurl',$('#boot_img').val());
    $('.bootimgurl').attr('data-boothref',boothref);
    $('.bootimgurl>td>img').attr('src',imgurl_href+$('#boot_img').val());
    $('.bootimgurl>td').eq(1).html(boothref);
    $('.bootimgurl>td').eq(3).html($('#boot_img').val());
  });
  ///////轮播图上传保存按钮///////
  $('.lbimgsave').click(function(){
    var lbcount=$('.table-lb>tbody>tr').length+1;
    var lbhref='HTTP://';
    if ($('#lb_img').val()=='') {
      art.dialog.alert('请上传图片!');
      return false;
    }
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
      if (lbcount>3) {
        art.dialog.alert('轮播图最多设置三个!');
        return false;
      }
      var htmls='<tr class="lbimgurl" data-lbno="LB'+lbcount+'" data-iurl="'+$('#lb_img').val()+'"data-lbhref="'+lbhref+'">'+
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
  })
  ///////菜单分类信息上传保存按钮///////
  $('.class_imgsave').click(function(){
    if ($("#pro-class option:selected").text()=='请选择') {
      art.dialog.alert('选择分类!');
      return false;
    }
    if ($('#class_img').val()=='') {
      art.dialog.alert('上传分类图片!');
      return false;
    }
    if ($('.classinfo').hasClass('classactive')) {
      $('.classactive').attr('data-iurl',$('#class_img').val());
      $('.classactive').attr('data-cid',$("#pro-class option:selected").val());
      $('.classactive').attr('data-cname',$("#pro-class option:selected").text());
      $('.classactive').attr('data-pid',$("#pro-class option:selected").attr('data-pid'));
      $('.classactive>td>img').attr('src',imgurl_href+$('#class_img').val());
      $('.classactive>td').eq(1).html($("#pro-class option:selected").val());
      $('.classactive>td').eq(2).html($("#pro-class option:selected").text());
      $('.classactive>td').eq(4).html($('#class_img').val());
      var cid=($('.classactive').attr('id')).substring(2);
      $('.classactive').removeClass('classactive');
      $('.classtype').eq(cid-1).children('img').attr('src',imgurl_href+$('#class_img').val());
      $('.classtype').eq(cid-1).children('label').text($("#pro-class option:selected").text());
    } else {
      var cid=$('.classinfo').length+1;
      var html='<tr class="classinfo" id="CL'+cid+'" data-iurl="'+$('#class_img').val()+'" data-cid="'+$("#pro-class option:selected").val()+'" data-cname="'+$("#pro-class option:selected").text()+'" data-pid="'+$("#pro-class option:selected").attr('data-pid')+'">'+
      '<td>CL'+cid+'</td>'+
      '<td>'+$("#pro-class option:selected").val()+'</td>'+
      '<td>'+$("#pro-class option:selected").text()+'</td>'+
      '<td><img src="'+imgurl_href+$('#class_img').val()+'" alt="" style="width:150px;"></td>'+
      '<td>'+$('#class_img').val()+'</td>'+
      '<td style="width:50px;">'+
      '<button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button>'+
      '<button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td></tr>';
      $('.table-class').append(html);
      if (cid<=4) {
        $('.classtype').eq(cid-1).children('img').attr('src',imgurl_href+$('#class_img').val());
        $('.classtype').eq(cid-1).children('label').text($("#pro-class option:selected").text());
      } else {
        swiper_class.appendSlide('<div class="swiper-slide"><div class="classtype"><img src="'+imgurl_href+$('#class_img').val()+'" alt=""><label>'+$("#pro-class option:selected").text()+'</label></div></div>');
      }
    }
    $('#class_img').val('');

  })
  ///////活动分类信息上传保存按钮///////
  $('.hclass_imgsave').click(function(){
    if ($('#hclass_img').val()=='') {
      art.dialog.alert('上传分类图片!');
      return false;
    }
    var banneherf='HTTP://';
    if ($('#hbbanner_href').val()!='') {
      banneherf=$('#hbbanner_href').val();
    }
    $('.hclassinfo').attr('data-iurl',$('#hclass_img').val());
    $('.hclassinfo').attr('data-href',banneherf);
    $('.hclassinfo>td>img').attr('src',imgurl_href+$('#hclass_img').val());
    $('.hclassinfo>td').eq(1).html(banneherf);
    $('.hclassinfo>td').eq(3).html($('#hclass_img').val());
    $('.home_activity>a>img').attr('src',imgurl_href+$('#hclass_img').val());
    $('#hclass_img').val('');
  })

  ///////商品区域一信息上传保存按钮///////
  $('.proone_imgsave').click(function(){
    if ($("#pro-one option:selected").text()=='请选择') {
      art.dialog.alert('选择商品!');
      return false;
    }
    if ($('#proone_img').val()=='') {
      art.dialog.alert('上传商品图片!');
      return false;
    }
    if ($('.oneproinfo').length>=6) {
      art.dialog.alert('最多设置六个!');
      return false;
    }

    if ($('.oneproinfo').hasClass('oneproactive')) {
      $('.oneproactive').attr('data-iurl',$('#proone_img').val());
      $('.oneproactive').attr('data-pid',$("#pro-one option:selected").val());
      $('.oneproactive').attr('data-pname',$("#pro-one option:selected").text());
      $('.oneproactive>td>img').attr('src',imgurl_href+$('#proone_img').val());
      $('.oneproactive>td').eq(1).html($("#pro-one option:selected").val());
      $('.oneproactive>td').eq(2).html($("#pro-one option:selected").text());
      $('.oneproactive>td').eq(4).html($('#proone_img').val());
      var cid=($('.oneproactive').attr('id')).substring(6);
      $('.proinfo_1').eq(cid-1).children('a').children('img').attr('src',imgurl_href+$('#proone_img').val());
      $('.oneproactive').removeClass('oneproactive');
      $('#proone_img').val('');
    } else {
      var proid=$('.oneproinfo').length+1;
      var html='<tr class="oneproinfo" id="PRO-1-'+proid+'" data-iurl="'+$('#proone_img').val()+'" data-pid="'+$("#pro-one option:selected").val()+'" data-pname="'+$("#pro-one option:selected").text()+'">'+
        '<td>PRO'+proid+'</td>'+
        '<td>'+$("#pro-one option:selected").val()+'</td>'+
        '<td>'+$("#pro-one option:selected").text()+'</td>'+
        '<td><img src="'+imgurl_href+$('#proone_img').val()+'" alt="" style="width:150px;"></td>'+
        '<td>'+$('#proone_img').val()+'</td>'+
        '<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button><button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td></tr>';
        $('.table-pro-one').append(html);
        if (proid>4) {
          var html='<div class="proinfo_1"><a><img src="'+imgurl_href+$('#proone_img').val()+'" alt=""></a></div>';
          $('.product_1').append(html);
        } else {
          $('.proinfo_1').eq(proid-1).children('a').children('img').attr('src',imgurl_href+$('#proone_img').val());
        }
        $('#proone_img').val('');
    }
  })
  ///////商品区域二信息上传保存按钮///////
  $('.protwo_imgsave').click(function(){
    if ($("#pro-two option:selected").text()=='请选择') {
      art.dialog.alert('选择商品!');
      return false;
    }
    if ($('#protwo_img').val()=='') {
      art.dialog.alert('上传商品图片!');
      return false;
    }
    if ($('.twoproinfo').hasClass('twoproactive')) {
      $('.twoproactive').attr('data-iurl',$('#protwo_img').val());
      $('.twoproactive').attr('data-pid',$("#pro-two option:selected").val());
      $('.twoproactive').attr('data-pname',$("#pro-two option:selected").text());
      $('.twoproactive>td>img').attr('src',imgurl_href+$('#protwo_img').val());
      $('.twoproactive>td').eq(1).html($("#pro-two option:selected").val());
      $('.twoproactive>td').eq(2).html($("#pro-two option:selected").text());
      $('.twoproactive>td').eq(4).html($('#protwo_img').val());
      var cid=($('.twoproactive').attr('id')).substring(6);
      $('.proinfo_2').eq(cid-1).children('a').children('img').attr('src',imgurl_href+$('#protwo_img').val());
      $('.twoproactive').removeClass('twoproactive');
      $('#protwo_img').val('');
    } else {
      var proid=$('.twoproinfo').length+1;
      var html='<tr class="twoproinfo" id="PRO-2-'+proid+'" data-iurl="'+$('#protwo_img').val()+'" data-pid="'+$("#pro-two option:selected").val()+'" data-pname="'+$("#pro-two option:selected").text()+'">'+
        '<td>PRO'+proid+'</td>'+
        '<td>'+$("#pro-two option:selected").val()+'</td>'+
        '<td>'+$("#pro-two option:selected").text()+'</td>'+
        '<td><img src="'+imgurl_href+$('#protwo_img').val()+'" alt="" style="width:150px;"></td>'+
        '<td>'+$('#protwo_img').val()+'</td>'+
        '<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button><button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td></tr>';
        $('.table-pro-two').append(html);
        if (proid>4) {
          var html='<div class="proinfo_2"><a><img src="'+imgurl_href+$('#protwo_img').val()+'" alt=""></a></div>';
          $('.product_2').append(html);
        } else {
          $('.proinfo_2').eq(proid-1).children('a').children('img').attr('src',imgurl_href+$('#protwo_img').val());
        }
        $('#protwo_img').val('');
    }
  })



  ///////保存首页////////
  $('#savehome').click(function(){
    homesave();
  })

})
////////保存首页事件//////
function homesave(){
  if ($('.bootimgurl').attr('data-iurl')=='') {
    art.dialog.alert('设置引导图!');
    return false;
  }
  if (($('.lbimgurl').length<1)||($('.lbimgurl').length>3)) {
    art.dialog.alert('按规定设置轮播图');
    return false;
  }
  if (($('.classinfo').length<4)) {
    art.dialog.alert('按规定设置分类');
    return false;
  }
  if (($('.hclassinfo').attr('data-iurl')=='')) {
    art.dialog.alert('设置活动图信息');
    return false;
  }
  if (($('.oneproinfo').length<4)) {
    art.dialog.alert('按规定设置商品区域一');
    return false;
  }
  if (($('.twoproinfo').length<4)) {
    art.dialog.alert('按规定设置商品区域二');
    return false;
  }

  var homedata= {};
  homedata['bootinfo']= {};/////首页阴道图////////
  homedata['lbdata']= {};/////轮播图内容////////
  homedata['classdata']= {};/////分类内容////////
  homedata['hclassdata']= {};/////活动分类内容////////
  homedata['oneprodata']= {};/////商品区域一内容////////
  homedata['twoprodata']= {};/////商品区域二内容////////
  homedata['bootinfo']['boothref']=$('.bootimgurl').attr('data-boothref');
  homedata['bootinfo']['imgurl']=$('.bootimgurl').attr('data-iurl');
  $('.lbimgurl').each(function(index,item){
    homedata['lbdata'][index]={};
    homedata['lbdata'][index]['lbno']=$(item).attr('data-lbno');
    homedata['lbdata'][index]['imgurl']=$(item).attr('data-iurl');
    homedata['lbdata'][index]['lbhref']=$(item).attr('data-lbhref');
  })
  $('.classinfo').each(function(index,item){
    homedata['classdata'][index]={};
    homedata['classdata'][index]['cid']=$(item).attr('data-cid');
    homedata['classdata'][index]['pid']=$(item).attr('data-pid');
    homedata['classdata'][index]['cname']=$(item).attr('data-cname');
    homedata['classdata'][index]['imgurl']=$(item).attr('data-iurl');
  })
  homedata['hclassdata'][0]={};
  homedata['hclassdata'][0]['imghref']=$('.hclassinfo').attr('data-href');
  homedata['hclassdata'][0]['imgurl']=$('.hclassinfo').attr('data-iurl');
  $('.oneproinfo').each(function(index,item){
    homedata['oneprodata'][index]={};
    homedata['oneprodata'][index]['pid']=$(item).attr('data-pid');
    homedata['oneprodata'][index]['pname']=$(item).attr('data-pname');
    homedata['oneprodata'][index]['imgurl']=$(item).attr('data-iurl');
  })
  $('.twoproinfo').each(function(index,item){
    homedata['twoprodata'][index]={};
    homedata['twoprodata'][index]['pid']=$(item).attr('data-pid');
    homedata['twoprodata'][index]['pname']=$(item).attr('data-pname');
    homedata['twoprodata'][index]['imgurl']=$(item).attr('data-iurl');
  })




  var homedataj=JSON.stringify(homedata);
  $.ajax({
    type:"post",
    url:savehomeurl,
    data:"homedata="+homedataj,
    dateType:"json",
    complete: function(e){
      ////
    },
    success: function(msg){
      if (msg.status == 'true') {
        art.dialog.alert('保存成功!');
      } else{
        art.dialog.alert('保存失败!');
      }
    },
  })
}

/////删除table当前行数据/////////
function delmsghome(btn){
  var trs=$(btn).parent().parent();
  $(btn).parent().parent().remove();
  if (trs.hasClass('lbimgurl')) {
    swiper_home.removeAllSlides();
    if ($('.lbimgurl').length==0) {
      swiper_home.appendSlide('<div class="swiper-slide"><img src="http://placehold.it/800x500" class="imgslide"></div>');
    } else {
      $('.lbimgurl').each(function(index,item){
        $(item).attr('data-lbno','LB'+(index+1));
        $(item).children('td').eq(0).html('LB'+(index+1));
        swiper_home.appendSlide('<div class="swiper-slide"><img src="'+imgurl_href+$(item).attr('data-iurl')+'" class="imgslide"></div>');
      });
    }
    swiper_home.startAutoplay();
  } else if (trs.hasClass('classinfo')) {
    var cid=trs.attr('id').substring(2);
      swiper_class.removeSlide(cid-1);
      $('.classinfo').each(function(index,item){
        $(item).attr('id','CL'+(index+1));
        $(item).children('td').eq(0).html('CL'+(index+1));
      });
      if ($('.classinfo').length<4) {
            swiper_class.appendSlide('<div class="swiper-slide"><div class="classtype"><img src="http://placehold.it/100x100" alt=""><label>分类名称</label></div></div>');
      }
  } else if (trs.hasClass('oneproinfo')) {
    var cid=trs.attr('id').substring(6);
    $('.proinfo_1').eq(cid-1).remove();
    if ($('.oneproinfo').length<4) {
      var html='<div class="proinfo_1"><a><img src="http://placehold.it/800x300" alt=""></a></div>';
      $('.product_1').append(html);
    }
    $('.oneproinfo').each(function(index,item){
      $(item).attr('id','PRO-1-'+(index+1));
      $(item).children('td').eq(0).html('PRO'+(index+1));
    });
  } else if (trs.hasClass('twoproinfo')) {
    var cid=trs.attr('id').substring(6);
    $('.proinfo_2').eq(cid-1).remove();
    if ($('.twoproinfo').length<4) {
      var html='<div class="proinfo_2"><a><img src="http://placehold.it/400x500" alt=""></a></div>';
      $('.product_2').append(html);
    }
    $('.twoproinfo').each(function(index,item){
      $(item).attr('id','PRO-2-'+(index+1));
      $(item).children('td').eq(0).html('PRO'+(index+1));
    });
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
  } else if (trs.hasClass('classinfo')) {
    $('.classactive').removeClass('classactive');
    trs.addClass('classactive');
    $('#class_img').val(trs.attr('data-iurl'));
  } else if (trs.hasClass('oneproinfo')) {
    $('.oneproactive').removeClass('oneproactive');
    trs.addClass('oneproactive');
    $('#proone_img').val(trs.attr('data-iurl'));
  } else if (trs.hasClass('twoproinfo')) {
    $('.twoproactive').removeClass('twoproactive');
    trs.addClass('twoproactive');
    $('#protwo_img').val(trs.attr('data-iurl'));
  }
}
