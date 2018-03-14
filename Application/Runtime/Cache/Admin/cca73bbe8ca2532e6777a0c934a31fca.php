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
    <link href="/Public/Admin/Admin/css/newstyle.css?v=2.1" rel="stylesheet">
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/common/static/nprogress.js"></script>
    </head><body><div id="wrapper">

<script type="text/javascript" src="/Public/Admin/artDialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/Public/Admin/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Admin/Admin/js/plugins/validate/messages_zh.min.js"></script>
<script src="/Public/Plugins/Swiper/swiper-3.3.1.jquery.min.js"></script>
<link rel="stylesheet" href="/Public/Plugins/Swiper/swiper-3.3.1.min.css">
<script type="text/javascript" src="/Public/Admin/Admin/js/mallpageset.js?v=3.5"></script>
<link rel="stylesheet" href="/Public/Admin/Admin/css/mallpageset.css?v=4.1">

<div class="row pagehomeset">
	<div class="col-lg-4 col-md-4 col-sm-4 pull-left page">
		<div class="showhome">
			<!-- 轮播图区域 -->

			<div class="swiper-container swiper-container-home">
				<div class="overburden"><div><span>轮播图设置区域,最少一个,最多设置三个(建议图片设置尺寸800x500)</span></div></div>
				<div class="swiper-wrapper">
					<?php if(empty($info["lbdata"])): ?><div class="swiper-slide">
							<img src="http://placehold.it/800x500" class="imgslide">
						</div>
						<?php else: ?>
						<?php if(is_array($info["lbdata"])): foreach($info["lbdata"] as $key=>$lbinfo): ?><div class="swiper-slide">
								<img src="<?php echo ($PICURL); echo ($lbinfo['imgurl']); ?>" class="imgslide">
							</div><?php endforeach; endif; endif; ?>
				</div>
				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination-home"></div>
			</div>
			<!-- 轮播图区域end -->
			<!-- 分类区域end -->
			<div class="swiper-container swiper-container-class">
				<div class="overburden"><div><span>分类设置区域,最少四个(建议图片设置尺寸100x100)</span></div></div>
				<div class="swiper-wrapper">
					<?php if(empty($info["classdata"])): $__FOR_START_30105__=1;$__FOR_END_30105__=5;for($i=$__FOR_START_30105__;$i < $__FOR_END_30105__;$i+=1){ ?><div class="swiper-slide">
								<div class="classtype">
									<img src="http://placehold.it/100x100" alt="">
								  <label>分类名称</label>
								</div>
							</div><?php } ?>
						<?php else: ?>
						<?php if(is_array($info["classdata"])): foreach($info["classdata"] as $key=>$classinf): ?><div class="swiper-slide">
								<div class="classtype">
									<img src="<?php echo ($PICURL); echo ($classinf['imgurl']); ?>" alt="">
								  <label><?php echo ($classinf['cname']); ?></label>
								</div>
							</div><?php endforeach; endif; endif; ?>
				</div>
			</div>
			<!-- 分类区域end -->
			<!-- 首页活动banner图 -->
			<div class="home_activity">
				<div class="overburden"><div><span>活动banner设置区域,最少一个(建议图片设置尺寸800x250)</span></div></div>
				<a>
					<?php if(empty($info["hclassdata"])): ?><img src="http://placehold.it/800x250" alt="">
						<?php else: ?>
						<img src="<?php echo ($PICURL); echo ($info['hclassdata']['0']['imgurl']); ?>" alt=""><?php endif; ?>
				</a>
			</div>
			<!-- 首页banner图end -->
			<!-- 产品区域一 -->
			<div class="product_1">
				<div class="overburden"><div><span>产品区域一设置区域,最少四个,最多设置六个(建议图片设置尺寸800x300)</span></div></div>
				<?php if(empty($info["oneprodata"])): $__FOR_START_24670__=1;$__FOR_END_24670__=5;for($i=$__FOR_START_24670__;$i < $__FOR_END_24670__;$i+=1){ ?><div class="proinfo_1">
							<a>
								<img src="http://placehold.it/800x300" alt="">
							</a>
						</div><?php } ?>
					<?php else: ?>
					<?php if(is_array($info["oneprodata"])): foreach($info["oneprodata"] as $key=>$proinfos): ?><div class="proinfo_1">
							<a>
								<img src="<?php echo ($PICURL); echo ($proinfos['imgurl']); ?>" alt="">
							</a>
						</div><?php endforeach; endif; endif; ?>
			</div>
			<!-- 产品区域一end -->
			<!-- 产品区域二 -->
			<div class="product_2">
				<div class="overburden"><div><span>产品区域二设置区域,最少四个(建议图片设置尺寸400x500)</span></div></div>
				<?php if(empty($info["twoprodata"])): $__FOR_START_23615__=1;$__FOR_END_23615__=5;for($i=$__FOR_START_23615__;$i < $__FOR_END_23615__;$i+=1){ ?><div class="proinfo_2">
							<a>
								<img src="http://placehold.it/400x500" alt="">
							</a>
						</div><?php } ?>
					<?php else: ?>
					<?php if(is_array($info["twoprodata"])): foreach($info["twoprodata"] as $key=>$proinfos): ?><div class="proinfo_2">
							<a>
								<img src="<?php echo ($PICURL); echo ($proinfos['imgurl']); ?>" alt="">
							</a>
						</div><?php endforeach; endif; endif; ?>
			</div>

			<!-- 产品区域二end -->
			<div style="height:10px;"></div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8 editpage">
		<div>
			<!-- 首页引导图 -->
			<div class="editqy">
				<label>首页引导图设置</label>
				<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>引导图链接</label>
					<input type="text" name="boot_href" id="boot_href" class="form-control" placeholder="已“HTTP://”开头[可以为空]">
				</div>
				<div class="input-group">
					<input type="text" name="boot_img" id="boot_img" class="form-control">
					<div class="input-group-addon" onclick="upimg('boot_img')">上传</div>
					<div class="input-group-addon bootimgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
				</div>
				<table class="table tabel-hovered table-bordered table-boot" style="margin:5px 0px 0px 0px">
					<tbody>
							<tr class="bootimgurl" data-iurl="<?php echo ($info['bootinfo']['imgurl']); ?>" data-boothref="<?php echo ($info['bootinfo']['boothref']); ?>">
								<td>BOOT1</td>
								<td><?php echo ($info['bootinfo']['boothref']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($info['bootinfo']['imgurl']); ?>" alt="" style="width:150px;"/></td>
								<td><?php echo ($info['bootinfo']['imgurl']); ?></td>
							</tr>
					</tbody>
				</table>
			</div>
			<!-- 首页引导图end -->
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
						<?php if(is_array($info["lbdata"])): foreach($info["lbdata"] as $key=>$lbinfo): ?><tr class="lbimgurl" data-lbno="<?php echo ($lbinfo['lbno']); ?>" data-iurl="<?php echo ($lbinfo['imgurl']); ?>" data-lbhref="<?php echo ($lbinfo['lbhref']); ?>">
								<td><?php echo ($lbinfo['lbno']); ?></td>
								<td><?php echo ($lbinfo['lbhref']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($lbinfo['imgurl']); ?>" alt="" style="width:150px;"/></td>
								<td><?php echo ($lbinfo['imgurl']); ?></td>
								<td style="width:50px;"><button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button><button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button></td>
							</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
			<!-- 轮播图设置区域end -->
			<!-- 分类设置区域 -->

			<div class="editqy">
				<label>分类信息设置</label>
				<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>分类信息</label>
					<select name="pro-class" id="pro-class" class="form-control">
						<option value="">请选择</option>
						<?php if(is_array($classinfo)): foreach($classinfo as $key=>$class): ?><option value="<?php echo ($class["ClassId"]); ?>" data-pid="<?php echo ($class["ParentClassId"]); ?>"><?php echo ($class["ClassName"]); ?></option><?php endforeach; endif; ?>
					</select>
				</div>
				<div class="input-group">
					<input type="text" name="class_img" id="class_img" class="form-control" >
					<div class="input-group-addon" onclick="upimg('class_img')">上传</div>
					<div class="input-group-addon class_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
				</div>
				<table class="table tabel-hovered table-bordered table-class" style="margin:5px 0px 0px 0px">
					<tbody>
						<?php if(is_array($info["classdata"])): foreach($info["classdata"] as $key=>$classinfo): ?><tr class="classinfo" id="CL<?php echo ($key+1); ?>" data-iurl="<?php echo ($classinfo['imgurl']); ?>" data-cid="<?php echo ($classinfo['cid']); ?>" data-cname="<?php echo ($classinfo['cname']); ?>" data-pid="<?php echo ($classinfo['pid']); ?>">
								<td>CL<?php echo ($key+1); ?></td>
								<td><?php echo ($classinfo['cid']); ?></td>
								<td><?php echo ($classinfo['cname']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($classinfo['imgurl']); ?>" alt="" style="width:150px;"></td>
								<td><?php echo ($classinfo['imgurl']); ?></td>
								<td style="width:50px;">
									<button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button>
									<button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button>
								</td>
							</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
			<!-- 分类设置区域end -->
			<!-- 活动分类设置 -->
			<div class="editqy">
				<label>活动banner设置</label>
				<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>活动链接</label>
					<input type="text" name="hbbanner_href" id="hbbanner_href" class="form-control" placeholder="已“HTTP://”开头[可以为空]">
				</div>
				<div class="input-group">
					<input type="text" name="hclass_img" id="hclass_img" class="form-control">
					<div class="input-group-addon" onclick="upimg('hclass_img')">上传</div>
					<div class="input-group-addon hclass_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
				</div>
				<table class="table tabel-hovered table-bordered table-hclass" style="margin:5px 0px 0px 0px">
					<tbody>
						<tr class="hclassinfo" data-iurl="<?php echo ($info['hclassdata'][0]['imgurl']); ?>" data-href="<?php echo ($info['hclassdata'][0]['imghref']); ?>">
							<td>HR0</td>
							<td><?php echo ($info['hclassdata'][0]['imghref']); ?></td>
							<td><img src="<?php echo ($PICURL); echo ($info['hclassdata'][0]['imgurl']); ?>" alt="" style="width:150px;"/></td>
							<td><?php echo ($info['hclassdata'][0]['imgurl']); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- 活动分类设置end -->
			<!-- 商品区域一信息设置 -->
			<div class="editqy">
				<label>商品区域一信息设置</label>
				<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>商品信息</label>
					<select name="pro-one" id="pro-one" class="form-control">
						<option value="">请选择</option>
						<?php if(is_array($proinfo)): foreach($proinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
					</select>
				</div>
				<div class="input-group">
					<input type="text" name="proone_img" id="proone_img" class="form-control" >
					<div class="input-group-addon" onclick="upimg('proone_img')">上传</div>
					<div class="input-group-addon proone_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
				</div>
				<table class="table tabel-hovered table-bordered table-pro-one" style="margin:5px 0px 0px 0px">
					<tbody>
						<?php if(is_array($info["oneprodata"])): foreach($info["oneprodata"] as $key=>$proinfos): ?><tr class="oneproinfo" id="PRO-1-<?php echo ($key+1); ?>" data-iurl="<?php echo ($proinfos['imgurl']); ?>" data-pid="<?php echo ($proinfos['pid']); ?>" data-pname="<?php echo ($proinfos['pname']); ?>">
								<td>PRO<?php echo ($key+1); ?></td>
								<td><?php echo ($proinfos['pid']); ?></td>
								<td><?php echo ($proinfos['pname']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($proinfos['imgurl']); ?>" alt="" style="width:150px;"></td>
								<td><?php echo ($proinfos['imgurl']); ?></td>
								<td style="width:50px;">
									<button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button>
									<button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button>
								</td>
							</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
			<!-- 商品区域一信息设置end -->
			<!-- 商品区域二信息设置 -->
			<div class="editqy">
				<label>商品区域二信息设置</label>
				<div class="seltype" style="margin:0px 0px 5px 0px;">
					<label>商品信息</label>
					<select name="pro-two" id="pro-two" class="form-control">
						<option value="">请选择</option>
						<?php if(is_array($proinfo)): foreach($proinfo as $key=>$pro): ?><option value="<?php echo ($pro["ProId"]); ?>"><?php echo ($pro["ProName"]); ?></option><?php endforeach; endif; ?>
					</select>
				</div>
				<div class="input-group">
					<input type="text" name="protwo_img" id="protwo_img" class="form-control" >
					<div class="input-group-addon" onclick="upimg('protwo_img')">上传</div>
					<div class="input-group-addon protwo_imgsave" style="background-color:#ec4758;color:#ffffff" >保存</div>
				</div>
				<table class="table tabel-hovered table-bordered table-pro-two" style="margin:5px 0px 0px 0px">
					<tbody>
						<?php if(is_array($info["twoprodata"])): foreach($info["twoprodata"] as $key=>$proinfos): ?><tr class="twoproinfo" id="PRO-2-<?php echo ($key+1); ?>" data-iurl="<?php echo ($proinfos['imgurl']); ?>" data-pid="<?php echo ($proinfos['pid']); ?>" data-pname="<?php echo ($proinfos['pname']); ?>">
								<td>PRO<?php echo ($key+1); ?></td>
								<td><?php echo ($proinfos['pid']); ?></td>
								<td><?php echo ($proinfos['pname']); ?></td>
								<td><img src="<?php echo ($PICURL); echo ($proinfos['imgurl']); ?>" alt="" style="width:150px;"></td>
								<td><?php echo ($proinfos['imgurl']); ?></td>
								<td style="width:50px;">
									<button type="button" class="btn btn-danger btn-xs" onclick="delmsghome(this)">删除</button>
									<button type="button" class="btn btn-warning btn-xs btn-update" onclick="updatemsghome(this)">修改</button>
								</td>
							</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
			<!-- 商品区域二信息设置end -->
			<!-- 底部按钮菜单栏 -->
			<div class="bottom-btn">
				<button type="button" name="lookhome" id="lookhome" class="btn btn-warning">查看预览</button>
				<button type="button" name="savehome" id="savehome" class="btn btn-danger">保&emsp;&emsp;存</button>
			</div>
			<!-- 底部按钮菜单栏end -->
		</div>

	</div>
</div>
<script type="text/javascript">
var savehomeurl="<?php echo U('BaseSetting/pageset');?>";
var imgurl_href = "<?php echo ($PICURL); ?>";
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
</div></div></div><script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script><script src="/Public/Admin/Admin/js/plugins/metisMenu/jquery.metisMenu.js"></script><script src="/Public/Admin/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><script src="/Public/Admin/Admin/js/plugins/peity/jquery.peity.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jquery-ui/jquery-ui.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script><script src="/Public/Admin/Admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script><script src="/Public/Admin/Admin/js/plugins/easypiechart/jquery.easypiechart.js"></script><script src="/Public/Admin/Admin/js/plugins/sparkline/jquery.sparkline.min.js"></script><script>NProgress.done()</script></body></html>