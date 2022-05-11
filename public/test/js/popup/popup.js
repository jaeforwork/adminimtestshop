/**
 * 
 */
window.popup_opened=false;
var Popup=function(title, body,callback){
	
	var timestamp="2";
	var popId="popup_msg_"+(Math.floor(Math.random() * 100000000) + 100000000);
	this.title=title;
	this.body=body;
	this.callback=callback;	
	this.screen=null;
	this.id="popup_msg_"+(Math.floor(Math.random() * 100000000) + 100000000);
	this.popupWrapper=null;
	this.popup_body=null;
	this.popup_opened=false;
	
	this.makewrapper=function(str){
		this.id="popup_msg_"+(Math.floor(Math.random() * 100000000) + 100000000);
		
		var tag="<div class='popup_component' id='"+popId+"'>"+
				"        <div class='popup_comp-box'>"+
				"            <h2>"+this.title+"</h2>"+
				"            <button class='btn-close' id='_btn_close_"+popId+"' type='button'><span class='ico-pc'>닫기</span></button>"+
				"            <div class='popup_comp-contents'>"+str;
		if(callback){
			tag+="				<div class='popup_comp-btn-box'><a href='javascript:;' id='_btn_cancel_"+popId+"' class='btn-negative'>취소</a><a href='javascript:;' id='_btn_ok_"+popId+"' class='btn-positive'>확인</a></div>";
		}
		tag+=	"            </div>"+
				"        </div>"+
				"    </div>";
		return $(tag);
		
		
	}
	
	this._show=function(bgcolor){	
		if(this.popup_opened){return;}
		this.popup_opened=true;
		
		var _this=this;	
		var wrapper=this.makewrapper(this.body);
		var background=bgcolor ? bgcolor : 	"rgba(0,0,0,0.3)";	
		this.screen=$("<div id='_bg_"+popId+"' style='position:fixed; top:0; left:0; z-index:2998;display:none; background:"+background+";'></div>");		
		var parent=$("body");
		parent.append(this.screen);
		parent.append(wrapper);
		var _screen=this.screen;
		this.popup_body=$("#"+popId).find(".popup_comp-contents");
		
		$("#_btn_cancel_"+popId).click(function(){		
			_this.close();
		});
		var pressOk=false;
		$("#_bg_"+popId).click(function(){		
			_this.close();
		})
		
		$("#_btn_ok_"+popId).click(function(){
			if(pressOk)return;
			pressOk=true;
			parent.css("overflow-y","");
			if(callback){
				callback();
			}
			pressOk=false;
		});
		
		
		$("#_btn_close_"+popId).click(function(){		
			_this.close();
		})
		
		if(!callback){
			$("#_btn_ok_"+popId).hide();
		}
		
		
		$("#popup_body_wrap").on("mousedown",function(e){
			e.stopPropagation();
			return true;
		})
		$( function() {
			if($.draggable){
				$( wrapper ).draggable();
			}
		} );
		
		
		_screen.css("display","block");
		_screen.css("width","100%");
		_screen.css("height","100%");
		wrapper.css("display","none");
		_this.relocate();		
		wrapper.css("display","block");		
		$(window).on("resize",function(){
			if(_screen){
				_this.relocate();
			}
		});
		
		$(document).on("keyup",funcEsc=function (e){
			if(e.keyCode==27){
				$(document).off("keyup",funcEsc);
				_this.close();
				
			}
		});
	}
	
	this.relocate=function(){
		var winWidth=$(window).width();
		var winHeight=$(window).height();
		var docHeight=$(document).height();
		var docWidth=$(document).width();
		
		var popupWrapper=$("#"+popId);
		
		var wrapperWidth=popupWrapper.outerWidth();
		var wrapperHeight=popupWrapper.outerHeight();
		
		var bh=docHeight-winHeight-$(window).scrollTop();
		var left=parseInt((winWidth-wrapperWidth)/2);
		var top=parseInt((docHeight-wrapperHeight)/2 + $(window).scrollTop()/2 -bh/2);
		if(top<0){
			top=0;
		}
		popupWrapper.css("left",left+"px");		
		popupWrapper.css("top",top+"px");		
	}
	this.setPosition=function(left,top){
		var popupWrapper=$("#"+popId);
		popupWrapper.css("left",left+"px");
		popupWrapper.css("top",top+"px");
	}
	
	this.setBgView=function(view){
		if(view){
			this.screen.css("display","block");
		}else{
			this.screen.css("display","none");
		}
	}
	this.cancelAble=function(t){
		if(t){
			$("#_btn_close_").css("display","block");
			$("#_btn_cancel_").css("display","block");
		}else{
			$("#_btn_close_").css("display","none");
			$("#_btn_cancel_").css("display","none");
		}
		
	}
	var getBasePath=function(){
		var a="";
		var e=/(^|.*[\\\/])popup\.js(?:\?.*|;.*)?$/i;
		if(!a)for(var b=document.getElementsByTagName("script"),c=0;c<b.length;c++){var f=b[c].src.match(e);if(f){a=f[1];break}}-1==a.indexOf(":/")&&"//"!=a.slice(0,2)&&(a=0===a.indexOf("/")?location.href.match(/^.*?:\/\/[^\/]*/)[0]+
			a:location.href.match(/^[^\?]*\/(?:)/)[0]+a);return a;
	}
	var getUrl=function(a){
		var basePath=getBasePath();
		return basePath+a+"?t="+timestamp;	
	}
	
	var loadCss=function(){
		if(document.getElementById("popup_css")){
			return;
		}
		var headID = document.getElementsByTagName("head")[0];  // 해더 사이에 위치 지정
		var cssNode = document.createElement('link');
		cssNode.id="popup_css";
		cssNode.type = 'text/css';
		cssNode.rel = 'stylesheet';
		cssNode.href = getUrl("popup.css");
		headID.appendChild(cssNode);
	};
	
	
	this.setCallback=function(callback){
		this.callback=callback;
	}
	this.setOkButtonText=function(text){
		$("#_btn_ok_"+popId).html(text);
	}
	this.setCancelButtonText=function(text){
		$("#_btn_cancel_"+popId).html(text);
	}
	this.close=function(){
		this.popup_opened=false;
		var parent=$("body");
		parent.css("overflow-y","");
		
		if(this.screen){
			this.screen.remove();
		}
		
		$("#"+popId).remove();	
	}
	this.removeBodyScrollbar=function(){
		var parent=$("body");
		parent.css("overflow-y","hidden");
	}
	this.resetBodyScrollbar=function(){
		var parent=$("body");
		parent.css("overflow-y","");
	}
	this.setContent=function(content){
		
		this.popup_body.html(content);		
		this.relocate();
		
	}
	this.isOpen=function(){
		return this.popup_opened;
	}
	
}


Popup.show=function(title, body,callback,bgcolor){
	var popup=new Popup(title, body,callback);
	
	popup._show(bgcolor);
	popup.removeBodyScrollbar();
	return popup;
	
}





