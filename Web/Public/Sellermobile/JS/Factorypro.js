$(document).ready(function () {
  $('.selpro').click(function(){
    event.stopPropagation();
    if($(this).parent().hasClass('selproactive')){
      $(this).parent().removeClass('selproactive');
      if ($(this).parent().hasClass('factproopen')) {
        $(this).parent().animate({
          "height":"81px"
        },500,function(){
          $(this).removeClass('factproopen');
        });
      }
    } else {
      $(this).parent().addClass('selproactive');
      if (!$(this).parent().hasClass('factproopen')) {
        $(this).parent().animate({
          "height":$(this).parent().children('.attrdata').height()+80+'px'
        },500,function(){
          $(this).addClass('factproopen');
        });
      }
    }
    if ($('.Factorypro').length==$('.selproactive').length) {
      $('.sel_all').addClass('selallactive');
    } else {
      $('.sel_all').removeClass('selallactive');
    }
  });
  $('.prodata').click(function(){
    if ($(this).parent().hasClass('factproopen')) {
      $(this).parent().animate({
        "height":"81px"
      },500,function(){
        $(this).removeClass('factproopen');
      });
    } else {
      $(this).parent().animate({
        "height":$(this).parent().children('.attrdata').height()+80+'px'
      },500,function(){
        $(this).addClass('factproopen');
      });
    }
  });
  $('.sel_all').click(function(){
    if ($(this).hasClass('selallactive')) {
      $(this).removeClass('selallactive');
      $('.Factorypro').removeClass('selproactive');
      $('.Factorypro').animate({
        "height":"81px"
      },500,function(){
        $(this).removeClass('factproopen');
      });
    } else {
      $(this).addClass('selallactive');
      $('.Factorypro').addClass('selproactive');
      $('.Factorypro').each(function(index,item){
        $(item).animate({
          "height":$(item).children('.attrdata').height()+80+'px'
        },500,function(){
          $(this).addClass('factproopen');
        });
      });
    }
  });
  ///////搜索商品///////
  $('#iconseach').click(function(){
    if ($(this).hasClass('checking')) {
      return false;
    }
    var textinfo=$('#inputseach').val();
    if ($.trim(textinfo)!='') {
      tips('waiting','正在查询···');
      $('#iconseach').addClass('checking');
      $.ajax({
        type:"post",
        url:factorysearch_url,
        data:"textinfo="+textinfo,
        dateType:"json",
        complete: function(e){
          hidetips('waiting');
          $('#iconseach').removeClass('checking');
        },
        success: function(msg){
          if (msg.status == 'true') {
            var prodata= msg.datainfo;
            var htmls='';
            $(prodata).each(function(intex,item){
              htmls+='<div class="Factorypro" data-proid="'+item['ProId']+'"><span class="selpro"></span><img class="proimg" src="'+item['ProLogoImg']+'" alt=""><div class="prodata"><label class="proname">'+item['ProName']+'</label><label class="protitle">'+item['ProTitle']+'</label></div><div class="attrdata"><div class="attrtop"><div><label>统一销售价格:</label><input type="number" name="" value="0.00" class="setaprice"></div><div><label>统一采购数量:</label><input type="number" name="" value="0" class="setanum"></div></div><div class="attrtitle"><label>属性</label><label>工厂价</label><label>销售价格/元</label><label>采购数量</label></div>PROATTRLISTTEMP</div></div>';
              var temphtmls='';
              $(item['attrlist']).each(function(i,it){
                temphtmls+='<div class="attritem" data-cid="'+item['ClassType']+'" data-pcid="'+it['ProIdCard']+'" data-gzprice="'+
                ((isNaN(parseFloat(it['Price'])))?'0.00':parseFloat(it['Price']).toFixed(2))+'" data-cprice="'+
                ((isNaN(parseFloat(it['CosPrice'])))?'0.00':parseFloat(it['CosPrice']).toFixed(2))+'"><div><label>'+it['ProSpec1']+'</label></div><div><label>'+
                ((isNaN(parseFloat(it['Price'])))?'0.00':parseFloat(it['Price']).toFixed(2))+'</label></div><div><input type="number" name="" value="'+
                ((isNaN(parseFloat(it['Price'])))?'0.00':parseFloat(it['Price']).toFixed(2))+'" class="attrprice"></div><div><input type="number" name="" value="0" class="attrnum"></div></div>';
              });
              htmls=htmls.replace(/PROATTRLISTTEMP/g,temphtmls);
              temphtmls='';
            });
            $('.prolist').html(htmls);
            ////////////////////////////////////////
            $('.selpro').click(function(){
              event.stopPropagation();
              if($(this).parent().hasClass('selproactive')){
                $(this).parent().removeClass('selproactive');
                if ($(this).parent().hasClass('factproopen')) {
                  $(this).parent().animate({
                    "height":"81px"
                  },500,function(){
                    $(this).removeClass('factproopen');
                  });
                }
              } else {
                $(this).parent().addClass('selproactive');
                if (!$(this).parent().hasClass('factproopen')) {
                  $(this).parent().animate({
                    "height":$(this).parent().children('.attrdata').height()+80+'px'
                  },500,function(){
                    $(this).addClass('factproopen');
                  });
                }
              }
              if ($('.Factorypro').length==$('.selproactive').length) {
                $('.sel_all').addClass('selallactive');
              } else {
                $('.sel_all').removeClass('selallactive');
              }
            });
            $('.prodata').click(function(){
              if ($(this).parent().hasClass('factproopen')) {
                $(this).parent().animate({
                  "height":"81px"
                },500,function(){
                  $(this).removeClass('factproopen');
                });
              } else {
                $(this).parent().animate({
                  "height":$(this).parent().children('.attrdata').height()+80+'px'
                },500,function(){
                  $(this).addClass('factproopen');
                });
              }
            });
            /////////////////////////////////////
          } else{
            $('.prolist').html('<div class="null_data"><img src="/public/Sellermobile/icon/watermark.png" alt=""></div>');
          }
        },
      })
    } else {
      tips('notice', '请输入商品名称!', 1500, 'weui_icon_notice');
    }
  });
  $('.savesel').click(function(){
    if ($('.selproactive').length>0) {
      var selprolist={};
      var allselpro=0;
      $('.selproactive').each(function(index,item){
        var pid=$(item).attr('data-proid');

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
      });

      selprolist=JSON.stringify(selprolist)
      tips('waiting','正在处理···');
      $.ajax({
        type:"post",
        url:factorysave_url,
        data:"prolist="+selprolist,
        dateType:"json",
        complete: function(e){
          hidetips('waiting');
        },
        success: function(msg){
          if (msg.status == 'true') {
            tips('notice','处理成功',2000,'weui_icon_toast');
            $('.selproactive').remove();
          } else{
            tips('notice', '处理失败!', 1500, 'weui_icon_notice');
          }
        },
      })
    } else {
      tips('notice', '请选择商品!', 1500, 'weui_icon_notice');
    }
  });
})
