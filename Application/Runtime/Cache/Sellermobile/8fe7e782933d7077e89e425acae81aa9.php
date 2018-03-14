<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
	<title><?php echo ($Title); ?></title>
	<link href="/Public/Sellermobile/CSS/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/Sellermobile/CSS/weui.css" rel="stylesheet">
	<script src="/Public/Sellermobile/JS/jquery.min.js"></script>
	<script src="/Public/Sellermobile/JS/bootstrap.min.js"></script>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="/Public/Sellermobile/JS/base.js"></script>
	<link rel="stylesheet" href="/Public/Sellermobile/CSS/pagemodel.css?v=1.0" media="screen" title="no title" charset="utf-8">

   <!-- 微信分享 -->
	 <script type="text/javascript">
	 var wxJSSDKConfig = <?php echo ($wxJSSDKConfigStr); ?>;
	 wx.config(wxJSSDKConfig);

	 wx.ready(function (a) {

	   wx.hideAllNonBaseMenuItem();

    <?php echo ($showlist); ?>
	  //  wx.showMenuItems({
	  //    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"] // 要显示的菜单项，所有menu项见附录3
	  //  });
	   wx.onMenuShareAppMessage({
	     title: '<?php echo ($shopname); ?>',
	     desc: '<?php echo ($shopdesc); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });

	   wx.onMenuShareTimeline({
	     title: '<?php echo ($Title); ?>',
	     link: '<?php echo ($shareUrl); ?>',
	     imgUrl: '<?php echo ($shareImg); ?>',
	     trigger: function (res) {

	     },
	     success: function (res) {
	       alert('已分享');
	     },
	     cancel: function (res) {
	       alert('已取消');
	     },
	     fail: function (res) {
	       alert(JSON.stringify(res));
	     }
	   });
	 });
	 wx.error(function (res) {
	   // alert(res);
	 });
	 </script>

</head>
<body>
	<!-- 正文显示区域 -->
	<div class="container">
		
<link rel="stylesheet" href="/Public/Sellermobile/css/proedit.css?v=4.7">
<script type="text/javascript" src="/Public/Sellermobile/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/Public/Sellermobile/js/proedit.js?v=2.6"></script>
<script type="text/javascript">
var proimagesave= "<?php echo U('Products/proimage');?>";//商品图片保存地址
var prosaveurl= "<?php echo U('Products/prosave');?>";//商品信息保存地址
var classdata=<?php echo ($Cclass); ?>;//商品所有分类
var SClassid="<?php echo ($Pdata['ClassType']); ?>";//商品所属二级分类ID
var SClassname="<?php echo ($Pdata['ClassName']); ?>";//商品所属二级分类名称
var Simages='<?php echo ($Simgdata); ?>';//修改时展示图信息
var prosaveyes="<?php echo U('Products/prolist');?>";//保存成功跳转
var classupdate_url="<?php echo U('Products/classedit');?>";//分类设置
</script>
<div class="outpart">
	<div class="partpro">
		<div class="pimgname">
			<div class="pimg">
				<?php if ($Pdata): ?>
					<img id="himage" src="<?php echo ($Pdata['ProLogoImg']); ?>">
					<input type="file"  id="selhimg" name="selimg" onchange="selhimage(this)">
					<input type="hidden" id="hdimage" value="<?php echo ($Pdata['ProLogoImg']); ?>">
				<?php else:?>
				<img id="himage" src="/Public/Sellermobile/icon/addimg.png">
				<input type="file"  id="selhimg" name="selimg" onchange="selhimage(this)">
				<input type="hidden" id="hdimage" value="">
				<?php endif; ?>
			</div>
			<input type="text" class="pname " id="proname" name="pname" placeholder="请输入商品名称" value="<?php echo ($Pdata['ProName']); ?>">
		</div>
		<div class="showimg pimgname">
			<label>
				<span>选择商品展示图片</span>
				<span>(单击可删除)</span>
			</label>
			<div class="addimg">
				<img src="/Public/Sellermobile/icon/addimg.png" id="addimg">
				<input type="file"  id="selswimg" name="selimg" onchange="selsimage(this)" data-sum="0">
			</div>
		</div>
	</div>
	<div class="partpro">
		<div class="partproitem">
			<label class="pitemleft">商品类别</label>
			<div class="selclass">
				<div>
					<select id="selclass-1" name="class1">
						<option value="-1">请选择</option>
						<?php if(is_array($Pclass)): foreach($Pclass as $key=>$lclass): if($lclass['ClassGrade'] == 1): ?><option value="<?php echo ($lclass['ClassId']); ?>"><?php echo ($lclass['ClassName']); ?></option><?php endif; endforeach; endif; ?>
					</select>
				</div>
				<div class="setclass">
					<span class="set_class">设置分类</span>
				</div>
				<!-- <div>
					<select id="selclass-2" name="class2">
						<option value="-1">请选择</option>
					</select>
				</div> -->
			</div>
		</div>
	</div>
	<div class="partpro partattrs">
		<?php if(is_array($prolist)): foreach($prolist as $key=>$plitem): ?><div class="partattr" data-attrid="<?php echo ($plitem['ProIdCard']); ?>">
				<div class="attritem">
					<label>规格</label>
					<input type="text" class="attredit" name="attrtext" value="<?php echo ($plitem['ProSpec1']); ?>" placeholder="商品属性">
				</div>
				<div class="attritem" style="display:none;">
					<label>编码</label>
					<input type="text" class="attredit" name="proinputcode" value="<?php echo ($plitem['InputCode']); ?>" placeholder="商品编码">
				</div>
				<div class="attritem" style="display:none;">
					<label>条码</label>
					<input type="text" class="attredit" name="procode" value="<?php echo ($plitem['ProIdInputCard']); ?>" placeholder="商品条码">
				</div>
				<div class="attritem" style="display:none;">
					<label>价格</label>
					<input type="number" class="attredit" name="proaprice" value="<?php echo $plitem['Price']?sprintf("%.2f",$plitem['Price']):'0.00' ?>" placeholder="商品价格">
				</div>
				<div class="attritem" style="display:none;">
					<label>库存</label>
					<input type="number" class="attredit" name="procount" value="<?php echo ($plitem['Count']); ?>" placeholder="商品库存">
				</div>
				<div class="removeattr"><span onclick="delattr(this)"></span></div>
			</div><?php endforeach; endif; ?>
  </div>
	<div class="partpro attrspro">
	<label>添加商品属性</label>
	</div>
	<div class="partpro">
		<div class="partproitem">
			<label class="pitemleft">商品标题</label>
			<input type="text" id="protilte" class="editbox " name="ptname" placeholder="商品标题" value="<?php echo ($Pdata['ProTitle']); ?>">
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">商品说明</label>
			<input type="text" id="pronote" class="editbox " name="ptname" placeholder="商品说明" value="<?php echo ($Pdata['ProSubtitle']); ?>">
		</div>
		<div class="partproitem" >
			<label class="pitemleft">市场价格</label>
			<input type="number" id="proprice" class="editbox " name="ptname" placeholder="商品价格" value="<?php echo ($Pdata['PriceRange']); ?>">
		</div>
		<div class="partproitem">
			<label class="pitemleft">出售价格</label>
			<input type="number" id="prosaleprice" class="editbox " name="ptname" placeholder="出售价格" value="<?php echo ($Pdata['Price']); ?>">
		</div>
		<div class="partproitem">
			<label class="pitemleft">商品编号</label>
			<input type="text" id="pronumber" class="editbox " name="ptname" placeholder="商品编号" value="<?php echo ($Pdata['ProNumber']); ?>">
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">商品条码</label>
			<input type="text" id="procode" class="editbox " name="ptname" placeholder="商品条码" value="<?php echo ($Pdata['Barcode']); ?>">
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">商品重量</label>
			<input type="number" id="proweight" class="editbox " name="ptname" placeholder="商品重量 单位/g" value="<?php echo ($Pdata['Weight']); ?>">
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">备注说明</label>
			<input type="text" id="promark" class="editbox " name="ptname" placeholder="备注说明" value="<?php echo ($Pdata['Remarks']); ?>">
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">检索关键字</label>
			<input type="text" id="prosearch" class="editbox " name="ptname" placeholder="检索关键字" value="<?php echo ($Pdata['KeyWord']); ?>">
		</div>
	</div>
	<div class="partpro" style="display:none;">
		<div class="partproitem">
			<label class="pitemleft">员工提成</label>
			<input type="number" id="proecom" class="editbox " name="ptname" placeholder="员工提成 单位/%" value="<?php echo ($Pdata['EmpCut']); ?>">
		</div>
		<div class="partproitem">
			<label class="pitemleft">一级提成</label>
			<input type="number" id="proonecom" class="editbox " name="ptname" placeholder="一级提成 单位/%" value="<?php echo ($Pdata['Cut']); ?>">
		</div>
		<div class="partproitem">
			<label class="pitemleft">推广佣金</label>
			<input type="number" id="propcom" class="editbox " name="ptname" placeholder="推广佣金 单位/%" value="<?php echo ($Pdata['ExtendCut']); ?>">
		</div>
	</div>
	<div class="partpro">
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">使用优惠券</label>
			<div class="addrcheck">
				<?php if($Pdata['IsUseConpon']=='1'):?>
				<input type="checkbox" id="checkbox_c1" class="chk_1" checked/>
			<?php else:?>
				<input type="checkbox" id="checkbox_c1" class="chk_1"/>
			<?php endif;?>
				<label for="checkbox_c1"></label>
			</div>
		</div>
		<div class="partproitem" style="display:none;">
			<label class="pitemleft">是否赠品</label>
			<div class="addrcheck">
				<?php if($Pdata['Iszp']=='1'):?>
				<input type="checkbox" id="checkbox_c2" class="chk_1" checked/>
			<?php else:?>
				<input type="checkbox" id="checkbox_c2" class="chk_1"/>
			<?php endif;?>
				<label for="checkbox_c2"></label>
			</div>
		</div>
		<div class="partproitem">
			<label class="pitemleft">积分兑换</label>
			<input type="number" id="proredeem" class="editbox jfedit" name="ptname" placeholder="所需积分 单位/分" value="<?php echo ($Pdata['Score']); ?>">
			<div class="addrcheck">
				<?php if($Pdata['IsUseScore']=='1') :?>
				<input type="checkbox" id="checkbox_c3" class="chk_1" checked/>
			<?php else:?>
				<input type="checkbox" id="checkbox_c3" class="chk_1"/>
			<?php endif;?>
				<label for="checkbox_c3"></label>
			</div>
		</div>

		<div class="partproitem">
			<label class="pitemleft">是否重量计算</label>
			<div class="addrcheck">
				<?php if($Pdata['NumType']=='2'):?>
				<input type="checkbox" id="checkbox_c4" class="chk_1" checked/>
			<?php else:?>
				<input type="checkbox" id="checkbox_c4" class="chk_1"/>
			<?php endif;?>
				<label for="checkbox_c4"></label>
			</div>
		</div>


		<div class="partproitem" style="display:block">
			<label class="pitemleft"> 商品详情</label>
			<label class="pitemright">选择编辑<span></span></label>
			</div>
		</div>
	</div>
</div>
<div class="btnsure">
	<button type="button" class="btn btn-danger  btn-block" id="addpro" data-type="<?php echo ($Stype); ?>" data-pid="<?php echo ($proid); ?>">保存</button>
</div>

<!-- 分类设置信息 -->
<div class="markclass">
	<div class="classlistinfo">
		<div class="setclassinfo">
			<span class="class_1">分类名称</span>
			<input type="text" class="classnameinfo" name="classnameinfo" value="" placeholder="填写分类名称">
			<span class="class_2">排序</span>
			<input type="text" class="classsortinfo" name="classsortinfo" value="" placeholder="填写排序">
		  <span class="saveclassinfo">保存</span>
		</div>

		<div class="class_list">
			<ul>
				<?php if(is_array($Pclass)): foreach($Pclass as $key=>$lclass): if($lclass['ClassGrade'] == 1): ?><li class="classiteminfo" data-id="<?php echo ($lclass['ClassId']); ?>" data-sort="<?php echo ($lclass['ClassSort']); ?>">
							<span class="class_name"><?php echo ($lclass['ClassName']); ?></span>
							<span class="class_sort">(<?php echo ($lclass['ClassSort']); ?>)</span>
							<span class="editclass">编辑</span>
							<span class="deleteclass">删除</span>
						</li><?php endif; endforeach; endif; ?>
			</ul>
		</div>

		<div class="bottomclass">
			<span class="closeclass">关闭</span>
		</div>
	</div>
</div>
<!-- 分类设置信息end -->
<!-- 商品性情 -->
<div class="prodetailsmark">
	<div class="prodetails">
		<div class="prodetailcontents"><?php echo (htmlspecialchars_decode($Pdata['ProContent'])); ?></div>
		<div class="addproimg">
			<img src="/Public/Sellermobile/icon/add_img.png" alt="">
			<input type="file"  id="detailimg" name="selimg" onchange="seldetailimg(this)">
		</div>
	</div>
	<div class="prodetailssure">
		<span>确定</span>
	</div>
</div>
<!-- 商品性情end -->

	</div>

	<!-- 底部导航栏 -->
	<?php if($footerSign == 1): ?><div style="height:50px"></div>
		<div class="footer">
			<div>
				<a href="<?php echo U('Index/Index');?>">
					<?php if (CONTROLLER_NAME=='Index' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/shop-act.png">
					<label style="color:#ff3e30">店铺</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/shop.png">
					<label>店铺</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a href="<?php echo U('Products/prolist');?>">
					<?php if (CONTROLLER_NAME=='Products' && ACTION_NAME=='prolist'): ?>
						<img src="/Public/Sellermobile/icon/product-act.png">
						<label style="color:#ff3e30">商品</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/product.png">
					<label>商品</label>
				<?php endif; ?>
				</a>
			</div>
			<div>
				<a>
					<img src="/Public/Sellermobile/icon/active.png">
					<label>动态</label>
				</a>
			</div>
			<div>
				<a href="<?php echo U('User/Index');?>">
					<?php if (CONTROLLER_NAME=='User' && ACTION_NAME=='Index'): ?>
					<img src="/Public/Sellermobile/icon/center-act.png">
					<label style="color:#ff3e30">我的</label>
				<?php else: ?>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				<?php endif; ?>
				</a>
				<!-- <a>
					<img src="/Public/Sellermobile/icon/center.png">
					<label>我的</label>
				</a> -->
			</div>
		</div><?php endif; ?>
	<!-- weui提示框 -->
	<div id="notice" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content"></p>
		</div>
	</div>

	<div class="weui_dialog_confirm" id="confirm" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog default" id="esc">取消</a>
				<a href="javascript:;" class="weui_btn_dialog primary" id="enter" data-s="" data-idcard=''>确定</a>
			</div>
		</div>
	</div>


	<div class="weui_dialog_alert" id="alert" style="display: none;">
		<div class="weui_mask"></div>
		<div class="weui_dialog">
			<div class="weui_dialog_hd"><strong class="weui_dialog_title">提示信息</strong></div>
			<div class="weui_dialog_bd"></div>
			<div class="weui_dialog_ft">
				<a href="javascript:;" class="weui_btn_dialog primary" id='alertenter'>确定</a>
			</div>
		</div>
	</div>

	<div id="waiting" class="weui_loading_toast" style="display:none;">
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
</body>
</html>