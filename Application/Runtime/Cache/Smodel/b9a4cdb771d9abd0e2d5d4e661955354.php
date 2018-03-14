<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="/Public/Admin/Admin/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Public/Admin/Admin/css/weui.min.css">
  <title>信息台</title>
  <style type="text/css">
  *{font-family: 'HirginoSansGB-W3'}
    .btn-box{
        width: 100%;
        height: 50px;
        position: fixed;
        bottom: 0px;
        left: 0px;
        background-color: #fff;
        text-align: center;
        border-top: 1px solid #ddd;
    }
    body{
/*        background-color: #ddd;
        max-width: 360px;
*/    }
    .container{
        max-width: 360px;
    }
    .row{
        background-color: #EAEAEB;
        padding-bottom: 600px;
    }
    .top-btn{
        padding-left: 15px;
        padding-right: 15px;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    .mybtn{
        border:1px solid #22a4ff;
        border-radius: 5px;
        color: #22a4ff;
        font-size: 14px;
        height: 26px;
        width: 75px;
    }
    .msg-box{
        width: 100%;
        margin-top: 10px;
        padding-right: 0px;
        padding-left: 0px;
    }
    .msg{
        /*width: 100%;*/
        background-color: #fff;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 10px;
        padding-right: 20px;
        padding-left: 20px;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        border-radius: 5px;
    }
    .time{
        color: #999;
        font-size: 13px;
        line-height: 26px;
        position: relative;
    }
    .time>span{
        position: absolute;
        right: 0px;
    }
    .msg-success{
        color: #02A342;
    }
    .msg-danger{
        color: #f61d1d;
    }
    .msg-warning{
        color: orange;
    }
    .infos{
        width: 100%;
        position: relative;
        padding-top: 14px;
        padding-bottom: 14px;
    }
    .imgbox{
        width: 20%;
        float: left;
    }
    .imgbox>img{
        width: 90%;
        margin-left: 5%;
    }
    .price{
        color: #f61d1d;
        font-size: 17px;
        padding-left: 14px;
        width: 80%;
        float: right;
        margin-top: 10px;
    }
    .payname{
        color: #999;
        font-size: 12px;
        padding-left: 14px;
        width: 80%;
        float: right;
        position: relative;
    }
    .payname>span{
        position: absolute;
        right: 0px;
        font-size: 15px;
        color: #333;
    }
    .clear{
        clear: both;
        width: 100%;
        height: 40px;
        line-height: 40px;
        /*border-top: 1px solid #ddd;*/
        cursor: pointer;
        margin-bottom: 5px;
    }
    .clear>button{
        width: 100%;
    }
    .bobtn{
/*        width: 50%;
        float: left;
*/        line-height: 50px;
    }
    .bobtn>img{
        height: 30px;
        padding-right: 8px;
    }
    .btn-box .bobtn:first-child{
        border-right: 1px solid #ddd;
    }
    .disabled{
        z-index: 10;
        background-color: rgba(221,221,221,0.6);
    }
    .msg>.title{
        padding-top: 20px;
        font-size: 16px;
    }
    .msg>.price-box{
        padding-top: 10px;
        text-align: center;
    }
    .price-box>h4{
        color: #8a8a8a;
    }
    .price-box>h2{
        margin-top: 0px;
    }
    .list-unstyled>li{
        margin-top: 10px;
    }
    li>span{
        color: #8a8a8a;
        padding-right: 10px;
    }
  </style>
</head>
<body>
	<div class="container">
		<div class="row">
            <div class="top-btn">
                <button class="mybtn pull-left" id="showall">查看全部</button>
                <button class="mybtn pull-right" id="clear">清空消息</button>
            </div>
                <marquee behavior="scroll" direction="left" id='tips'></marquee>
			<div class="col-xs-12 msg-box">
<!--                 <div class="msg">
                    <div class="title">
                        支付凭证
                    </div>
                    <div class="time">
                        06月05日 12:00:00
                    </div>
                    <div class="price-box">
                        <h4>支付金额</h4>
                        <h2>￥0.01</h2>
                    </div>
                    <div class="infos">
                        <ul class="list-unstyled">
                            <li><span>用户昵称：</span>初见</li>
                            <li><span>支付方式：</span>零钱</li>
                            <li><span>商品总额：</span>0.01</li>
                            <li><span>优惠金额：</span>0</li>
                            <li><span>商品详情：</span>购买商品</li>
                            <li><span>交易单号：</span>E7089089080989898</li>
                        </ul>
                    </div>
                    <div class="clear"><button class="btn btn-default">确 定</button></div>
                </div>
 -->            </div>
		</div>
        <div class="btn-box">
            <div style="width:50%;float:left" class="disabled">
                <div class="bobtn"  id="get">
                    <img src="/Public/Admin/Admin/img/get.png" alt="">接收消息
                </div>
            </div>
            <div style="width:50%;float:left">
                <div class="bobtn"  id="stop">
                    <img src="/Public/Admin/Admin/img/stop.png" alt="">停止接收
                </div>
            </div>
        </div>
	</div>
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">已完成</p>
        </div>
    </div>
    <div id="loadingToast" class="weui_loading_toast" style="display:none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">数据加载中</p>
        </div>
    </div>
<!--     <div class="btn-box">
        <button class="btn btn-success btn-md disabled" id="get">接收消息</button>
        <button class="btn btn-warning btn-md" id="stop">停止接收</button>
        <button class="btn btn-danger btn-md" id="clear">清空消息</button>
        <button class="btn btn-default btn-md">查看全部</button>
    </div>
 --></body>
<script type="text/javascript" src="/Public/Admin/jquery.min.js"></script>
<script type="text/javascript">
    var token='<?php echo $token ?>';
    var sid='<?php echo $sid ?>';
    var mid='<?php echo $mid ?>';
    var count=0;
    function getmsg(){
        $.ajax({
            url:"<?php echo U('Base/getmsg');?>",
            type:"post",
            data:"token="+token+'&sid='+sid+'&mid='+mid,
            dataType:"json",
            success:function(msg){
                if (msg.statu=='success') {
                    var msgdata=msg.msgs;
                    var html='';
                    var tempCount=0;
                    $.each(msgdata,function(index,item){
                        var type='';
                        var img='';
                        if (item.paytype=='XJ') {
                            type='现金支付';
                            img='xjpay.png';
                        }else if(item.paytype=='YE'){
                            type='余额支付';
                            img='yepay.png';
                        }else{
                            type='微信支付';
                            img='wxpay.png';
                        }
                        var discount=parseFloat(item.money)-parseFloat(item.truemoney);
                        html+='<div class="msg"><div class="title"> 支付凭证 </div> <div class="time"> '+item.paytime+' </div> <div class="price-box"> <h4>支付金额</h4> <h2>￥'+item.truemoney+'</h2> </div> <div class="infos"> <ul class="list-unstyled"> <li><span>用户昵称：</span>'+item.wxname+'</li><li><span>支付方式：</span>'+type+'</li><li><span>商品总额：</span>'+item.money+'</li><li><span>优惠金额：</span>'+discount+'</li> <li><span>商品详情：</span>购买商品</li> <li><span>交易单号：</span>'+item.oid+'</li> </ul> </div> <div class="clear"><button class="btn btn-default msg-btn">确 定</button></div> </div>';
                        // html+='<div class="msg"><div class="time"> '+item.paytime+' <span class=\'msg-success\'>支付成功</span> </div> <div class="infos"> <div class="imgbox"> <img src="/Public/Admin/Admin/img/'+img+'" alt=""> </div> <div class="price">￥'+item.money+'</div> <div class="payname">'+type+'<span class=\'wxname\'>'+item.wxname+'</span> </div> </div> <div class="clear"></div> </div>';
                        tempCount+=1;
                    })
                    // $('#msg-box').before(msg.msg);
                    $('.msg-box').prepend(html);
                    count+=tempCount;
                    if (count>0) {
                        $('#tips').html(count+'条未读消息');
                        // $('#msg-tips').html(count);
                    };
                    // var info=msg.msg;
                    // $("'"+msg.msg+"'").insertBefore("#msg-box");
                };
            }
        })
    }
    $(document).ready(function(){
        var getmsg=setInterval('getmsg()',2000);
        $('#get').click(function(){
            $(this).parent().addClass('disabled');
            $('#stop').parent().removeClass('disabled');
            getmsg=setInterval('getmsg()',2000);
        })
        $('#stop').click(function(){
            $(this).parent().addClass('disabled');
            $('#get').parent().removeClass('disabled');
            window.clearInterval(getmsg);
        })
        $('#clear').click(function(){
            // window.opener=null;window.open('','_self');window.close();
            if (confirm('确定要清空消息吗？')) {
                $('.msg-box').html('');
            };
        })
        $(document).on('click','.msg-btn',function(){
            count-=1;
            $(this).addClass('disabled').html('已读');
            if (count>0) {
                $('#tips').html(count+'条未读消息');
            }else{
                $('#tips').html('');
            }
        })
        $('#showall').click(function(){
            $(this).parent().addClass('disabled');
            $('#get').parent().removeClass('disabled');
            window.clearInterval(getmsg);
            $.ajax({
                url:"<?php echo U('Base/getAll');?>",
                type:"post",
                data:"token="+token+"&sid="+sid,
                dataType:"json",
                success:function(msg){
                    if (msg.statu=='success') {
                        var msgdata=msg.msgs;
                        var html='';
                        $.each(msgdata,function(index,item){
                            var type='';
                            var img='';
                            if (item.paytype=='XJ') {
                                type='现金支付';
                                img='xjpay.png';
                            }else if(item.paytype=='YE'){
                                type='余额支付';
                                img='yepay.png';
                            }else{
                                type='微信支付';
                                img='wxpay.png';
                            }
                            var discount=parseFloat(item.money)-parseFloat(item.truemoney);
                            html+='<div class="msg"><div class="title"> 支付凭证 </div> <div class="time"> '+item.paytime+' </div> <div class="price-box"> <h4>支付金额</h4> <h2>￥'+item.truemoney+'</h2> </div> <div class="infos"> <ul class="list-unstyled"> <li><span>用户昵称：</span>'+item.wxname+'</li><li><span>支付方式：</span>'+type+'</li><li><span>商品总额：</span>'+item.money+'</li><li><span>优惠金额：</span>'+discount+'</li> <li><span>商品详情：</span>购买商品</li> <li><span>交易单号：</span>'+item.oid+'</li> </ul> </div> </div>';
                        })
                        $('.msg-box').html(html);
                    };
                }
            })
        })
    })
</script>
</html>