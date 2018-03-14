<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>光盘客</title>
    <meta name="keywords" content="徽记食品">
    <meta name="description" content="徽记食品">
    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    <script src="/Public/Admin/Admin/js/hplus.js"></script>

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/messages_zh.min.js"></script>
<script src="/Public/Plugins/Swiper/swiper-3.3.1.jquery.min.js"></script>
<link rel="stylesheet" href="/Public/Plugins/Swiper/swiper-3.3.1.min.css">
<script type="text/javascript" src="/Public/Admin/Admin/js/page.js?v=3.2"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/page.css?v=2.7">

<div class="row pagehomeset">
	<div class="col-lg-4 col-md-4 col-sm-4 pull-left page">
    <div class="showhome">
			<!-- 轮播图区域 -->
			<div class="swiper-container swiper-container-home">
				<div class="overburden"><div><span>轮播图设置区域,第一张为平台设置,最多设置两个(建议图片设置尺寸800x500)</span></div></div>
				<div class="swiper-wrapper">
					<?php if(!empty($homeimg)): ?><div class="swiper-slide">
							<img src="<?php echo ($PICURL); echo ($homeimg['ImgPath']); ?>" class="imgslide">
						</div>
						<?php else: ?>
						<div class="swiper-slide">
							<img src="http://placehold.it/800x500" class="imgslide">
						</div><?php endif; ?>
					<?php if(is_array($lbdata)): foreach($lbdata as $key=>$lbinfo): ?><div class="swiper-slide">
							<img src="<?php echo ($PICURL); echo ($lbinfo['ImgPath']); ?>" class="imgslide">
						</div><?php endforeach; endif; ?>
				</div>

				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination-home"></div>
			</div>
			<!-- 轮播图区域end -->
			<!-- 产品区域一 -->
		  <div class="propart">
		    <div class="pro-title">
					<label class="pro_hot">热销商品<span>更多</span></label>
		    </div>
		    <div class="pro-line-1">
					<div class="overburden"><div><span>热卖商品设置,第二个为平台设置,最多设置两个商品</span></div></div>
					<?php if(empty($selhotinfo[0])): ?><div class="pro-line-1-left hot-pro-1">
			        <img src="http://placehold.it/100x100" alt="">
							<label>商品名称<span>￥0.00</span></label>
			      </div>
						<?php else: ?>
						<div class="pro-line-1-left hot-pro-1">
			        <img src="<?php echo ($PICURL); echo ($selhotinfo[0]['ProLogoImg']); ?>" alt="">
							<label><?php echo ($selhotinfo[0]['ProName']); ?><span>￥<?php echo number_format(($selhotinfo[0]['PriceRange']),2); ?></span></label>
			      </div><?php endif; ?>
					<?php if(empty($prohot)): ?><div class="pro-line-1-right">
							<div>
								<label>商品名称</label>
								<label>￥0.00</label>
							</div>
							<img src="http://placehold.it/100x100" alt="">
						</div>
						<?php else: ?>
						<div class="pro-line-1-right">
							<div>
								<label><?php echo ($prohot['ProName']); ?></label>
								<label>￥<?php echo number_format(($prohot['PriceRange']),2); ?></label>
							</div>
							<img src="<?php echo ($PICURL); echo ($prohot['ProLogoImg']); ?>" alt="">
						</div><?php endif; ?>

					<?php if(empty($selhotinfo[1])): ?><div class="pro-line-1-right hot-pro-2">
							<div>
								<label>商品名称</label>
								<label>￥0.00</label>
							</div>
							<img src="http://placehold.it/100x100" alt="">
						</div>
						<?php else: ?>
						<div class="pro-line-1-right hot-pro-2">
							<div>
								<label><?php echo ($selhotinfo[1]['ProName']); ?></label>
								<label>￥<?php echo number_format(($selhotinfo[1]['PriceRange']),2); ?></label>
							</div>
							<img src="<?php echo ($PICURL); echo ($selhotinfo[1]['ProLogoImg']); ?>" alt="">
						</div><?php endif; ?>
		    </div>
		  </div>
			<div class="swiper-container swiper-container-class">
				<div class="overburden"><div><span>分类设置区域为平台上传，不可编辑</span></div></div>
				<div class="swiper-wrapper">
					<?php if(empty($info["classdata"])): $__FOR_START_10503__=1;$__FOR_END_10503__=5;for($i=$__FOR_START_10503__;$i < $__FOR_END_10503__;$i+=1){ ?><div class="swiper-slide">
								<div class="classtype">
									<img src="http://placehold.it/100x100" alt="">
								  <label>分类名称</label>
								</div>
							</div><?php } ?>
						<?php else: ?>
						<?php if(is_array($info["classdata"])): foreach($info["classdata"] as $key=>$classinfo): ?><div class="swiper-slide">
								<div class="classtype">
									<img src="<?php echo ($PICURL); echo ($classinfo['imgurl']); ?>" alt="">
								  <label><?php echo ($classinfo['cname']); ?></label>
								</div>
							</div><?php endforeach; endif; endif; ?>
				</div>
			</div>
		  <!-- 产品区域一end -->
		  <!-- 产品区域二 -->
		  <div class="propart">
		    <div class="pro-title">
		      <label class="pro_new">新品推荐<span>更多</span></label>
		    </div>
		    <div class="pro-line-3">
					<div class="overburden"><div><span>新品商品设置,(建议图片设置尺寸800x300,可以为空)</span></div></div>
				  <!-- <div class="new_pro_3">
				  	<img src="http://placehold.it/400x500" alt="">
				  </div>
					<div class="new_pro_3">
				  	<img src="http://placehold.it/400x500" alt="">
				  </div>
					<div class="new_pro_3">
				  	<img src="http://placehold.it/400x500" alt="">
				  </div>
					<div class="new_pro_3">
				  	<img src="http://placehold.it/400x500" alt="">
				  </div> -->
					<?php if(is_array($selnewinfo)): foreach($selnewinfo as $key=>$pi): ?><div class="new_pro_3">
					  	<img src="<?php echo ($PICURL); echo ($pi["prohomeimg"]); ?>" alt="">
					  </div><?php endforeach; endif; ?>

		    </div>
		  </div>
		  <!-- 产品区域二end -->
    </div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8 editpage">
    <div>
    <!-- 轮播图设置区域 -->
    <div class="editqy">
      <label>轮播图设置</label>
			<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>轮播图链接</label>
					<input type="text" name="lb_href" id="lb_href" class="form-control" placeholder="已“HTTP://”开头[可以为空]">
				</div>
      <div class="input-group">
        <input type="text" name="lb_img" id="lb_img" class="form-control">
        <div class="input-group-addon" onclick="upimg('lb_img')">上传</div>
        <div class="input-group-addon lbimgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
      </div>
      <table class="table tabel-hovered table-bordered table-lb" style="margin:5px 0px 0px 0px">
        <tbody>
					<?php if(is_array($lbdata)): foreach($lbdata as $vo=>$lbinfo): ?><tr id="LB<?php echo ($vo+1); ?>" class="lbimgurl" data-lbno="LB<?php echo ($vo+1); ?>" data-iurl="<?php echo ($lbinfo['ImgPath']); ?>" data-lbhref="<?php echo ($lbinfo['ImgUrl']); ?>" data-hid="<?php echo ($lbinfo['ID']); ?>">
								<td>LB<?php echo ($vo+1); ?></td>
								<td><?php echo ($lbinfo['ImgUrl']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($lbinfo['ImgPath']); ?>" alt="" style="width:150px;"/></td>
								<td><?php echo ($lbinfo['ImgPath']); ?></td>
								<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button><button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td>
							</tr><?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    <!-- 轮播图设置区域end -->
    <!-- 热卖设置区域 -->
    <div class="editqy">
			<label>热卖商品信息设置</label>
			<div class="input-group">
				<select name="pro-hot" id="pro-hot" class="form-control">
					<option value="">请选择</option>
					<?php if(is_array($hotproinfo)): foreach($hotproinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>" data-img="<?php echo ($pro["ProLogoImg"]); ?>" data-price="<?php echo ($pro["PriceRange"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
				</select>
        <div class="input-group-addon prohot_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
      </div>
			<table class="table tabel-hovered table-bordered table-pro-hot" style="margin:5px 0px 0px 0px">
        <tbody>
					<?php if(is_array($selhotinfo)): foreach($selhotinfo as $key=>$pi): ?><tr id="<?php echo ($pi["Position"]); ?>" class="hotproinfo" data-iurl="<?php echo ($pi["ProLogoImg"]); ?>" data-pid="<?php echo ($pi["ProId"]); ?>" data-pname="<?php echo ($pi["ProName"]); ?>" data-price="<?php echo ($pi["PriceRange"]); ?>" data-hid="<?php echo ($pi["ID"]); ?>">
							<td><?php echo ($pi["Position"]); ?></td>
							<td><?php echo ($pi["ProId"]); ?></td>
							<td><?php echo ($pi["ProName"]); ?></td>
							<td><?php echo ($pi["PriceRange"]); ?></td>
							<td><img src="<?php echo ($PICURL); echo ($pi["ProLogoImg"]); ?>" alt="" style="width:150px;"/></td>
							<td><?php echo ($pi["ProLogoImg"]); ?></td>
							<td style="width:50px;"><button type="button" class="btn btn-warning btn-xs" onclick="updatemsghome(this)">修改</button></td>
						</tr><?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    <!-- 热卖设置区域end -->
		<!-- 新品设置区域 -->
		<div class="editqy">
			<label>新品商品信息设置</label>
			<div class="input-group" style="margin-bottom:5px; width:100%">
				<select name="pro-new" id="pro-new" class="form-control">
					<option value="">请选择</option>
					<?php if(is_array($newproinfo)): foreach($newproinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>" data-img="<?php echo ($pro["ProLogoImg"]); ?>" data-price="<?php echo ($pro["PriceRange"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
				</select>
				<!-- <div class="input-group-addon pronew_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div> -->
			</div>
			<div class="input-group">
        <input type="text" name="new_img" id="new_img" class="form-control">
        <div class="input-group-addon" onclick="upimg('new_img')">上传</div>
        <div class="input-group-addon pronew_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
      </div>
			<table class="table tabel-hovered table-bordered table-pro-new" style="margin:5px 0px 0px 0px">
				<tbody>
					<?php if(is_array($selnewinfo)): foreach($selnewinfo as $key=>$pi): ?><tr id="NEW<?php echo ($key+1); ?>" class="newproinfo" data-iurl="<?php echo ($pi["prohomeimg"]); ?>" data-pid="<?php echo ($pi["ProId"]); ?>" data-pname="<?php echo ($pi["ProName"]); ?>" data-price="<?php echo ($pi["PriceRange"]); ?>" data-hid="<?php echo ($pi["ID"]); ?>" data-sore="<?php echo ($key); ?>">
							<td>NEW<?php echo ($key+1); ?></td>
							<td><?php echo ($pi["ProId"]); ?></td>
							<td><?php echo ($pi["ProName"]); ?></td>
							<td><?php echo ($pi["PriceRange"]); ?></td>
							<td><img src="<?php echo ($PICURL); echo ($pi["prohomeimg"]); ?>" alt="" style="width:150px;"/></td>
							<td><?php echo ($pi["prohomeimg"]); ?></td>
							<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button><button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td>
						</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
		<!-- 新品设置区域end -->
    <div style="height:50px;">
		<!-- 底部按钮菜单栏 -->
    <div class="bottom-btn">
    <button type="button" name="lookhome" id="lookhome" class="btn btn-warning">查看预览</button>
    <!-- <button type="button" name="savehome" id="savehome" class="btn btn-danger">保&emsp;&emsp;存</button> -->
    </div>
    <!-- 底部按钮菜单栏end -->
      </div>

	</div>
</div>
<script type="text/javascript">
  var savehomeurl="<?php echo U('Store/pageset');?>";//////保存地址/////
	var delhomeurl="<?php echo U('Store/delonhome');?>";//////删除地址/////
	var imgurl_href ="<?php echo ($PICURL); ?>";//图片地址
		//轮播图数据
		var swiper_home = new Swiper('.swiper-container-home', {
			autoplay: 3000,//可选选项，自动滑动
			pagination: '.swiper-pagination',
			paginationClickable: true,
			// autoHeight: true, //enable auto height
			autoplayDisableOnInteraction:false,
			loop:true,
		});
		var swiper_class = new Swiper('.swiper-container-class', {
			slidesPerView: 4,
			spaceBetween: 0,
			autoHeight: true, //enable auto height
			freeMode: true,
		});
</script>
<script type="text/javascript">
	function upimg(id){
		art.dialog.data('domid',id);
		art.dialog.open('<?php echo U('Upimg/spceil');?>');
	};
	function show(id){
		art.dialog({title:'图片预览',content:'<img src="<?php echo ($PICURL); ?>'+$('#'+id).val()+'" style="width:600px;min-width:50%;" />',lock:true,background: '#000',opacity: 0.45})
	}
</script>
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/layer/layer.min.js"></script><script>NProgress.done()</script></body></html>