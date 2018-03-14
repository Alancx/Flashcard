var swiper,swiper_class;
var selproinfo='';
$(document).ready(function () {
  $('.updatelb').css('display','none');
  $('.deletelb').css('display','none');

   swiper = new Swiper('.swiper-container-home', {
    pagination: '.swiper-pagination',
    onSlideChangeEnd: function(swiper){
      if (swiper.activeIndex==0) {
        $('.updatelb').css('display','none');
        $('.deletelb').css('display','none');
      } else {
        $('.updatelb').css('display','inline-block');
        $('.deletelb').css('display','inline-block');
      }
    }
  });

  swiper_class = new Swiper('.swiper-container-class', {
    slidesPerView: 4,
    spaceBetween: 0,
    autoHeight: true, //enable auto height
    freeMode: true,
  });
  if (swiper.slides.length>=3) {
    $('.addlb').css('display','none');
  } else {
    $('.addlb').css('display','inline-block');
  }
  //////////固定轮播图高度///////////
  $('.swiper-container-home').css('height',$('.swiper-container-home').width()/8*5+'px');
   $(".pro-line-1-left>img").height($(".pro-line-1-left>img").width());
   $(".pro-line-1-right>img").height($(".pro-line-1-right>img").width());
   $(".pro-line-2 img").height($(".pro-line-2 img").width());
   $(".pro-line-3 img").height($(".pro-line-3 img").width());

   $('.addlb').click(function(){
     $('.home-contents').css('display','none');
     $('.lbinfosetconver').css('display','block');
     $('.lbinfosetconver').attr('data-type','add');
     $('.setlbinfo>img').attr("src","/Public/Sellermobile/Icon/add_img.png");
     $('#lbimage').val('');
     $('#lbinput').val('');
     $('.setlbinfo').css('height',$('.setlbinfo').width()/8*5+'px');
   });
   $('.updatelb').click(function(){
     var swid=swiper.activeIndex;
     var selslide=$(swiper.slides[swid]);
     $('.home-contents').css('display','none');
     $('.lbinfosetconver').css('display','block');
     $('.lbinfosetconver').attr('data-type',selslide.attr('data-hid'));
     $('.setlbinfo>img').attr("src",selslide.children('img').attr('src'));
     $('#lbimage').val(selslide.children('img').attr('src'));
     $('#lbinput').val(selslide.attr('data-href'));
     $('.setlbinfo').css('height',$('.setlbinfo').width()/8*5+'px');
   });
   $('.deletelb').click(function(){
     var swid=swiper.activeIndex;
     var selslide=$(swiper.slides[swid]);
     var hid=selslide.attr('data-hid');
       var lbdata={
         'hid':hid,
         'type':'0',
       };
       tips('waiting','删除数据中···');
       $.ajax({
         type:"post",
         url:lbimgdeleteurl,
         data:lbdata,
         dateType:"json",
         complete: function(e){
           hidetips('waiting');
         },
         success: function(msg){
             if (msg.status == 'true') {
               swiper.removeSlide(swid);
               if (swiper.slides.length>=3) {
                 $('.addlb').css('display','none');
               } else {
                 $('.addlb').css('display','inline-block');
               }
               if (swiper.activeIndex==0) {
                 $('.updatelb').css('display','none');
                 $('.deletelb').css('display','none');
               } else {
                 $('.updatelb').css('display','inline-block');
                 $('.deletelb').css('display','inline-block');
               }
               tips('notice', '删除成功!', 1500, 'weui_icon_toast');
             } else{
               tips('notice', '删除失败!', 1500, 'weui_icon_notice');
             }
         },
       })
   });
   $('.quxiaolb').click(function(){
     $('.home-contents').css('display','block');
     $('.lbinfosetconver').css('display','none');
     $('.lbinfosetconver').attr('data-type','');
   });
   $('.baocunlb').click(function(){
     var lbcount=swiper.slides.length;
     var lbhref='HTTP://';
     var type=$('.lbinfosetconver').attr('data-type');
     if ($('#lbimage').val()=='') {
       tips('notice', '请上传图片!', 1500, 'weui_icon_notice');
       return false;
     }
     if ($('#lbinput').val()!='') {
       lbhref=$('#lbinput').val();
     }
     var lbdata={
       'lbiurl':$('#lbimage').val(),
       'lbhref':lbhref,
       'stype':'0',
     };
     if (type=='add') {
       if (lbcount>=3) {
         tips('notice', '轮播图最多设置两个!', 1500, 'weui_icon_notice');
         return false;
       }
       lbdata['type']='add';
       lbdata['hid']='add';
       lbdata['sort']='LB'+lbcount;
     } else {
       lbdata['type']='update';
       lbdata['sort']='LB'+swiper.activeIndex;
       lbdata['hid']=type;
     }
     homeinfosave('0',lbdata);
   });
   /////////编辑商品信息/////////////////
   $('.updatepro').click(function(){
     selproinfo=$(this);
     if (selproinfo.hasClass('updatehot')) {
       $('.hotproinfo').css('display','block');
       $('.newproinfo').css('display','none');
     } else {
       $('.hotproinfo').css('display','none');
       $('.newproinfo').css('display','block');
     }
     $('body').css('overflow','hidden');
     $('.prohotconver').show(100);
   });
   $('.proqusel').click(function(){
     selproinfo='';
     $('body').css('overflow','auto');
     $('.prohotconver').hide(100);
   });
   $('.pro_info').click(function(){
     if (selproinfo.hasClass('updatehot')) {
       var hotcount=selproinfo.attr('data-s');
       var hproid=$(this).attr('data-pid');
       var hproimg=$(this).attr('data-imgurl');
       var type=selproinfo.attr('data-type');
       $('.hasselpro').removeClass('hasselpro');
       $(this).addClass('hasselpro');
       if (type=='add') {
         var hotdata={
           'hpid':hproid,
           'hcount':hotcount,
           'stype':'1',
           'type':'add',
           'hid':'add',
         };
       } else {
         var hotdata={
           'hpid':hproid,
           'hcount':hotcount,
           'stype':'1',
           'type':'update',
           'hid':type,
         };
       }
       homeinfosave('1',hotdata);
     } else {
       var hotcount=selproinfo.attr('data-s');
       var hproid=$(this).attr('data-pid');
       var hproimg=$(this).attr('data-imgurl');
       var type=selproinfo.attr('data-type');
       $('.hasselpro').removeClass('hasselpro');
       $(this).addClass('hasselpro');
       if (type=='add') {
         var hotdata={
           'hpid':hproid,
           'hcount':hotcount,
           'stype':'2',
           'type':'add',
           'hid':'add',
         };
       } else {
         var hotdata={
           'hpid':hproid,
           'hcount':hotcount,
           'stype':'1',
           'type':'update',
           'hid':type,
         };
       }
       homeinfosave('2',hotdata);
     }
   });
})
////////////////////////////////////////////////////////////////
oFReader = new FileReader(),
rFilter = /^(?:image\/jpeg|image\/png)$/i;
oFReader.onload = function (oFREvent) {
  qdlbimage();
};
function lbimage(ifile){
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

function qdlbimage(){
	$.ajaxFileUpload({
		url: lbimgsaveurl, //用于文件上传的服务器端请求地址
		secureuri: false, //是否需要安全协议，一般设置为false
		fileElementId: 'selimg', //文件上传域的ID
		dataType: 'json', //返回值类型 一般设置为json
		success: function (data, status)  //服务器成功响应处理函数
		{
			if (data.status=='true'){
				$("#lbimage").val(data.datainfo);
				$(".setlbinfo>img").attr("src",data.datainfo);
			}else{
				$("#hdimage").val('');
				tips('notice', '上传失败!', 1500, 'weui_icon_notice');
			}
		},
		complete: function (e) {

		},
		error: function (data,status,e)//服务器响应失败处理函数
		{
			tips('notice', '上传失败!', 1500, 'weui_icon_notice');
		}
	})
}

/////////保存首页信息///////////
function homeinfosave(type,info){
  var htype=type;
  var hinfo=info;
  tips('waiting','保存数据中···');
  $.ajax({
    type:"post",
    url:savehomeurl,
    data:hinfo,
    dateType:"json",
    complete: function(e){
      hidetips('waiting');
    },
    success: function(msg){
        if (msg.status == 'true') {
          if (htype=='0') {
            if (hinfo['type']=='add') {
             swiper.appendSlide('<div class="swiper-slide" data-url="'+hinfo['lbiurl']+'" data-href="'+hinfo['lbhref']+'" data-hid="'+msg.info+'"><img src="'+hinfo['lbiurl']+'" class="imgslide"></div>');
           } else {
             var swid=swiper.activeIndex;
             var selslide=$(swiper.slides[swid]);
             selslide.attr('data-url',hinfo['lbiurl']);
             selslide.attr('data-href',hinfo['lbhref']);
             selslide.children('img').attr('src',hinfo['lbiurl']);
           }
           if (swiper.slides.length>=3) {
             $('.addlb').css('display','none');
           } else {
             $('.addlb').css('display','inline-block');
           }
           $('.home-contents').css('display','block');
           $('.lbinfosetconver').css('display','none');
           $('.lbinfosetconver').attr('data-type','');
          } else if (htype=='1') {
            var imgurl=$('.hasselpro').attr('data-imgurl');
            var proname=$('.hasselpro').attr('data-pname');
            var price=$('.hasselpro').attr('data-price');
            $('.hasselpro').removeClass('hasselpro');
            if (hinfo['type']=='add') {
              selproinfo.attr('data-type',msg.info);
            }
            if (hinfo['hcount']=='1') {
              selproinfo.parent().parent().parent().children('img').attr('src',imgurl);
              selproinfo.parent().parent().parent().children('label').html(proname+'<span>￥'+price+'</span>');
            } else {
              selproinfo.parent().parent().parent().children('img').attr('src',imgurl);
              selproinfo.parent().parent().parent().children('.hotpnpc').children('label:eq(0)').text(proname);
              selproinfo.parent().parent().parent().children('.hotpnpc').children('label:eq(1)').text('￥'+price);
            }
            selproinfo='';
            $('body').css('overflow','auto');
            $('.prohotconver').hide(100);
          }else if (htype=='2') {
            var imgurl=$('.hasselpro').attr('data-imgurl');
            var proname=$('.hasselpro').attr('data-pname');
            var price=$('.hasselpro').attr('data-price');
            $('.hasselpro').removeClass('hasselpro');

            if (hinfo['type']=='add') {
              selproinfo.attr('data-type',msg.info);
            }
            selproinfo.parent().parent().parent().children('img').attr('src',imgurl);
            selproinfo.parent().parent().parent().children('.newpnpc').children('label:eq(0)').text(proname);
            selproinfo.parent().parent().parent().children('.newpnpc').children('label:eq(1)').text('￥'+price);
            selproinfo='';
            $('body').css('overflow','auto');
            $('.prohotconver').hide(100);
          }
          tips('notice', '保存成功!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '保存失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
