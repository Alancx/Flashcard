		function changeTag(a,b)
		{
			$(window).scrollTop(0);
			$(".tab").hide();
			$("#"+b).show();
			$(a).parent().closest("ul").find("a").removeClass("current");
			$(a).addClass("current");
		}
		
		function changeComment(b,a)
		{
			$("#"+b+"-area").parent().closest(".content").find("ul").hide();
			$("#"+b+"-area").show();$(a).parent().closest("ul").find("li").removeClass("current");
			$(a).parent().addClass("current");
		}
		
		function checkQuantity()
		{
		
			var reg = new RegExp("^[1-9]*$");  
			var quantityObj= $('#quantity');
			if(!reg.test(quantityObj.val())){  
			   quantityObj.val("1");  
			}  
		}
		
		function delPrdNum()
		{
			var quantityObj= $('#quantity');
			if(parseInt(quantityObj.val())>1)
			{
				quantityObj.val(parseInt(quantityObj.val())-1);
			}
			
		}
		
		function addPrdNum()
		{
			var quantityObj= $('#quantity');
			quantityObj.val(parseInt(quantityObj.val())+1);
		}
		
		function selectAttr(parentIndex,index,data)
		{
		//selected
		    var dataObj=$("#Arrt"+parentIndex);
			
		    var ddObjs=$("#dl"+parentIndex).find("dd");
			
			var aObj=$("#AttrA"+parentIndex+"-"+index);
			
			for(var i=0;i<ddObjs.length;i++)
			{
			   if($(ddObjs[i]).find("a").attr('id')=="AttrA"+parentIndex+"-"+index)	
			   {
				   $(ddObjs[i]).find("a").addClass("selected");
			   }
			   else
			   {
				   $(ddObjs[i]).find("a").removeClass("selected");   
				}
			  
		    }
			
			dataObj.val(data);
	    }
		
		function changeArrtClass(obj)
		{
			alert($(obj.target).find("span").html());
	    }
		
		
		
		
		