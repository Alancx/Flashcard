$(document).ready(function () {
  ///////// 设置中间区域的高度////////
  $('.all_pro_list').css('height',$('.all_prolist').height()-$('.sel_protype').height()-71+'px');
  $('.searchprolist').css('height',$('.searchconver').height()-45+'px');
  ////////////////点击搜索///////////
  $('.srarchbtn').click(function(){
    $('.searchconver').css('left','100%');
    $('.searchprolist').html('');
    $('#inputseach').val('');
    $('.searchconver').css('display','block');
    $('.searchconver').animate({
      "left":"0%"
    },500);
  });
  /////////取消搜索//////////////
  $('.qxsearch').click(function(){
    $('.searchconver').animate({
      "left":"100%"
    },500,function(){
      $(this).css('display','none');
      $('.searchprolist').html('');
      $('#inputseach').val('');
    });
  });
  ///////////搜索商品信息///////////////
  $('#iconseach').click(function(){
    $('.searchprolist').html('');
    var searchtext=$('#inputseach').val();
    if ($.trim(searchtext)!='') {
      $('.classpro_list>.proinfo').each(function(index,item){
        var pname=$(item).children('div').children('.proname').text();
        if (pname.indexOf(searchtext)!=-1) {
          // console.log(pname);
          $(item).clone(true).appendTo('.searchprolist');
        }
      });
      // console.log($('.searchprolist').children().length);
      $('.searchprolist').children().css('display','block');
      if ($('.searchprolist').children().length==0) {
        tips('notice', '无搜索商品信息!', 1500, 'weui_icon_notice');
      }
    } else {
      tips('notice', '请输入要搜索的商品名称!', 1500, 'weui_icon_notice');
    }
  });
  ////////////设置商品分类中的商品信息///////////////////////
  $('.classpro_list>.proinfo').each(function(index,item){
    var cname=$(item).attr('data-cname');
    var cid=$(item).attr('data-cid');
    if ($('.class_'+cid).length==0) {
      var classcount=$('.classpro_list>.proinfo[data-cid='+cid+']').length;
      var htmls='<div class="classtype_info class_'+cid+'" data-cid="'+cid+'" onclick="selclasspro(this)">'+
      '<label>'+cname+'<span>('+classcount+')</span></label></div>';
      $('.classtype_list').append(htmls);
    }
  });
  ///////////选择商品类型/////////////////
  $('.sel_protype_top').click(function(){
    var stype=$(this).attr('data-type');
    if (!$(this).hasClass('sel_topactive')) {
      $('.sel_topactive').removeClass('sel_topactive');
      $(this).addClass('sel_topactive');
      if (stype=='1') {
        $('.all_pro_list>div').css('display','none');
        $('.all_pro_list>.facpro_list').css('display','block');
        $('.sel_protype_2').css('display','none');
        $('.all_pro_list').css('height',$('.all_prolist').height()-$('.sel_protype').height()-71+'px');
        $('.magpro').css('display','block');
        $('.add_pro').css('width','50%');
      } else if (stype=='2') {
        $('.all_pro_list>div').css('display','none');
        $('.all_pro_list>.selfpro_list').css('display','block');
        $('.sel_protype_2').css('display','block');
        $('.all_pro_list').css('height',$('.all_prolist').height()-$('.sel_protype').height()-71+'px');
        $('.selfpro_list>.proinfo').css('display','none');
        if ($('.sel_protype_2>.sel_bottomactive').attr('data-type')=='1') {
          $('.selfpro_list>.proinfo[data-shelve=1]').css('display','block');
        } else {
          $('.selfpro_list>.proinfo[data-shelve=0]').css('display','block');
        }
        $('.magpro').css('display','block');
        $('.add_pro').css('width','50%');
      }
      else if (stype=='3') {
        $('.all_pro_list>div').css('display','none');
        $('.all_pro_list>.classpro_list').css('display','block');
        $('.classtype_list').css('display','block');
        $('.classpro_list>.proinfo').css('display','none');
        $('.sel_protype_2').css('display','none');
        $('.all_pro_list').css('height',$('.all_prolist').height()-$('.sel_protype').height()-71+'px');
        $('.magpro').css('display','none');
        $('.add_pro').css('width','100%');

        $('.btn_group>.noedit').css('display','block');
        $('.btn_group>.yesedit').css('display','none');
      }
      $('.sel_exit').click();
    }
  })
  //////////出售中和已下架商品选择///////////////
  $('.sel_protype_bottom').click(function(){
    var stype=$(this).attr('data-type');
    if (!$(this).hasClass('sel_bottomactive')) {
      $('.sel_bottomactive').removeClass('sel_bottomactive');
      $(this).addClass('sel_bottomactive');
      $('.selfpro_list>.proinfo').each(function(index,item){
        if (stype=='1') {
          if ($(item).attr('data-shelve')=='1') {
            $(item).css('display','block');
          } else {
            $(item).css('display','none');
          }
        } else {
          if ($(item).attr('data-shelve')=='1') {
            $(item).css('display','none');
          } else {
            $(item).css('display','block');
          }
        }
      });
      $('.sel_exit').click();
    }
  })
  /////////////////点击添加商品///////////////
  $('.add_pro').click(function(){
    // $('.converaddpro').css('display','block');
    // $('body').css('overflow','hidden');
    window.location.href=$(this).attr('data-url');
  })
  /////////////////取消添加商品///////////////
  // $('.cancelpro').click(function(){
  //   $('.converaddpro').css('display','none');
  //   $('body').css('overflow','auto');
  // })
  ///////点击批量处理商品////////////
  $('.magpro').click(function(){
    //////////////
    $('.menulistconver').css('display','block');
    $('.menulistconver').css('top','100%');
    var ptype=$('.sel_topactive').attr('data-type');
    if (ptype=='1') {
      $('.menuxj').css('display','none');
    } else {
      $('.menuxj').css('display','block');
      if ($('.sel_bottomactive').attr('data-type')=='1') {
          $('.menuxj').text('批量下架');
        } else {
          $('.menuxj').text('批量上架');
        }
    }
    $('.menulistconver').animate({
      "top":"0px"
    },500);
  })
  ///////////////////////////////
  $('.menulistconver').click(function(){
    $('.menulistconver').animate({
      "top":"100%"
    },500,function(){
      $('.menulistconver').css('display','none');
    });
  });
  /////////批量编辑选择//////////////////////
  $('.menuedit').click(function(){
    event.stopPropagation();
    var ptype=$('.sel_topactive').attr('data-type');
    if (ptype=='1') {
      $('.facpro_list>.proinfo>.selmag').css('display','block');
      $('.facpro_list>.proinfo>div').css('padding-left','120px');
      $('.facpro_list>.proinfo>div>.clickinfo').css('display','none');
      $('.btn_group>.noedit').css('display','none');
      $('.btn_group>.yesedit').css('display','block');
      $('.btn_group>.yesedit>.magdel_pro').text('保存');
      $('.btn_group>.yesedit>.magdel_pro').attr('type','edit');
      $('.btn_group>.yesedit>label').css('width','50%');
    } else {
        $('.selfpro_list>.proinfo>.selmag').css('display','block');
        $('.selfpro_list>.proinfo>div').css('padding-left','120px');
        $('.selfpro_list>.proinfo>div>.clickinfo').css('display','none');
        $('.btn_group>.noedit').css('display','none');
        $('.btn_group>.yesedit').css('display','block');
        $('.btn_group>.yesedit>.magdel_pro').text('保存');
        $('.btn_group>.yesedit>.magdel_pro').attr('type','edit');
        $('.btn_group>.yesedit>label').css('width','50%');
    }
    $('.menulistconver').animate({
      "top":"100%"
    },500,function(){
      $('.menulistconver').css('display','none');
    });
  });
  /////////批量删除选择//////////////////////
  $('.menudel').click(function(){
    event.stopPropagation();
    var ptype=$('.sel_topactive').attr('data-type');
    if (ptype=='1') {
      $('.facpro_list>.proinfo>.selmag').css('display','block');
      $('.facpro_list>.proinfo>div').css('padding-left','120px');
      $('.facpro_list>.proinfo>div>.clickinfo').css('display','none');
      $('.btn_group>.noedit').css('display','none');
      $('.btn_group>.yesedit').css('display','block');
      $('.btn_group>.yesedit>.magdel_pro').text('删除');
      $('.btn_group>.yesedit>.magdel_pro').attr('type','delete');
      $('.btn_group>.yesedit>label').css('width','50%');
    } else {
      $('.selfpro_list>.proinfo>.selmag').css('display','block');
      $('.selfpro_list>.proinfo>div').css('padding-left','120px');
      $('.selfpro_list>.proinfo>div>.clickinfo').css('display','none');
      $('.btn_group>.noedit').css('display','none');
      $('.btn_group>.yesedit').css('display','block');
      $('.btn_group>.yesedit>.magdel_pro').text('删除');
      $('.btn_group>.yesedit>.magdel_pro').attr('type','delete');
      $('.btn_group>.yesedit>label').css('width','50%');
    }
    $('.menulistconver').animate({
      "top":"100%"
    },500,function(){
      $('.menulistconver').css('display','none');
    });
  });
  ////////批量上下架操作/////////////
  $('.menuxj').click(function(){
    event.stopPropagation();
    var ptype=$('.sel_topactive').attr('data-type');
    if (ptype=='2') {
      $('.selfpro_list>.proinfo>.selmag').css('display','block');
      $('.selfpro_list>.proinfo>div').css('padding-left','120px');
      $('.selfpro_list>.proinfo>div>.clickinfo').css('display','none');
      $('.btn_group>.noedit').css('display','none');
      $('.btn_group>.yesedit').css('display','block');
      if ($('.sel_bottomactive').attr('data-type')=='1') {
          $('.btn_group>.yesedit>.magdel_pro').text('下架');
        } else {
          $('.btn_group>.yesedit>.magdel_pro').text('上架');
        }
      $('.btn_group>.yesedit>.magdel_pro').attr('type','shelves');
      $('.btn_group>.yesedit>label').css('width','50%');
    }
    $('.menulistconver').animate({
      "top":"100%"
    },500,function(){
      $('.menulistconver').css('display','none');
    });
  });
  ///////取消批量处理商品////////////
  $('.sel_exit').click(function(){
    var ptype=$('.sel_topactive').attr('data-type');
    if (ptype=='1') {
      $('.facpro_list>.proinfo>.selmag').css('display','none');
      $('.facpro_list>.proinfo>.selmag').css('background-image',"url("+imgurl+"uncheck.png)");
      $('.facpro_list>.proinfo>.selmag').attr('data-type','1');
      $('.facpro_list>.proinfo>div').css('padding-left','85px');
      $('.facpro_list>.proinfo>div>.clickinfo').css('display','block');
      if (($('.magdel_pro').attr('type')=='edit')||($('.magdel_pro').attr('type')=='')) {
        $('.facpro_list>.proinfo').animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
      $('.magdel_pro').attr('type','');
      $('.btn_group>.noedit').css('display','block');
      $('.btn_group>.yesedit').css('display','none');
    } else if (ptype=='2') {
      $('.selfpro_list>.proinfo>.selmag').css('display','none');
      $('.selfpro_list>.proinfo>.selmag').css('background-image',"url("+imgurl+"uncheck.png)");
      $('.selfpro_list>.proinfo>.selmag').attr('data-type','1');
      $('.selfpro_list>.proinfo>div').css('padding-left','85px');
      $('.selfpro_list>.proinfo>div>.clickinfo').css('display','block');
      if (($('.magdel_pro').attr('type')=='edit')||($('.magdel_pro').attr('type')=='')) {
        $('.selfpro_list>.proinfo').animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
      $('.magdel_pro').attr('type','');
      $('.btn_group>.noedit').css('display','block');
      $('.btn_group>.yesedit').css('display','none');
    }
    $('.sel_all').css('background-image',"url("+imgurl+"uncheck.png)");
    $('.sel_all').attr('data-type','1');
  })
  /////////点击///////////
  $(document.body).on('touchstart',function(event){
    if (event.originalEvent.targetTouches.length == 1) {
      var tdom=event.originalEvent.target;
      if (!$(tdom).hasClass('editmenu')) {
        if((!$(tdom).parent().hasClass('editmenu'))&&(!$(tdom).parent().parent().hasClass('editmenu'))){
          $('.editmenu').css('display','none');
        }
      }
    }
  })
})
//////////选择要编辑的商品/////////////////////
function clickinfo(img) {
  $('.editmenu').css('display','none');
  $(img).parent().parent().children('.editmenu').css('display','block');
}
/////////删除商品信息////////////////
function delpro(div){
  var ptype=$('.sel_topactive').attr('data-type');
  var pid=$(div).attr('data-pid');
  var proidlist={};
  proidlist[0]=pid;
  if (ptype=='3') {
    ptype=$(div).parent().parent().attr('data-ptype');
  }
  // console.log(proidlist);
  // console.log(ptype);
  // return false;
  tips('waiting','正在删除···');
  $.ajax({
    type:"post",
    url:deletepro,
    data:"ProId="+JSON.stringify(proidlist)+"&type="+ptype,
    dateType:"json",
    complete: function(e){
    hidetips('waiting');
    },
    success: function(msg){
        if (msg.status == 'true') {
          $(msg.data).each(function(intex,item){
            $('.P_'+item).remove();
          });
            tips('notice', '已删除!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '删除失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
///////////设置商品上下架////////////////
function shelvepro(div){
  var pid=$(div).parent().parent().attr('data-pid');
  var shelve=$(div).parent().parent().attr('data-shelve');
  var proidlist={};
  proidlist[0]=pid+'|'+shelve;
  tips('waiting','正在处理···');
  $.ajax({
    type:"post",
    url:editshelve,
    data:"ProId="+JSON.stringify(proidlist),
    dateType:"json",
    complete: function(e){
    hidetips('waiting');
    },
    success: function(msg){
        if (msg.status == 'true') {
          $('.editmenu').css('display','none');
          $(msg.data).each(function(intex,item){
            itemarray=item.split('|');
            if ($('.sel_topactive').attr('data-type')=='2') {
              $('.selfpro_list>.P_'+itemarray[0]).css('display','none');
            }
            if (itemarray[1]=='0') {
              $('.P_'+itemarray[0]).attr('data-shelve','1');
              $('.P_'+itemarray[0]).children('.editmenu').children('.shelvepro').children('label').text('下架');
            } else {
              $('.P_'+itemarray[0]).attr('data-shelve','0');
              $('.P_'+itemarray[0]).children('.editmenu').children('.shelvepro').children('label').text('上架');
            }
          });
            tips('notice', '已完成!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '处理失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
/////////批量上下架//////////////////
function magxjpro(){
  var selprocont=0;
  var proidlist={};
  $('.selfpro_list>.proinfo>.selmag').each(function(index,item){
    if ($(item).attr('data-type')=='2') {
      proidlist[selprocont]=$(item).parent().attr('data-pid')+'|'+$(item).parent().attr('data-shelve');
      selprocont=selprocont+1;
    }
  });
  if (selprocont==0) {
    tips('notice', '选择商品!', 1500, 'weui_icon_notice');
    return false;
  }
  tips('waiting','正在处理···');
  $.ajax({
    type:"post",
    url:editshelve,
    data:"ProId="+JSON.stringify(proidlist),
    dateType:"json",
    complete: function(e){
    hidetips('waiting');
    },
    success: function(msg){
        if (msg.status == 'true') {
          $('.editmenu').css('display','none');
          $('.sel_exit').click();
          $(msg.data).each(function(intex,item){
            itemarray=item.split('|');
            $('.P_'+itemarray[0]).css('display','none');
            if (itemarray[1]=='0') {
              $('.P_'+itemarray[0]).attr('data-shelve','1');
              $('.P_'+itemarray[0]).children('.editmenu').children('.shelvepro').children('label').text('下架');
            } else {
              $('.P_'+itemarray[0]).attr('data-shelve','0');
              $('.P_'+itemarray[0]).children('.editmenu').children('.shelvepro').children('label').text('上架');
            }
          });
            tips('notice', '已完成!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '处理失败!', 1500, 'weui_icon_notice');
        }
    },
  })
}
/////////批量删除/////////////////////////
function magdelpro(){
  if ($('.magdel_pro').attr('type')=='delete') {
    var ptype=$('.sel_topactive').attr('data-type');
    var selprocont=0;
    var proidlist={};
    if (ptype=='1') {
      $('.facpro_list>.proinfo>.selmag').each(function(index,item){
        if ($(item).attr('data-type')=='2') {
          proidlist[selprocont]=$(item).parent().attr('data-pid');
          selprocont=selprocont+1;
        }
      });
    } else if (ptype=='2') {
      $('.selfpro_list>.proinfo>.selmag').each(function(index,item){
        if ($(item).attr('data-type')=='2') {
          proidlist[selprocont]=$(item).parent().attr('data-pid');
          selprocont=selprocont+1;
        }
      });
    }
    if (selprocont==0) {
      tips('notice', '选择删除商品!', 1500, 'weui_icon_notice');
      return false;
    }
    // console.log(proidlist);
    // console.log(ptype);
    // return false;
    tips('waiting','正在删除···');
    $.ajax({
      type:"post",
      url:deletepro,
      data:"ProId="+JSON.stringify(proidlist)+"&type="+ptype,
      dateType:"json",
      complete: function(e){
        hidetips('waiting');
      },
      success: function(msg){
        if (msg.status == 'true') {
          $(msg.data).each(function(intex,item){
            $('.P_'+item).remove();
          });
          tips('notice', '已删除!', 1500, 'weui_icon_toast');
        } else{
          tips('notice', '删除失败!', 1500, 'weui_icon_notice');
        }
      },
    })
  } else if ($('.magdel_pro').attr('type')=='edit') {
    editselmag();
  } else if ($('.magdel_pro').attr('type')=='shelves') {
    magxjpro();
  }
}
//////////////批量编辑保存信息///////////
function editselmag(){
  var ptype=$('.sel_topactive').attr('data-type');
  var selprocont=0;///判断是否有选择商品
  var selprolist={};///////选择的商品的属性列表
  var allselpro=0;/////属性的key
  if (ptype=='1') {    ///////选卖工厂商品
    $('.facpro_list>.proinfo').each(function(index,item){
      if ($(item).children('.selmag').attr('data-type')=='2') {
        selprocont=selprocont+1;
        var pid=$(item).attr('data-pid');
        var setprice=$(item).children('.attrdata').children('.attrtop').children('div').children('.setaprice').val();
        setprice=(isNaN(parseFloat(setprice)))?parseFloat('0.00').toFixed(2):parseFloat(setprice).toFixed(2);

        var setcgnum=$(item).children('.attrdata').children('.attrtop').children('div').children('.setanum').val();
        setcgnum=(isNaN(parseInt(setcgnum)))?'0':parseInt(setcgnum).toFixed(0);

        $(item).children('.attrdata').children('.attritem').each(function(i,it){
          var pcid=$(it).attr('data-pcid');
          var gzprice=$(it).attr('data-gzprice');
          var cprice=$(it).attr('data-cprice');
          var cid=$(it).attr('data-cid');
          if (setprice=='0.00') {
            var attrprice=$(it).children('div').children('.attrprice').val();
            attrprice=(isNaN(parseFloat(attrprice)))?parseFloat(gzprice).toFixed(2):parseFloat(attrprice).toFixed(2);
          } else {
            var attrprice=setprice;
          }
          if (setcgnum=='0') {
            var attrnum=$(it).children('div').children('.attrnum').val();
            attrnum=(isNaN(parseInt(attrnum)))?parseInt(setcgnum).toFixed(0):parseInt(attrnum).toFixed(0);
          } else {
            var attrnum=setcgnum;
          }
          var gzproinfo={};
          gzproinfo['pid']=pid;
          gzproinfo['pcid']=pcid;
          gzproinfo['cid']=cid;
          gzproinfo['price']=attrprice;
          gzproinfo['num']=attrnum;
          gzproinfo['cprice']=(isNaN(parseFloat(cprice)))?parseFloat(cprice).toFixed(2):parseFloat(cprice).toFixed(2);
          selprolist[allselpro]=gzproinfo;
          allselpro++;
        });

      }
    });
    if (selprocont==0) {
      tips('notice', '选择编辑商品!', 1500, 'weui_icon_notice');
      return false;
    }
    selprolist=JSON.stringify(selprolist)
    tips('waiting','正在保存···');
    $.ajax({
      type:"post",
      url:factoryeditsave_url,
      data:"prolist="+selprolist,
      dateType:"json",
      complete: function(e){
        hidetips('waiting');
      },
      success: function(msg){
        if (msg.status == 'true') {
          tips('notice','保存成功',2000,'weui_icon_toast');
          setTimeout(function(e){
            window.location.reload();
          },1000);
        } else{
          tips('notice', '保存失败!', 1500, 'weui_icon_notice');
        }
      },
    })
  } else if (ptype=='2') {    //////自营商品修改价格
    $('.selfpro_list>.proinfo').each(function(index,item){
      if ($(item).children('.selmag').attr('data-type')=='2') {
        selprocont=selprocont+1;
        var pid=$(item).attr('data-pid');
        var setprice=$(item).children('.attrdata').children('.attrtop').children('div').children('.setaprice').val();
        setprice=(isNaN(parseFloat(setprice)))?parseFloat('0.00').toFixed(2):parseFloat(setprice).toFixed(2);

        $(item).children('.attrdata').children('.attritem').each(function(i,it){
          var pcid=$(it).attr('data-pcid');
          var cid=$(it).attr('data-cid');
          if (setprice=='0.00') {
            var attrprice=$(it).children('div').children('.attrprice').val();
            attrprice=(isNaN(parseFloat(attrprice)))?parseFloat(gzprice).toFixed(2):parseFloat(attrprice).toFixed(2);
          } else {
            var attrprice=setprice;
          }
          var gzproinfo={};
          gzproinfo['pid']=pid;
          gzproinfo['pcid']=pcid;
          gzproinfo['cid']=cid;
          gzproinfo['price']=attrprice;
          selprolist[allselpro]=gzproinfo;
          allselpro++;
        });

      }
    });

    if (selprocont==0) {
      tips('notice', '选择编辑商品!', 1500, 'weui_icon_notice');
      return false;
    }
    selprolist=JSON.stringify(selprolist)
    tips('waiting','正在保存···');
    $.ajax({
      type:"post",
      url:selfproeditsave_url,
      data:"prolist="+selprolist,
      dateType:"json",
      complete: function(e){
        hidetips('waiting');
      },
      success: function(msg){
        if (msg.status == 'true') {
          tips('notice','保存成功',2000,'weui_icon_toast');
          setTimeout(function(e){
            window.location.reload();
          },1000);
        } else{
          tips('notice', '保存失败!', 1500, 'weui_icon_notice');
        }
      },
    })
  }
}
////////////显示属性信息////////////////////
function openattr(div){
  if ($('.magdel_pro').attr('type')=='edit') {
    if (!$(div).parent().hasClass('factproopen')) {
      $(div).parent().animate({
        "height":$(div).parent().children('.attrdata').height()+80+'px'
      },500,function(){
        $(this).addClass('factproopen');
      });
    } else {
      $(div).parent().animate({
        "height":"81px"
      },500,function(){
        $(this).removeClass('factproopen');
      });
    }
  }
}
/////////选择批量处理的商品///////////////
function selmagpro(span){
  event.stopPropagation();
  var stype=$(span).attr('data-type');
  if (stype=='1') {
    $(span).attr('data-type','2');
    $(span).css('background-image',"url("+imgurl+"checked.png)");
  } else {
    $(span).attr('data-type','1');
    $(span).css('background-image',"url("+imgurl+"uncheck.png)");
  }
  var ptype=$('.sel_topactive').attr('data-type');
  if (ptype=='1') {
    var selprocont=0;
    $('.facpro_list>.proinfo>.selmag').each(function(index,item){
      if ($(item).attr('data-type')=='2') {
        selprocont=selprocont+1;
      }
    });

    if ($('.magdel_pro').attr('type')=='edit') {
      if (stype=='1') {
        $(span).parent().animate({
          "height":$(span).parent().children('.attrdata').height()+80+'px'
        },500,function(){
          $(this).addClass('factproopen');
        });
      } else {
        $(span).parent().animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
    }

    if (selprocont==$('.facpro_list>.proinfo>.selmag').length) {
      $('.sel_all').css('background-image',"url("+imgurl+"checked.png)");
      $('.sel_all').attr('data-type','2');
    } else {
      $('.sel_all').css('background-image',"url("+imgurl+"uncheck.png)");
      $('.sel_all').attr('data-type','1');
    }
  } else if (ptype=='2') {
    var btype=$('.sel_bottomactive').attr('data-type');
    var selprocont=0;
    var procount=0;
    if (btype=='1') {
      $('.selfpro_list>.proinfo>.selmag').each(function(index,item){
        if ($(item).parent().attr('data-shelve')=='1') {
          procount=procount+1;
          if ($(item).attr('data-type')=='2') {
            selprocont=selprocont+1;
          }
        }
      });
    } else {
      $('.selfpro_list>.proinfo>.selmag').each(function(index,item){
        if ($(item).parent().attr('data-shelve')=='0') {
          procount=procount+1;
          if ($(item).attr('data-type')=='2') {
            selprocont=selprocont+1;
          }
        }
      });
    }
    if ($('.magdel_pro').attr('type')=='edit') {
      if (stype=='1') {
        $(span).parent().animate({
          "height":$(span).parent().children('.attrdata').height()+80+'px'
        },500,function(){
          $(this).addClass('factproopen');
        });
      } else {
        $(span).parent().animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
    }
    if (selprocont==procount) {
      $('.sel_all').css('background-image',"url("+imgurl+"checked.png)");
      $('.sel_all').attr('data-type','2');
    } else {
      $('.sel_all').css('background-image',"url("+imgurl+"uncheck.png)");
      $('.sel_all').attr('data-type','1');
    }
  }
}
//////////根据分类选择商品///////////////
function selclasspro(div){
  var cid=$(div).attr('data-cid');
  $('.classtype_list').css('display','none');
  $('.classpro_list>.proinfo').css('display','none');
  $('.classpro_list>.proinfo[data-cid='+cid+']').css('display','block');
}
//////////////////全选按钮处理////////////////
function selmagallpro(label){
  var ptype=$('.sel_topactive').attr('data-type');
  if (ptype=='1') {
    if ($(label).attr('data-type')=='1') {
      $(label).css('background-image',"url("+imgurl+"checked.png)");
      $(label).attr('data-type','2');
      $('.facpro_list>.proinfo>.selmag').css('background-image',"url("+imgurl+"checked.png)");
      $('.facpro_list>.proinfo>.selmag').attr('data-type','2');
      if ($('.magdel_pro').attr('type')=='edit') {
        $('.facpro_list>.proinfo').each(function(i,it){
          $(it).animate({
            "height":$(it).children('.attrdata').height()+80+'px'
          },500,function(){
            $(this).addClass('factproopen');
          });
        });
      }
    } else {
      $(label).css('background-image',"url("+imgurl+"uncheck.png)");
      $(label).attr('data-type','1');
      $('.facpro_list>.proinfo>.selmag').css('background-image',"url("+imgurl+"uncheck.png)");
      $('.facpro_list>.proinfo>.selmag').attr('data-type','1');
      if ($('.magdel_pro').attr('type')=='edit') {
        $('.facpro_list>.proinfo').animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
    }
  } else if (ptype=='2') {
    if ($('.sel_bottomactive').attr('data-type')=='1') {
      if ($(label).attr('data-type')=='1') {
        $(label).css('background-image',"url("+imgurl+"checked.png)");
        $(label).attr('data-type','2');
        $('.selfpro_list>.proinfo[data-shelve=1]>.selmag').css('background-image',"url("+imgurl+"checked.png)");
        $('.selfpro_list>.proinfo[data-shelve=1]>.selmag').attr('data-type','2');
        if ($('.magdel_pro').attr('type')=='edit') {
          $('.selfpro_list>.proinfo[data-shelve=1]').each(function(i,it){
            $(it).animate({
              "height":$(it).children('.attrdata').height()+80+'px'
            },500,function(){
              $(this).addClass('factproopen');
            });
          });
        }
      } else {
        $(label).css('background-image',"url("+imgurl+"uncheck.png)");
        $(label).attr('data-type','1');
        $('.selfpro_list>.proinfo[data-shelve=1]>.selmag').css('background-image',"url("+imgurl+"uncheck.png)");
        $('.selfpro_list>.proinfo[data-shelve=1]>.selmag').attr('data-type','1');
        if ($('.magdel_pro').attr('type')=='edit') {
          $('.selfpro_list>.proinfo[data-shelve=1]').animate({
            "height":"81px"
          },500,function(){
            $(this).removeClass('factproopen');
          });
        }
      }
    } else {
      if ($(label).attr('data-type')=='1') {
        $(label).css('background-image',"url("+imgurl+"checked.png)");
        $(label).attr('data-type','2');
        $('.selfpro_list>.proinfo[data-shelve=0]>.selmag').css('background-image',"url("+imgurl+"checked.png)");
        $('.selfpro_list>.proinfo[data-shelve=0]>.selmag').attr('data-type','2');
        if ($('.magdel_pro').attr('type')=='edit') {
          $('.selfpro_list>.proinfo[data-shelve=0]').each(function(i,it){
            $(it).animate({
              "height":$(it).children('.attrdata').height()+80+'px'
            },500,function(){
              $(this).addClass('factproopen');
            });
          });
        }
      } else {
        $(label).css('background-image',"url("+imgurl+"uncheck.png)");
        $(label).attr('data-type','1');
        $('.selfpro_list>.proinfo[data-shelve=0]>.selmag').css('background-image',"url("+imgurl+"uncheck.png)");
        $('.selfpro_list>.proinfo[data-shelve=0]>.selmag').attr('data-type','1');
        if ($('.magdel_pro').attr('type')=='edit') {
          $('.selfpro_list>.proinfo[data-shelve=0]').animate({
            "height":"81px"
          },500,function(){
            $(this).removeClass('factproopen');
          });
        }
      }
    }
  }
}
/////////自营编辑商品//////////////////
function editpro(label){
  var pid=$(label).attr('data-pid');
  var gotourl=updatepro.replace(/PRODUCTID/g,pid);
  window.location.href=gotourl;
}
//////炫迈平台商品编辑//////
function faceditpro(label){
  var pid=$(label).attr('data-pid');
  var gotourl=updatefacpro.replace(/PRODUCTID/g,pid);
  window.location.href=gotourl;
}
