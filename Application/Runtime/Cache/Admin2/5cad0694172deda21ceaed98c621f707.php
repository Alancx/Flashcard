<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>进度</title>
		<link href="/Public/note/css/mui.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/note/css/jindu.css" />
	</head>
	<body>
		<div class="top">
			<div class="left">
				<b>工作进度</b>
			<div class="xiala">
				<div class="" style="font-size:16px;width:150px;line-height:20px;">
						<span><?php echo ($title); ?></span>

					
					<!-- <select name="" style="border:0px !important;" class="seltype">
						<?php if(is_array($weeklist)): foreach($weeklist as $key=>$w): ?><option data-year="<?php echo ($w["year"]); ?>" data-week="<?php echo ($w["week"]); ?>"><?php echo ($w['year']); ?>年<?php echo ($w['week']); ?>周</option><?php endforeach; endif; ?>					
					</select>
					<span class="aa"></span> -->
				</div>
			</div>
			</div>
			
			<div class="" id="pie" >
					
			</div>
		</div>
		<div class="ssss">
			<?php if(is_array($result)): foreach($result as $key=>$list): ?><div class="renlist">
				<div class="ren" value="true">
				<div class="img"><img src="<?php echo ($list['pic']); ?>"/></div>
				<a ><?php echo ($list['name']); ?></a>
				<span class="completeing">0%</span>
				<div class="pp">
					<p class="mui-icon mui-icon-arrowup"></p>
				</div>	
			</div>
			<?php if(is_array($list['list'])): foreach($list['list'] as $key=>$row): if($row['state'] == '2'): ?><div class="complete" data-state="1">
				<div class="complete-text">
					<?php echo ($row['content']); ?>
				</div>
				<div class="ppp"><span class="mui-icon mui-icon-checkmarkempty" style="color:rgb(109,251,175);"></span></div>
				</div>
				<?php else: ?>
				<div class="complete">
				<div class="complete-text">
					<?php echo ($row['content']); ?>
				</div>
				</div>
				<!-- <div class="ppp"><span class="mui-icon mui-icon-checkmarkempty"></span></div> --><?php endif; endforeach; endif; ?>
			</div><?php endforeach; endif; ?>
		</div>
		
	</body>
	<script src="/Public/note/js/mui.min.js"></script>
	<script src="/Public/note/js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/note/js/echarts.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		mui.init();
	</script>
	<script type="text/javascript">
		$(document).on("tap", ".ren", function() {
			var value=$(this).attr('value');
			// console.log(value);
			if(value=='true'){
				$(this).parents('.renlist').find('.complete').slideToggle(200);
				$(this).parents('.renlist').find('p').removeClass('mui-icon mui-icon-arrowup').addClass('mui-icon mui-icon-arrowdown');
				$(this).attr('value','false');
			}else{
				$(this).parents('.renlist').find('.complete').slideToggle(200);
				$(this).parents('.renlist').find('p').removeClass('mui-icon mui-icon-arrowdown').addClass('mui-icon mui-icon-arrowup');
				$(this).attr('value','true');
			}
			
		})
	</script>
	<script>
	var myChart=null;
	$(document).ready(function(){
		myChart = echarts.init(document.getElementById('pie'));
		//获取人员百分比
		$('.renlist').each(function(index,item){
			var allcount=$(item).find('.complete').length;
			var hascount=$(item).find('.complete[data-state=1]').length;
			var complete=parseInt(hascount / allcount * 100);
			$(item).find('.completeing').text(complete+'%');
		});
		//总数百分比
		var conut=$('.complete').length;
		var hadcount=$('.complete[data-state=1]').length;
		var allcomplete=parseInt(hadcount / conut * 100);
		var chartoption = setchartoption(allcomplete,(100-allcomplete));
			myChart.setOption(chartoption);

	})
	</script>
	<script type="text/javascript">
	
		function setchartoption(a,b){
			var option = {
			"title": {
				"text": '进度',
				"top": '55%',
				"left": '40%',
				"textStyle": {
					"fontSize": 12,
					"fontWeight": "bold",
					"color": "#bcbfff"
				}
			},
			"tooltip": {
				"trigger": 'item',
				"formatter": "{a} : ({d}%)"
			},
			"series": [{
				"name": "工作进度",
				"center": [
					"50%",
					"50%"
				],
				"radius": [				
					"70%",
					"70%",
				],
				"clockWise": false,
				"hoverAnimation": false,
				"type": "pie",
				"data": [{
					"value": a,
					"name": "",
					"label": {
						"normal": {
							"show": true,
							"formatter": '{d} %',
							"textStyle": {
								"fontSize": 14,
								"fontWeight": "bold"
							},
							"position": "center"
						}
					},
					"labelLine": {
						"show": false
					},
					"itemStyle": {
						"normal": {
							"color": "#5886f0",
							"borderColor": new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
								offset: 0,
								color: '#00a2ff'
							}, {
								offset: 1,
								color: '#70ffac'
							}]),
							"borderWidth": 10
						},
						"emphasis": {
							"color": "#5886f0",
							"borderColor": new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
								offset: 0,
								color: '#85b6b2'
							}, {
								offset: 1,
								color: '#6d4f8d'
							}]),
							"borderWidth": 25
						}
					},
				}, {
					"name": " ",
					"value": b,
					"itemStyle": {
						"normal": {
							"label": {
								"show": false
							},
							"labelLine": {
								"show": false
							},
							"color": 'rgba(0,0,0,0)',
							"borderColor": 'rgba(0,0,0,0)',
							"borderWidth": 0
						},
						"emphasis": {
							"color": 'rgba(0,0,0,0)',
							"borderColor": 'rgba(0,0,0,0)',
							"borderWidth": 0
						}
					}
				}]
			}, {
				"name": "工作进度",
				"center": [
					"50%",
					"50%"
				],
				"radius": [
					"90%",
					"90%"
				],
				"clockWise": false,
				"hoverAnimation": false,
				"type": "pie",
				"data": [{
					"value": a,
					"name": "",
					"label": {
						"normal": {
							"show": true,
							"formatter": '{d} %',
							"textStyle": {
								"fontSize": 14,
								"fontWeight": "bold"
							},
							"position": "center"
						}
					},
					"labelLine": {
						"show": false
					},
					"itemStyle": {
						"normal": {
							"color": "#5886f0",
							"borderColor": new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
								offset: 0,
								color: '#00a2ff'
							}, {
								offset: 1,
								color: '#70ffac'
							}]),
							"borderWidth": 1
						},
						"emphasis": {
							"color": "#5886f0",
							"borderColor": new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
								offset: 0,
								color: '#85b6b2'
							}, {
								offset: 1,
								color: '#6d4f8d'
							}]),
							"borderWidth": 1
						}
					},
				}, {
					"name": "未完成",
					"value": b,
					"itemStyle": {
						"normal": {
							"label": {
								"show": false
							},
							"labelLine": {
								"show": false
							},
							"color": 'rgba(0,0,0,0)',
							"borderColor": 'rgba(0,0,0,0)',
							"borderWidth": 0
						},
						"emphasis": {
							"color": 'rgba(0,0,0,0)',
							"borderColor": 'rgba(0,0,0,0)',
							"borderWidth": 0
						}
					}
				}]
			}]
		};
		return option;
		}		
	</script>
	<script>
	// 	$('.seltype').change(function(){
	// 		var weeks=$(this).children('option:selected').attr('data-week');
	// 		var year=$(this).children('option:selected').attr('data-year');
	// 		var senddata = {
	// 			weeks:weeks,
	// 			year:year,
	// 		}
	// 		$.ajax({
	// 			url:"<?php echo U('Index/addtime');?>",
	// 			type:"post",
	// 			data:senddata,
	// 			dataType:"json",
	// 			success:function(msg){
	// 				var _html='';
	// 				var list=msg['info'];
	// 				if(msg['status']=='true'){
	// 					$.each(list,function(index,item){
	// 					var _htmls="";
	// 					var row=item['list'];
	// 					$.each(row,function(i,it){							
	// 						if(it['state']=='2'){
	// 						 _htmls+='<div class="complete" data-state="1">'+
	// 								'<div class="complete-text">'+it['content']+
	// 								'</div>'+
	// 								'<div class="ppp"><span class="mui-icon mui-icon-checkmarkempty" style="color:rgb(109,251,175);"></span></div>'+
	// 								'</div>';
	// 						}else{
	// 						_htmls+='<div class="complete">'+
	// 							'<div class="complete-text">'+it['content']+
	// 							'</div>'+
	// 							'</div>';
	// 						}				
										
	// 					});
	// 					 _html+='<div class="renlist">'+
	// 						'<div class="ren">'+
	// 						'<div class="img"><img src="'+item['pic']+'"/></div>'+
	// 						'<a >'+item['name']+'</a>'+
	// 						'<span class="completeing">0%</span>'+
	// 						'<div class="pp">'+
	// 							'<p class="mui-icon mui-icon-arrowup"></p>'+
	// 						'</div>	'+
	// 						'</div>'+
	// 							_htmls+
	// 						'</div>';
	// 				})	
	// 				$('.ssss').html(_html);
	// 				$('.renlist').each(function(index,item){
	// 					//获取人员百分比
	// 					var allcount=$(item).find('.complete').length;
	// 					var hascount=$(item).find('.complete[data-state=1]').length;
	// 					var complete=parseInt(hascount / allcount * 100);
	// 					$(item).find('.completeing').text(complete+'%');
	// 				});
	// 				//总数百分比
	// 				var conut=$('.complete').length;
	// 				var hadcount=$('.complete[data-state=1]').length;
	// 				var allcomplete=parseInt(hadcount / conut * 100);
	// 				var chartoption = setchartoption(allcomplete,(100-allcomplete));
	// 					myChart.setOption(chartoption);
	// 				}else{
	// 					$('.ssss').html('');
	// 					var chartoption = setchartoption(100,0);
	// 					myChart.setOption(chartoption);
	// 				}
					

	// 			}
	// 		})
	// 	})
	</script>
</html>