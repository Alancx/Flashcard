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
		
<link rel="stylesheet" href="/Public/Sellermobile/css/prolist.css?v=1.5">
<script type="text/javascript" src="/Public/Sellermobile/js/prolist.js?V=1.5"></script>
<script  type="text/javascript">
var updatepro="<?php echo U('Products/proedit',array('proid'=>'PRODUCTID'));?>";//修改自营产品信息
var updatefacpro="<?php echo U('Products/Factoryproadd',array('proid'=>'PRODUCTID','type'=>'U'));?>";//修改工厂产品信息
var imgurl = "/Public/Sellermobile/icon/";//图标路径
var editshelve="<?php echo U('Products/setshelve');?>";////修改是否上架
var deletepro="<?php echo U('Products/prodelete');?>";////删除商品
var factoryeditsave_url="<?php echo U('Products/Factoryproeditsave');?>";////保存批量编辑的工厂商品
var selfproeditsave_url="<?php echo U('Products/selfproeditsave');?>";////保存批量编辑的自营商品
</script>
<div class="all_prolist">
	<div class="sel_protype">
		<img src="/Public/Sellermobile/icon/search.png" class="srarchbtn" alt="">
		<div class="sel_protype_1">
			<!-- <label class="sel_protype_top sel_topactive" data-type="1">工厂商品</label> -->
			<label class="sel_protype_top sel_topactive" data-type="2">自营商品</label>
			<label class="sel_protype_top" data-type="3">商品分类</label>
		</div>
		<div class="sel_protype_2">
			<label class="sel_protype_bottom sel_bottomactive" data-type="1">出售中<span></span></label>
			<label class="sel_protype_bottom" data-type="2">已下架<span></span></label>
		</div>
	</div>
	<div class="all_pro_list">
		<div class="facpro_list" style="display:none;">
			<?php if($facpro == 'NULLFACPRO' ): ?><img class="watermatk" src="/Public/Sellermobile/Icon/watermark.png" alt="">
				<?php else: ?>
				<?php if(is_array($facpro)): foreach($facpro as $key=>$item): ?><div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>">
						<span class="selmag" data-type="1" onclick="selmagpro(this)">
						</span>
						<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
						<div onclick="openattr(this)">
							<label class="proname"><?php echo ($item['ProName']); ?></label>
							<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
							<label class="proprice">￥<?php echo $item['Price']?sprintf("%.2f",$item['Price']):'0.00' ?></label>
							<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
						</div>
						<div class="editmenu">
							<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
								<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
								<label>删除</label>
							</div>
							<div class="faceditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="faceditpro(this)">
								<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
								<label>编辑</label>
							</div>
						</div>
						<div class="attrdata">
		          <div class="attrtop">
		            <div>
		              <label>统一销售价格:</label>
		              <input type="number" name="" value="0.00" class="setaprice">
		            </div>
		            <div>
		              <label>统一采购数量:</label>
		              <input type="number" name="" value="0" class="setanum">
		            </div>
		          </div>
		          <div class="attrtitle">
		            <label>属性</label>
		            <label>原销售价</label>
		            <label>销售价格/元</label>
		            <label>采购数量</label>
		          </div>
		          <?php if(is_array($item['attrlist'])): foreach($item['attrlist'] as $key=>$attritem): ?><div class="attritem" data-cid="<?php echo ($item['ClassType']); ?>" data-pcid="<?php echo ($attritem['ProIdCard']); ?>" data-gzprice="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" data-cprice="<?php echo $attritem['CosPrice']?sprintf("%.2f",$attritem['CosPrice']):'0.00' ?>">
		              <div><label><?php echo ($attritem['ProSpec1']); ?></label></div>
		              <div><label><?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?></label></div>
		              <div><input type="number" name="" value="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" class="attrprice"></div>
		              <div><input type="number" name="" value="0" class="attrnum"></div>
		            </div><?php endforeach; endif; ?>
		        </div>
					</div><?php endforeach; endif; endif; ?>
		</div>
		<div class="selfpro_list">
			<?php if($selfpro == 'NULLSELFPRO' ): ?><img class="watermatk" src="/Public/Sellermobile/Icon/watermark.png" alt="">
				<?php else: ?>
				<?php if(is_array($selfpro)): foreach($selfpro as $key=>$item): if($item['IsShelves'] == '1' ): ?><div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>" data-shelve="<?php echo ($item['IsShelves']); ?>">
						<span class="selmag" data-type="1" onclick="selmagpro(this)">
						</span>
						<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
						<div onclick="openattr(this)">
							<label class="proname"><?php echo ($item['ProName']); ?></label>
							<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
							<label class="proprice">￥<?php echo $item['Price']?sprintf("%.2f",$item['Price']):'0.00' ?></label>
							<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
						</div>
						<div class="editmenu">
							<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
								<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
								<label>删除</label>
							</div>
							<div class="shelvepro" data-pid="<?php echo ($item['ProId']); ?>" onclick="shelvepro(this)" >
								<img src="/Public/Sellermobile/icon/xj_pro.png" alt="">
								<label>下架</label>
							</div>
							<div class="selfeditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="editpro(this)">
								<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
								<label>编辑</label>
							</div>
						</div>
						<div class="attrdata">
		          <div class="attrtop">
		            <div>
		              <label>统一销售价格:</label>
		              <input type="number" name="" value="0.00" class="setaprice">
		            </div>
		          </div>
		          <div class="attrtitle">
		            <label>属性</label>
		            <label>原销售价</label>
		            <label>销售价格/元</label>
		          </div>
		          <?php if(is_array($item['attrlist'])): foreach($item['attrlist'] as $key=>$attritem): ?><div class="attritem" data-cid="<?php echo ($item['ClassType']); ?>" data-pcid="<?php echo ($attritem['ProIdCard']); ?>" data-gzprice="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" data-cprice="<?php echo $attritem['CosPrice']?sprintf("%.2f",$attritem['CosPrice']):'0.00' ?>">
		              <div><label><?php echo ($attritem['ProSpec1']); ?></label></div>
		              <div><label><?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?></label></div>
		              <div><input type="number" name="" value="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" class="attrprice"></div>
		            </div><?php endforeach; endif; ?>
		        </div>
					</div>
					<?php else: ?>
					<div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>" data-shelve="<?php echo ($item['IsShelves']); ?>" style="display:none;">
						<span class="selmag" data-type="1" onclick="selmagpro(this)">
						</span>
						<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
						<div onclick="openattr(this)">
							<label class="proname"><?php echo ($item['ProName']); ?></label>
							<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
							<label class="proprice">￥<?php echo $item['PriceRange']?sprintf("%.2f",$item['PriceRange']):'0.00' ?></label>
							<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
						</div>
						<div class="editmenu">
							<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
								<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
								<label>删除</label>
							</div>
							<div class="shelvepro" data-pid="<?php echo ($item['ProId']); ?>" onclick="shelvepro(this)">
								<img src="/Public/Sellermobile/icon/xj_pro.png" alt="">
								<label>上架</label>
							</div>
							<div class="selfeditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="editpro(this)">
								<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
								<label>编辑</label>
							</div>
						</div>
						<div class="attrdata">
		          <div class="attrtop">
		            <div>
		              <label>统一销售价格:</label>
		              <input type="number" name="" value="0.00" class="setaprice">
		            </div>
		          </div>
		          <div class="attrtitle">
		            <label>属性</label>
		            <label>原销售价</label>
		            <label>销售价格/元</label>
		          </div>
		          <?php if(is_array($item['attrlist'])): foreach($item['attrlist'] as $key=>$attritem): ?><div class="attritem" data-cid="<?php echo ($item['ClassType']); ?>" data-pcid="<?php echo ($attritem['ProIdCard']); ?>" data-gzprice="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" data-cprice="<?php echo $attritem['CosPrice']?sprintf("%.2f",$attritem['CosPrice']):'0.00' ?>">
		              <div><label><?php echo ($attritem['ProSpec1']); ?></label></div>
		              <div><label><?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?></label></div>
		              <div><input type="number" name="" value="<?php echo $attritem['Price']?sprintf("%.2f",$attritem['Price']):'0.00' ?>" class="attrprice"></div>
		            </div><?php endforeach; endif; ?>
		        </div>
					</div><?php endif; endforeach; endif; endif; ?>
		</div>
		<div class="classpro_list" style="display:none;">
			<?php if($allpro == 'NULLALLPRO' ): ?><img class="watermatk" src="/Public/Sellermobile/Icon/watermark.png" alt="">
				<?php else: ?>
				<div class="classtype_list">
				</div>
				<?php if(is_array($allpro)): foreach($allpro as $key=>$item): if($item['ptype'] == '1'): ?><div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>" data-cname="<?php echo ($item['ClassName']); ?>" data-cid="<?php echo ($item['ClassType']); ?>" data-ptype="<?php echo ($item['ptype']); ?>">
							<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
							<div>
								<label class="proname"><?php echo ($item['ProName']); ?></label>
								<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
								<label class="proprice">￥<?php echo $item['PriceRange']?sprintf("%.2f",$item['PriceRange']):'0.00' ?></label>
								<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
							</div>
							<div class="editmenu">
								<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
									<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
									<label>删除</label>
								</div>
								<div class="faceditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="faceditpro(this)">
									<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
									<label>编辑</label>
								</div>
							</div>
						</div>
						<?php else: ?>
						<?php if($item['IsShelves'] == '1' ): ?><div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>" data-shelve="<?php echo ($item['IsShelves']); ?>" data-cname="<?php echo ($item['ClassName']); ?>" data-cid="<?php echo ($item['ClassType']); ?>" data-ptype="<?php echo ($item['ptype']); ?>">
								<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
								<div>
									<label class="proname"><?php echo ($item['ProName']); ?></label>
									<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
									<label class="proprice">￥<?php echo $item['PriceRange']?sprintf("%.2f",$item['PriceRange']):'0.00' ?></label>
									<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
								</div>
								<div class="editmenu">
									<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
										<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
										<label>删除</label>
									</div>
									<div class="shelvepro" data-pid="<?php echo ($item['ProId']); ?>" onclick="shelvepro(this)" >
										<img src="/Public/Sellermobile/icon/xj_pro.png" alt="">
										<label>下架</label>
									</div>
									<div class="selfeditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="editpro(this)">
										<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
										<label>编辑</label>
									</div>
								</div>
							</div>
							<?php else: ?>
							<div class="proinfo P_<?php echo ($item['ProId']); ?>" data-pid="<?php echo ($item['ProId']); ?>" data-shelve="<?php echo ($item['IsShelves']); ?>" data-cname="<?php echo ($item['ClassName']); ?>" data-cid="<?php echo ($item['ClassType']); ?>" data-ptype="<?php echo ($item['ptype']); ?>">
								<img src="<?php echo ($item['ProLogoImg']); ?>" alt="">
								<div>
									<label class="proname"><?php echo ($item['ProName']); ?></label>
									<label class="protitle"><?php echo ($item['ProTitle']); ?></label>
									<label class="proprice">￥<?php echo $item['PriceRange']?sprintf("%.2f",$item['PriceRange']):'0.00' ?></label>
									<img class="clickinfo" src="/Public/Sellermobile/icon/click_pro.png" alt="" onclick="clickinfo(this)">
								</div>
								<div class="editmenu">
									<div class="delpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="delpro(this)">
										<img src="/Public/Sellermobile/icon/del_pro.png" alt="">
										<label>删除</label>
									</div>
									<div class="shelvepro" data-pid="<?php echo ($item['ProId']); ?>" onclick="shelvepro(this)">
										<img src="/Public/Sellermobile/icon/xj_pro.png" alt="">
										<label>上架</label>
									</div>
									<div class="selfeditpro" data-pid="<?php echo ($item['ProId']); ?>" onclick="editpro(this)">
										<img src="/Public/Sellermobile/icon/edit_pro.png" alt="">
										<label>编辑</label>
									</div>
								</div>
							</div><?php endif; endif; endforeach; endif; endif; ?>
		</div>
	</div>
	<div class="btn_group">
		<div class="noedit">
			<label class="add_pro" data-url="<?php echo U('Products/proedit');?>">添加商品</label>
			<label class="magpro">批量管理</label>
		</div>
		<div class="yesedit">
			<label class="magdel_pro" onclick="magdelpro()">删除</label>
			<div class="mag_sel">
				<label class="sel_all" onclick="selmagallpro(this)" data-type="1">全选</label>
				<label class="sel_exit">取消</label>
			</div>
		</div>
	</div>
