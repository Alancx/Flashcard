ecWap.showError=function(a){if(a.code&&a.code=="login"){ecWap.alert("\u767b\u5f55\u8d85\u65f6\uff0c\u8bf7\u91cd\u65b0\u767b\u5f55");return false}ecWap.alert(a.msg||"\u7cfb\u7edf\u7e41\u5fd9")};ecWap.alert=function(b,a){ecWap.box(b,a)};ecWap.confirmDialog=function(b,a){ecWap.box(b,{isconfirm:true,onok:function(c){if(a){a(c)}}})};ecWap.loding=function(a){var b=a?a:'<div class="ecWap-dialog-content-loading"></div>';ecWap.box(b,{showTitle:false,button:false})};ecWap.lodingHide=function(){ecWap.box.close()};ecWap.console={warn:function(a){if(!window.console||!ecWap.debug){return}console.warn(a)},error:function(a){if(!window.console||!ecWap.debug){return}console.error(a)},log:function(a){if(!window.console||!ecWap.debug){return}console.log(a)}};var log=ecWap.console.log;ecWap.toggle=function(a,b){$("#"+b).toggle();$(a).attr("class",($(a).hasClass("icon-arrows-top")?"icon-arrows-down":"icon-arrows-top"))};ecWap.encodeScript=function(a){if(a&&""!=a){a=a.replace(new RegExp("&","gm"),"&amp;");a=a.replace(new RegExp(">","gm"),"&gt;");a=a.replace(new RegExp("<","gm"),"&lt;");a=a.replace(new RegExp('"',"gm"),"&quot;")}return a};ecWap.pkg("ecWap.account");ecWap.account={getInfo:function(a){new ecWap.ajax({type:"get",url:"/member/status.json?_t="+(new Date()).getTime(),timeout:10000,timeoutFunction:function(){if(null!=a){a()}},successFunction:function(b){if(b.success&&b.account){ecWap.account.id=b.account.id;ecWap.account.userId=b.account.userId;ecWap.account.name=b.account.name;ecWap.account.nickName=b.account.nickName;ecWap.account.loginName=b.account.loginName;ecWap.account.email=b.account.email;ecWap.account.mobile=b.account.mobile;ecWap.account.mobileStatus=b.account.mobileStatus;ecWap.account.accountType=b.account.accountType;ecWap.account.isBindMobile=b.isBindMobile;ecWap.account.isPriorityBuy=b.account.isPriorityBuy;ecWap.account.prioritySkuId=b.account.prioritySkuId}if(null!=a){a()}}})},isLogin:function(){return ecWap.account.id&&ecWap.account.name}};ecWap.pkg("ecWap.cart");ecWap.cart.setCartNum=function(a){$("#cartNum").html(a||0)};ecWap.pkg("ecWap.product");ecWap.product.inventory={_data:{},set:function(b,a){this._data[b]=a},haveInventory:function(a){return this._data[a]}};ecWap.track99click=function(b){var d;if(typeof(b)=="string"){d=b}else{var e=[],a;for(var c in b){a=b[c];e.push(c+"="+(ec.util.isArray(a)?a.join(";"):a))}d=e.join("&")}ec.track99click._ozuid=ec.account.id;ec.track99click._ozprm=d};ecWap.pkg("ecWap.cmb");ecWap.cmb.getQueryStringByName=function getQueryStringByName(b){var a=window.location.search.match(new RegExp("[?&]"+b+"=([^&]+)","i"));if(a==null||a.length<1){return""}return a[1]};window._gaq=window._gaq||[];_gaq.push(["_setAccount",(ecWap.debug?"":"UA-28046633-2"),"t1"]);var _hmt=_hmt||[];var _paq=_paq||[];ecWap.code={addShare:function(a){a=$.extend({type:"tools",lazy:true},a);document.write('<script type="text/javascript" id="bdshare_js" data="type='+a.type+'&amp;uid=4505950" ><\/script>');document.write('<script type="text/javascript" id="bdshell_js"><\/script>');window.bds_config={bdText:a.title};if(a.lazy){ec.ready(function(){document.getElementById("bdshell_js").src="http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion="+new Date().getHours()})}else{document.getElementById("bdshell_js").src="http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion="+new Date().getHours()}},addAnalytics:function(c){c=c||{google:false,cnzz:true,baidu:true,click99:true,hicloud:true,dmp:true};var f=[],b=location.href;for(var d=0;d<f.length;d+=1){if(b.indexOf(f[d])>0){return}}_gaq.push(["_trackPageview"]);_gaq.push(["_trackPageLoadTime"]);_gaq.push(["_addOrganic","baidu","word"]);_gaq.push(["_addOrganic","baidu","kw"]);_gaq.push(["_addOrganic","opendata.baidu","wd"]);_gaq.push(["_addOrganic","zhidao.baidu","word"]);_gaq.push(["_addOrganic","news.baidu","word"]);_gaq.push(["_addOrganic","post.baidu","kw"]);_gaq.push(["_addOrganic","tieba.baidu","kw"]);_gaq.push(["_addOrganic","mp3.baidu","word"]);_gaq.push(["_addOrganic","image.baidu","word"]);_gaq.push(["_addOrganic","top.baidu","word"]);_gaq.push(["_addOrganic","news.google","q"]);_gaq.push(["_addOrganic","soso","w"]);_gaq.push(["_addOrganic","image.soso","w"]);_gaq.push(["_addOrganic","music.soso","w"]);_gaq.push(["_addOrganic","post.soso","kw"]);_gaq.push(["_addOrganic","wenwen.soso","sp"]);_gaq.push(["_addOrganic","post.soso","kw"]);_gaq.push(["_addOrganic","3721","name"]);_gaq.push(["_addOrganic","114","kw"]);_gaq.push(["_addOrganic","youdao","q"]);_gaq.push(["_addOrganic","vnet","kw"]);_gaq.push(["_addOrganic","sogou","query"]);_gaq.push(["_addOrganic","news.sogou","query"]);_gaq.push(["_addOrganic","mp3.sogou","query"]);_gaq.push(["_addOrganic","pic.sogou","query"]);_gaq.push(["_addOrganic","blogsearch.sogou","query"]);_gaq.push(["_addOrganic","gougou","search"]);$.ajaxSetup({cache:true});if(c.google){$.getScript("http://www.google-analytics.com/ga.js",function(){log("google")})}if(c.baidu){$.getScript("http://hm.baidu.com/h.js?fe2b46caf2fee4e4b483b4c75f784be9",function(){log("baidu")})}if(c.dmp){$.getScript("http://dmp-collector.huawei.com/api/2.0/mDmpMapping.js",function(){log("dmp")})}if(c.hicloud){_paq.push(["setTrackerUrl","http://datacollect.vmall.com:28080/webv1"]);var a=((ecWap.order&&ecWap.order.orderId)?ecWap.order.orderId:"")+"";_paq.push(["setSiteId","m.vmall.com"]);_paq.push(["setCustomVariable",1,"cid",(ecWap.cookie.get("cps_id")||""),"page"]);_paq.push(["setCustomVariable",2,"direct",(ecWap.cookie.get("cps_direct")||""),"page"]);_paq.push(["setCustomVariable",3,"orderid",a,"page"]);_paq.push(["setCustomVariable",4,"wi",(ecWap.cookie.get("cps_wi")||""),"page"]);_paq.push(["setCustomVariable",1,"uid",((ecWap.account?ecWap.account.id:"")||""),"visit"]);_paq.push(["setCustomVariable",10,"uid",((ecWap.account?ecWap.account.userId:"")||""),"visit"]);_paq.push(["trackPageView"]);$.getScript("http://res.vmallres.com/bi/hianalytics.js",function(){log("hicloud")});var e=(ecWap.cookie.get("cps_direct")?ecWap.cookie.get("cps_direct"):"");if(undefined==e){e=""}ecWap.cookie.set("cps_orderid",a,{path:"/",domain:".vmall.com"});ecWap.cookie.set("cps_direct",e,{path:"/",domain:".vmall.com"})}}};$(function(){ecWap.account.getInfo();var l=ecWap.cmb.getQueryStringByName("type");var h=ecWap.cmb.getQueryStringByName("cid");var a=ecWap.cmb.getQueryStringByName("wi");ecWap.cookie.set("cps_id",h,{path:"/",domain:".vmall.com"});ecWap.cookie.set("cps_wi",a,{path:"/",domain:".vmall.com"});var b=window.location;if(b.toString().indexOf("/index")!=-1){var m=ecWap.cookie.get("type");if(!m){var j={path:"/",domain:".vmall.com"};if((l=="1"||l=="2")){ecWap.cookie.set("type",l,j)}else{if(l!=""){ecWap.cookie.set("type",null,j)}}}}$("input[type=checkbox],input[type=radio]").click(function(){var c=this.className,i=this;this.className=c+" active";setTimeout(function(){i.className=i.className.replace(" active","")},300)});var k=window.location.search,e={};if(k){var d;k=k.substring(1).split("&");for(var g=0;g<k.length;g++){d=k[g].split("=");if(d.length==2){e[d[0]]=d[1]}}switch(e.name){case"loginError":log("loginError");break}}setTimeout(function(){$("#button-area-2").css("position","fixed").show()},200);var f=false;$(window).bind("scroll",function(){if(!f){$("#button-area-2").css("position","fixed");f=true}});setTimeout(function(){ecWap.code.addAnalytics()},3000)});
