new PCAS(["s_province","请选择省份"],["s_city","请选择城市"])	//三级联动，有默认值，有文字提示信息
  $(document).ready(function(){
    $("#nickname").click(function(){
      $(this).hide(1);
      $("#nic").show(1).focus();
    })
    $("#nic").blur(function(){
      $(this).hide(1);
      if ($("#nic").val()) {
        $.ajax({
          type:"post",
          url:cname,
          data:"newname="+$("#nic").val(),
          dateType:"json",
          success:function(msg){
            if (msg=='success') {
              $("#nickname").show(1).html($("#nic").val());
              tips('修改成功',1000);
            }else{
              $("#nickname").show();
              tips('修改失败',1000);
            }
          }
        })
      }else{
      $("#nickname").show(1);
      }
    })
    $("#sex").click(function(){
      $(this).hide(1);
      $("#csex").show(2);
    })
    $("#man").click(function(){
      $("#csex").hide();
      $.ajax({
        type:"post",
        url:csex,
        data:"sex=1",
        dateType:"json",
        success:function(msg){
          if (msg=='success') {
            $("#sex").html('男').show();
            tips('修改成功',1000);
          }else{
            $("#sex").show();
            tips('修改失败',1000);
          }
        }
      })
    })
    $("#women").click(function(){
      $("#csex").hide();
      $.ajax({
        type:"post",
        url:csex,
        data:"sex=2",
        dateType:"json",
        success:function(msg){
          if (msg=='success') {
            $("#sex").html('女').show();
            tips('修改成功',1000);
          }else{
            $("#sex").show();
            tips('修改失败',1000);
          }
        }
      })
    })
    $("#area").click(function(){
      $("#area-box").show();
    })
    $("#quxiao").click(function(){
      $("#area-box").hide();
    })
    $("#sure").click(function(){
      var province=$("#s_province").val();
      var city=$("#s_city").val();
      if (!province) {
        alert('请选择省份');
        return false;
      };
      if (!city) {
        alert('请选择城市');
        return false;
      }else{
        $("#area-box").hide();
        $.ajax({
          type:"post",
          url:carea,
          data:"province="+province+'&city='+city,
          dateType:"json",
          success:function(msg){
            if (msg=='success') {
              $("#area").html(province+" "+city).show();
              tips('修改成功',1000);
            }else{
              $("#area").show();
              tips('修改失败',1000);
            }
          }
        })
      }
    })

    $("#cmod").click(function(){
      $("#cmod-box").show();
    })
    $("#c-mod").click(function(){
      $("#cmod-box").hide();
    })

    $("#sure-mod").click(function(){
      var pass=$("#password").val();
      var npass=$("#repass").val();
      if (pass && npass) {
        if (pass==npass) {
          $.ajax({
            type:"post",
            url:cmod,
            data:"password="+pass,
            dateType:"json",
            success:function(msg){
              if (msg=='success') {
                $("#cmod-box").hide();
                tips('修改成功',1000);
              }else{
                tips('修改失败',1000);
              }
            }
          })
        }else{
          alert('两次输入密码不一致');
        }
      }else{
        alert('请输入密码');
      }
    })
  })
