        //切换菜单显示
		function editMenuChange(id)
		{
			if(id=="specification")
			{
			  $('#editMenuDIV').show();	 
			}
			else
			{
			  $('#editMenuDIV').hide();	 
			}	
		}
		
		
		
		
		//简单固定格式的编辑器
		var basePart="<section class=\"specify\"><header>头部标题</header><div class=\"content\"><p>普通文字</p></div></section>";
		
		var baseText="<p>普通文字</p>";

		var tempObject=null;
		function addPart()
		{
			$('#specification-sku-undefined').append(basePart); //添加段落
			
			tempObject=$('#specification-sku-undefined').find('section:last-child header');
			bindMouseover(tempObject); //给段落标头绑定事件
			bindMouseout(tempObject);  //给段落标头绑定事件
			bindMouseclickSelect(tempObject,"edit"); //给段落标头绑定事件 edit
			
			tempObject=$('#specification-sku-undefined').find('section:last-child div p');
			
			bindMouseover(tempObject); //给段落内容div绑定事件
			bindMouseout(tempObject); //给段落内容div绑定事件
			bindMouseclickSelect($('#specification-sku-undefined').find('section:last-child div p'),"edit");
			
	    }
		
		function deletePart()
		{
			$('#specification-sku-undefined').find('section:last-child').remove();
			objectSelect=$('#specification-sku-undefined').find('section:last-child div');  //选择最后一个div
		}
		
		function addText()
		{
			$('#specification-sku-undefined').find('section:last-child div').append(baseText);
			bindMouseclickSelect($('#specification-sku-undefined').find('section:last-child div p'),"edit");
		}
		
		function deleteText()
		{
			$('#specification-sku-undefined').find('section:last-child div p').remove();
		}
		
		function subEdit()
		{
		   nowObject.html($('#editContext').val());
		   escEdit();
		}
		
		function escEdit()
		{
		   nowObject=null;
		   $('#editTextBoxDIV').slideUp("slow");
		   $('#editContext').val("");
		}
		
		function bindMouseover(object)
		{
			object.mouseover(function(e){
				//alert("xx");
				$(e.target).addClass("selectObj");	
				
			});
		}
		
		function bindMouseout(object)
		{
			object.mouseout(function(e){
			//alert("xx");
			    $(e.target).removeClass("selectObj");
			
			});
		}
		
		var nowObject=null;
		function bindMouseclickSelect(object,type)
		{
			object.click(function(e){
			     nowObject=$(e.target);
				 $('#editTextBoxDIV').slideDown("slow");
			});
		}
		//简单固定格式的编辑器
		
		//获取提交内容
		function getContext()
		{
			$('#subContextHidden').val($('#subContext').html());
		}
		//提交方法
		function subFrom()
		{
		    $('#subContext').submit();	
		}