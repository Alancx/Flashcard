$(document).ready(function(){
  $("#type").change(function(){
    var type=$("#type").val();
    if (type=='0') {
      $("#title").html('现金抵扣券');
      $("#rules").val('');
      $("#rules").prop('placeholder','请输出抵扣金额 单位(元)');
    };
    if (type=='1') {
      $("#title").html('折扣券');
      $("#rules").val('');
      $("#rules").prop('placeholder','输入折扣比例 如9折 填写0.9即可');
    }
    if (type=='2') {
      $("#title").html('满减券');
      $("#rules").val('');
      $("#rules").prop('placeholder','例：满199减10元  填写 199/10 即可');
    }
  })

  $("#rules").keyup(function(){
    var rules=$("#rules").val();
    var type=$("#type").val();
    if (type=='0') {
      $("#content").html('交易时使用此券可抵扣'+rules+'元');
    }
    if (type=='1') {
      $("#content").html('交易时使用此券可享受'+rules*10+'折');
    }
    if (type=='2') {
      var tempS=new Array();
      tempS=rules.split('/');
      $("#content").html('交易时使用此券,满'+tempS[0]+'元 可减免'+tempS[1]+'元');
    }
  })

  $("#stime").blur(function(){
    $("#start").html($("#stime").val());
  })
  $("#stime").click(function(){
    $("#start").html($("#stime").val());
  })
  $("#etime").blur(function(){
    $("#en").html($("#etime").val());
  })
  $("#etime").click(function(){
    $("#en").html($("#etime").val());
  })

})
