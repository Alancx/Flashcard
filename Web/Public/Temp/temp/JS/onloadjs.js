function onloadjs(func){var oldonload=window.onload;if(typeof window.onload!='function'){window.onload = func;}else{window.onload=function(){oldonload();func();}}}
function $(id){return document.getElementById(id);}
function f(id,name){return id=="d"?document.getElementsByTagName(name):document.getElementById(id).getElementsByTagName(name);}
function u(a){return a.getAttribute("u");}
function uu(a,b){return a.setAttribute("u",b);}
function ee(a){return encodeURIComponent(a);}