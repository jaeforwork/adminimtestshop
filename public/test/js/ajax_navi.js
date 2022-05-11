/**
 * 
 */

"use strict";
var ajaxNavi=new (function(wrapperId){
	var pageLoading=false;
	var navi=this;
	var current_url;
	var current_sv=_getCookie("sv");
	function getPage(url){
		if (history.pushState) {
			if(pageLoading){
				return;
			}
			pageLoading=true;
		
			$.get(url,function(res){
				window.csrf_val=_getCookie(window.csrf_name);
				var pageData=$(res);			
				
				$(wrapperId).replaceWith(pageData);
	
				var curUrl=location.pathname+location.search;
				//setTimeout(function(){console.log(wrapperId+" "+pageData.attr("page-title"));},1000);
				
				document.title=pageData.data("page-title");
				
				if(url!=curUrl){
					
					var stateObj={"url":url};
					//history.pushState(stateObj, document.title, url);
					navi.pushHistory(stateObj, document.title, url);
				}
				$(window).scrollTop(0);
				pageLoading=false;
				current_url=url;
				
			}).fail(function(){pageLoading=false;});
		}else{
			location.assign(url);
			current_url=url;
		}
	}
	
	function init(){
		$(document).on("click",".link-silence",function(){
			
			var sv=_getCookie("sv");			
			if(sv && current_sv!=sv){
				return true;
			}
			var url=$(this).attr("href");			
			getPage(url);
			return false;
		});
		if(history.pushState){
			
			var stateObj={"url":location.pathname+location.search};
			history.pushState(stateObj, document.title, location.pathname+location.search);
		}
	}
	
	window.addEventListener ? addEventListener("load", init, false) : window.attachEvent ? attachEvent("onload", init) : (onload = init);
	window.addEventListener("popstate", function(e) { 
		console.log(e);
		//history.back();
		//history.pushState(null, null, window.location.pathname);
		if(e.state && e.state.url){
			//var curUrl=location.pathname+location.search;
			
			var url=e.state.url;	
			if(current_url==url){
				
				return ;
			}
			getPage(url);return ;
		}else{
			history.back();
		}
		
	}, false);
	
	this.formNavi=function(formId,url){
		var param="";
		$("#"+formId+" input, #"+formId+" select").each(function(){
			var $p=$(this);
			if($p.val()!=""){			
				if(param==""){
					param="?"+$p.attr("name")+"="+$p.val();
				}else{
					param +="&"+$p.attr("name")+"="+$p.val();
				}
			}
		});
		getPage(url+param);
	};
	
	this.navi=function(url){
		getPage(url);
	}
	this.pushHistory=function(stateObj, title, url){
		history.pushState(stateObj, document.title, url);
	}
	
	
	function _getCookie(cName) {
	    cName = cName + '=';
	    var cookieData = document.cookie;
	    var start = cookieData.indexOf(cName);
	    var cValue = '';
	    if(start != -1){
	        start += cName.length;
	        var end = cookieData.indexOf(';', start);
	        if(end == -1)end = cookieData.length;
	        cValue = cookieData.substring(start, end);
	    }
	    return unescape(cValue);
	}
	
})("#wrap");