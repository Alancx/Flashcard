var totalprice = 0;
$(document).ready(function(){
  setcartsinfo();
});
// 进入时显示商品信息
function setcartsinfo(){
  if (olinfo !='') {
    var olArray = JSON.parse(olinfo);
    var htmls ='';
    var clength = 0;
    $.each(olArray,function(index,item){
      htmls +='<div class="proinfo">'+
      '<img src="'+item['ProLogoImg']+'" alt="">'+
      '<span class="pname">'+item['ProName']+'</span>'+
      '<span class="psnums">×'+item['Count']+'</span>'+
      '<span class="pprice">'+parseFloat(item['Price']).toFixed(2)+'</span>'+
      '</div>';
      clength++;
    })
    $('.pro_list').html(htmls);
    if(clength >3){
      $('.pro_list').css('height',85*3 + 'px');
      $('.getmorepros').css('display','block');
      setgetmorebtn();
    }
  }
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
