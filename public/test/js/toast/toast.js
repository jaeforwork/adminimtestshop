/**
 * 
 */

var Toast=function(msg,options){
	var timestamp="1";
	defualtOptions={timeOut:1000, callback:function(){}, ok:null, cancel:null, modal:false};
	this.msg=msg;
	
	this.options=options || defualtOptions;	
	this.id="toast_msg_"+(Math.floor(Math.random() * 100000000) + 100000000);
	this.timeOut=this.options.timeOut || defualtOptions.timeOut;
	this.callback=this.options.callback || defualtOptions.callback;
	this.ok=this.options.ok || defualtOptions.ok;
	this.cancel=this.options.cancel || defualtOptions.cancel;
	this.modal=this.options.modal || defualtOptions.modal;
	this.buttonOk=this.options.buttonOk || "확인";
	
	
	this._show=function(){
		
		var _func=this.callback;
		var _ok=this.ok;
		var _cancel=this.cancel;
		
		
		var tag=
		"<div class='toast_component TOAST_TYPE'>"+
		"	<div class='toast_comp-box'>"+
		"	    <h3>TOAST_TITLE</h3>"+
		"	    <p class='comp-desc'>"+this.msg+"</p>"+
		"	</div>"+
		"	BTN_DATA"+
		"</div>";
		
	
		var screen=null;
		var wrapper=null;
		var _toast_btn_confirm_="_toast_btn_confirm_"+getRandomInt(100000,999999);
		var _toast_btn_cancel_="_toast_btn_cancel_"+getRandomInt(100000,999999);
		var _toast_btn_ok_="_toast_btn_ok_"+getRandomInt(100000,999999);
		
		if(this.modal){
			screen=$("<div style='position:absolute; top:0; left:0; z-index:100000'></div>");
			screen.css("width","100%").css("height",$(document).height()+"px");
			tag=tag.replace("TOAST_TITLE","hoo");
			tag=tag.replace("BTN_DATA","<div class='btn-wrap'><a href='javascript:;' id='"+_toast_btn_confirm_+"'>확인</a></div>");
			tag=tag.replace("TOAST_TYPE","");
			wrapper=$(tag);
		}else if(this.ok!=null || this.cancel!=null){
			screen=$("<div style='position:absolute; top:0; left:0; z-index:100000'></div>");
			screen.css("width","100%").css("height",$(document).height()+"px");	
			tag=tag.replace("TOAST_TITLE","hoo 확인");
			tag=tag.replace("BTN_DATA","<div class='btn-wrap'><a href='javascript:;' id='"+_toast_btn_cancel_+"'>취소</a><a href='javascript:;' id='"+_toast_btn_ok_+"'>"+this.buttonOk+"</a></div>");
			tag=tag.replace("TOAST_TYPE","toast-choose");
			wrapper=$(tag);
		}
		else{
			screen=$("<div z-index:100000'></div>");
			tag=tag.replace("TOAST_TITLE","hoo 알림");
			tag=tag.replace("BTN_DATA","");
			wrapper=$(tag);
		}		
		
		var parent=$("body");
		
		parent.append(screen);
		parent.append(wrapper);
		$(document).on("click","#"+_toast_btn_confirm_,function(){
			wrapper.fadeOut(500, function(){				
				
				wrapper.remove();
				screen.remove();
				_func();
				
			});
		});
		
		$(document).on("click","#"+_toast_btn_cancel_,function(){
			wrapper.fadeOut(500, function(){				
				
				wrapper.remove();
				screen.remove();
				if(_cancel){_cancel();}
				
			});
		});
		
		$(document).on("click","#"+_toast_btn_ok_,function(){
			wrapper.fadeOut(500, function(){				
				
				wrapper.remove();
				screen.remove();
				if(_ok){_ok();}
				
			});
		});
		
		/*
		$("#_toast_btn_confirm_").click(function(){			
			wrapper.fadeOut(500, function(){				
				
				wrapper.remove();
				screen.remove();
				_func();
				
			});
		});
		
		$("#_toast_btn_cancel_").click(function(){			
			wrapper.fadeOut(500, function(){
				
				wrapper.remove();
				screen.remove();
				if(_cancel){_cancel();}
				
			});
		});
		$("#_toast_btn_ok_").click(function(){			
			wrapper.fadeOut(500, function(){
				
				wrapper.remove();
				screen.remove();
				if(_ok){_ok();}
				
			});
		});
		*/
		
	
		screen.css("visibility","hidden");
		wrapper.css("visibility","hidden");
		
		$(function(){
			screen.css("visibility","visible");
			wrapper.css("visibility","visible");
			var winWidth=$(window).width();
			var winHeight=$(window).height();
			var docHeight=$(document).height();
			var docWidth=$(document).width();
			
			var wrapperWidth=wrapper.outerWidth();
			var wrapperHeight=wrapper.outerHeight();
			
			
			var left=parseInt((winWidth-wrapperWidth)/2);
			var top= (winHeight - wrapperHeight)/2;
			
			if(top<0){
				top=0;
			}
			
			wrapper.css("left","calc( 50% - "+(wrapperWidth/2)+"px )");
			wrapper.css("top",top+"px");
			$("#"+_toast_btn_confirm_).focus();
		});
		
		
		
		if(!this.modal && (this.ok==null && this.cancel==null) ){
			setTimeout(
				function(){
					wrapper.fadeOut(300, function(){						
						_func();
						wrapper.remove();
						screen.remove();
					});
				},
				this.timeOut
			);
		}
	}
	
	this._init=function(){
		if(this.options.timeOut!=undefined){
			this.timeOut=this.options.timeOut;
		}
		if(this.options.callback!=undefined){
			this.callback=this.options.callback;
		}else{
			this.callback=function(){};
		}
	
		
		
	}
	var getRandomInt=function(min, max) {
		  min = Math.ceil(min);
		  max = Math.floor(max);
		  return Math.floor(Math.random() * (max - min)) + min; //최댓값은 제외, 최솟값은 포함
	}
	var getBasePath=function(){
		var a="";
		var e=/(^|.*[\\\/])toast\.js(?:\?.*|;.*)?$/i;
		if(!a)for(var b=document.getElementsByTagName("script"),c=0;c<b.length;c++){var f=b[c].src.match(e);if(f){a=f[1];break}}-1==a.indexOf(":/")&&"//"!=a.slice(0,2)&&(a=0===a.indexOf("/")?location.href.match(/^.*?:\/\/[^\/]*/)[0]+
			a:location.href.match(/^[^\?]*\/(?:)/)[0]+a);
		return a;
	}
	var getUrl=function(a){
		var basePath=getBasePath();
		-1==a.indexOf(":/")&&0!==a.indexOf("/")&&(a=basePath+a);
		timestamp&&"/"!=a.charAt(a.length-1)&&!/[&?]t=/.test(a)&&(a+=(0<=a.indexOf("?")?"&":"?")+"t="+timestamp);
		return a;
	}
	
	
	var loadCss=function(){
		if(document.getElementById("toast_css")){
			return;
		}
		
		$('<link>')
		  .appendTo('head')
		  .attr({
		      type: 'text/css', 
		      rel: 'stylesheet',
		      href: getUrl("toast.css"),
		      id: "toast_css"
		  });
	};
	
}


Toast.show=function(msg,options){
	var toast=new Toast(msg,options);
	toast._init();
	toast._show();
	
}