function selectTag(showContent,selfObj){
	var tag=f($('tags'),'li');
	var taglength=tag.length;
	for(i=0;i<taglength;i++){
		tag[i].className="";
		var img=f($("tagContent"+i),"img");
		var imglength=img.length;
		for(j=0;j<imglength;j++){
			img[j].src=u(img[j]);
		}
		$("tagContent"+i).style.display="none";
	}
	selfObj.className="selectTag";
	$(showContent).style.display="block";
}
onloadjs(ini);
function ini(){
	var url = window.location.href;
	var strs= new Array();
	strs=url.split("#");
	if(strs[1] != null){
		selectTag(strs[1],f($("tags"),'li')[strs[2]]);
	}else{
		selectTag('tagContent0',f($("tags"),'li')[0]);	
	}
}