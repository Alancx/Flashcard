/*
========================================================================
LoadingBar 页面加载进度条
@auther LiuMing
@blog http://www.cnblogs.com/dtdxrk
demo 在body里填写需要加载的进度
LoadingBar.setWidth(number)
========================================================================
*/
var LoadingBar = {
    /*初始化*/
    init:function(){
        this.creatStyle();
        this.creatLoadDiv();
    },

    /*记录当前宽度*/
    width:0,

    /*页面里LoadingBar div*/
    oLoadDiv : false,

    /*开始*/
    setWidth : function(w){
        if(!this.oLoadDiv){this.init();}
        var oLoadDiv = this.oLoadDiv,
            width = Number(w) || 100;
        /*防止后面写入的width小于前面写入的width*/
        (width<this.width) ? width=this.width : this.width = width;
        oLoadDiv.className = 'animation_paused';
        oLoadDiv.style.width = width + '%';
        oLoadDiv.className = '';

        if(width === 100){this.over(oLoadDiv);}
    },

    /*页面加载完毕*/
    over : function(obj){
        setTimeout(function(){
            obj.style.display = 'none';
        },1000);
    },

    /*创建load条*/
    creatLoadDiv : function(){
        var div = document.createElement('div');
        div.id = 'LoadingBar';
        document.body.appendChild(div);
        this.oLoadDiv = document.getElementById('LoadingBar');
    },

    /*创建style*/
    creatStyle : function(){
        var nod = document.createElement('style'),   
            str = '#LoadingBar{transition:all 1s;-moz-transition:all 1s;-webkit-transition:all 1s;-o-transition:all 1s;background-color:#b91f1f;height: 3px;width:0; position: fixed;top: 0;z-index: 99999;left: 0;font-size: 0;z-index:9999999;_position:absolute;_top:expression(eval(document.documentElement.scrollTop));}.animation_paused{-webkit-animation-play-state:paused;-moz-animation-play-state:paused;-ms-animation-play-state:paused;animation-play-state:paused;};'
        nod.type = 'text/css';
        //ie下
        nod.styleSheet ? nod.styleSheet.cssText = str : nod.innerHTML = str; 
        document.getElementsByTagName('head')[0].appendChild(nod); 
    }
}