</div>
<!-- 批量操作菜单 -->
<div class="menulistconver">
	<div class="menulist">
		<label class="menuedit" style="display:none;">批量编辑</label>
		<label class="menuxj">批量下架</label>
		<label class="menudel">批量删除</label>
	</div>
</div>
<!-- 批量操作菜单end -->
<!-- 添加商品页面 -->
<div class="converaddpro">
	<div>
		<div class="selpro">
			<div>
				<a href="<?php echo U('Products/Factorypro');?>">
				<img src="/Public/Sellermobile/icon/add_factory.png" alt="">
				<label>工厂选货</label>
			</a>
			</div>
			<div>
				<a href="<?php echo U('Products/proedit');?>">
				<img src="/Public/Sellermobile/icon/add_self.png" alt="">
				<label>自营商品</label>
			</a>
			</div>
		</div>
		<div class="cancelpro">取消</div>
	</div>
</div>
<!-- 搜索页面 -->
<div class="searchconver">
	<div class="seachhaeard">
		<label class="qxsearch">取消</label>
		<div class="inputseach">
			<input id="inputseach" type="text" name="" value="" placeholder="输入商品名称">
			<img id="iconseach" src="/Public/Sellermobile/Icon/search.png" alt="">
		</div>
	</div>
	<div class="searchprolist">
	</div>
</div>
<!-- end搜索页面 -->

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