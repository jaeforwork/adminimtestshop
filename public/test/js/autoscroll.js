var loadingStr="<div class='timeline__loading'><svg version='1.1' id='loader-1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='40px' height='40px' viewBox='0 0 40 40' enable-background='new 0 0 40 40' xml:space='preserve'><path opacity='0.2' fill='#000' d='M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z'/><path fill='#000' d='M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0C22.32,8.481,24.301,9.057,26.013,10.047z'><animateTransform attributeType='xml'attributeName='transform'type='rotate'from='0 20 20'to='360 20 20'dur='0.8s'repeatCount='indefinite'/></path></svg></div>";

var AutoLoadScroll=function(wrapper, p, _callbackStart, _callback){
	
	var pageNum=1;
	var loadable=true;
	var page=p;
	var callbackStart=_callbackStart;
	var callback=_callback;
	
	this.init=function(startPage){
		if(!window.scrollListener){
			window.scrollListener=[];
		}
		for(var i=0;i<window.scrollListener.length;i++){			
			window.removeEventListener("scroll",window.scrollListener[i]);			
		}
		if(startPage){
			pageNum=startPage;
		}else{
			loadpage();
		}
		
		window.addEventListener("scroll",scrollListener);
		
		window.scrollListener.push(scrollListener);
		
		
	};
	
	var scrollListener=function(){
		var wrapHeight=$(window).height();
    	var child=$(document);//.children();
    	var pmHeight=parseInt(child.height());
    	var scrollTop=parseInt($(window).scrollTop());
    	var checkVal=pmHeight-wrapHeight;
    	//console.log(scrollTop+","+checkVal+","+pmHeight+","+wrapHeight);
    	//if(scrollTop==checkVal){
    	if(scrollTop>=checkVal-wrapHeight/5){
    		loadpage();		
    	}
	}

	var loadpage=function(){
		
		//if(!loadable || !$(wrapper).length)return;
		if(!loadable )return;
		loadable=false;
		//var _setLoadable=this.setLoadable;	
			
		var url=page+pageNum;
		
		callbackStart();
		$.get(url,function(res){
			var data=JSON.parse(res);
			
			if(pageNum==1){
				callback(res, wrapper);
				if(data.result=="Y"){
					var listInfo=JSON.parse(data.msg);
					if(listInfo.page){
						pageNum=parseInt(listInfo.page)+1;
						
					}else{
						pageNum++;
					}		 
				}else{
					pageNum++;
				}
			}else{
				setTimeout(function(){
					callback(res, wrapper);
					if(data.result=="Y"){
						var listInfo=JSON.parse(data.msg);
						if(listInfo.page){
							pageNum=parseInt(listInfo.page)+1;
							
						}else{
							pageNum++;
						}		 
					}else{
						pageNum++;
					}	 
				},400);
			}
	    });
	};
	
	this.setLoadable=function(b){
		
		loadable=b;
		
	};
	
	this.nextPage=function(){
		pageNum++;
		loadpage();
	}
	this.reset=function(p, _callbackStart, _callback){
		if(p){
			page=p;
		}
		if(_callbackStart){
			callbackStart=_callbackStart;
		}
		if(_callback){
			callback=_callback;
		}
		
		pageNum=1;
		loadable=true;
		loadpage();
	}
	this.getPage=function(){
		return pageNum;
	}
	
}
AutoLoadScroll.prototype.listenerList=[];

AutoLoadScroll.clearListener=function(){
	if(!window.scrollListener){
		return;
	}
	for(var i=0;i<window.scrollListener.length;i++){			
		window.removeEventListener("scroll",window.scrollListener[i]);			
	}
	
}
