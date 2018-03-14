<?php if (!defined('THINK_PATH')) exit();?><html>

	<head>
		<meta charset="UTF-8">
		<title>银行卡信息</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/Public/newhome/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Public/newhome/css/withdraw.css" />
		<!--<link rel="stylesheet" href="/Public/newhome/fonts/mui.ttf" />-->

	</head>
	<style type="text/css">
		input{
			font-size: 14px !important;
		}
	</style>
	<body  style="font-size: 14px !important">
		<div class="mui-input-group" id="input_example">
			<div class="mui-input-row">
				<label >户主姓名：</label>
				<input type="text" class="mui-input-clear" id="user" data-id="<?php echo ($bankmsg["ID"]); ?>" placeholder="张XX" value="<?php echo ($bankmsg["IdName"]); ?>">
			</div>
			<div class="mui-input-row">
				<label>银行名称：</label>
				<input type="text" class="mui-input-clear" id="bank" placeholder="中国农业银行" value="<?php echo ($bankmsg["BankName"]); ?>">
			</div>
			<div class="mui-input-row">
				<label>银行卡号：</label>
				<input type="text" class="mui-input-clear" id="bankCard" placeholder="622848***321" value="<?php echo ($bankmsg["BankId"]); ?>">
			</div>

		</div>
		<div class="btn">
			<button type="button" class="save">保存</button>
		</div>
		<script src="/Public/newhome/js/mui.min.js"></script>
		<script src="/Public/newadmin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/Public/JS/plugins/layer/layer.min.js"></script>

		<script type="text/javascript">
			mui.init()
		</script>
		<script type="text/javascript">
			//银行卡号校验
			//Description: 银行卡号Luhm校验
			//Luhm校验规则：16位银行卡号（19位通用）:
			// 1.将未带校验位的 15（或18）位卡号从右依次编号 1 到 15（18），位于奇数位号上的数字乘以 2。
			// 2.将奇位乘积的个十位全部相加，再加上所有偶数位上的数字。
			// 3.将加法和加上校验位能被 10 整除。
			function luhmCheck(bankCard){
				if(bankCard == "") {
					layer.msg("请填写银行卡号");
					return false;
				}
				var num = /^\d*$/; //全数字
                if(!num.exec(bankCard)) {
                    layer.msg("银行卡号必须全部为数字");
                    return false;
				}
						
				if(bankCard.length<16 || bankCard.length>19) {
					layer.msg("银行卡号长度必须在16~19位之间");
					return false;
				}
				//开头6位
				var strBin="10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99";
				if (strBin.indexOf(bankCard.substring(0, 2))== -1) {
					layer.msg("银行卡号开头6位不符合规范");
					return false;
				}
				var lastNum=bankCard.substr(bankCard.length-1,1);//取出最后一位（与luhm进行比较）
				var first15Num=bankCard.substr(0,bankCard.length-1);//前15或18位
				var newArr=new Array();
				for(var i=first15Num.length-1;i>-1;i--){ //前15或18位倒序存进数组
					newArr.push(first15Num.substr(i,1));
				}
				var arrJiShu=new Array(); //奇数位*2的积 <9
				var arrJiShu2=new Array(); //奇数位*2的积 >9
				var arrOuShu=new Array(); //偶数位数组
				for(var j=0;j<newArr.length;j++){
					//奇数位
					if((j+1)%2 == 1) {
						if(parseInt(newArr[j])*2 < 9) {
							arrJiShu.push(parseInt(newArr[j])*2);
						}else  {
							arrJiShu2.push(parseInt(newArr[j])*2);
						}
					//偶数位
					}else {
						arrOuShu.push(newArr[j]);
					}	
				}
				var jishu_child1=new Array();//奇数位*2 >9 的分割之后的数组个位数
				var jishu_child2=new Array();//奇数位*2 >9 的分割之后的数组十位数
				for(var h=0;h<arrJiShu2.length;h++){
					jishu_child1.push(parseInt(arrJiShu2[h])%10);
					jishu_child2.push(parseInt(arrJiShu2[h])/10);
				}
				var sumJiShu=0; //奇数位*2 < 9 的数组之和
				var sumOuShu=0; //偶数位数组之和
				var sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
				var sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
				var sumTotal=0;
				for(var m=0;m<arrJiShu.length;m++){
					sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
				}
				for(var n=0;n<arrOuShu.length;n++){
					sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
				}
				for(var p=0;p<jishu_child1.length;p++){
					sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
					sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
				}
				//计算总和
				sumTotal=parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);
				//计算Luhm值
				var k= parseInt(sumTotal)%10==0?10:parseInt(sumTotal)%10;
				var luhm= 10-k;
				if(lastNum==luhm){
					// layer.msg("Luhm验证通过");
					return true;
				}else {
					layer.msg("银行卡号必须真实");
					return false;
				}
			}
			/*
				中文验证
			*/
			function checkInput(str) {   
			   var r;
			   if (str.length<10) {
				    var re = /^[\u4E00-\u9FA5]{1,10}$/;
				    r = re.test(str);
				    if (!r) {
				    	layer.msg('银行名称只能输入中文');
				    	return false;
			    	}else{
			    		return true;
			    	}
			   } else{
			    	layer.msg('银行名称长度不得大于10个字符');
			    	return false;
			   }
			} 
			function checkInputs(str) {   
			   var r;
			   if (str.length<10) {
				    var re = /^[\u4E00-\u9FA5]{1,10}$/;
				    r = re.test(str);
				    if (!r) {
				    	layer.msg('户主姓名只能输入中文');
				    	return false;
			    	}else{
			    		return true;
			    	}
			   } else{
			    	layer.msg('户主姓名长度不得大于10个字符');
			    	return false;
			   }
			} 
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				var check;
				$(".save").on("tap", function() {
					mui("#input_example input").each(function() {
						//若当前input为空，则alert提醒 
						if(!this.value || this.value.trim() == "") {
							var label = this.previousElementSibling;
							mui.alert(label.innerText + "不允许为空");
							check = false;
							return false;
						} else {
							check = true;
							// return true;
						}
					}); //校验通过，继续执行业务逻辑 
					if(check) {
						//获取用户名
						var user = $('#user').val();
						//获取银行名称
						var bank = $('#bank').val();
						//获取银行卡号
						var bankCards = $('#bankCard').val();
						//获取银行卡号
						var bankCard = $.trim(bankCards);
						//获取ID值
						var id = $("#user").attr('data-id');
                        //Luhm校验（新）
                        if(!luhmCheck(bankCard) || !checkInput(bank) || !checkInputs(user)) {
                           return false;
                        }else {
                        	layer.msg("验证通过!");
							$.ajax({
								url: "<?php echo U('Tuier/bankMessage');?>",
								type: 'post',
								data: "user=" + user + "&bank=" + bank + "&bankCard=" + bankCard + "&id=" + id,
								dataType: 'json',
								success: function(msg) {
									if(msg.type == "success") {
										window.location.href = "<?php echo U('Tuier/getMoney');?>";
									} else {
										layer.msg('请重新填写');
									}
								}
							})
                   		 }
					}
				})

			})
		</script>
	</body>

</html>