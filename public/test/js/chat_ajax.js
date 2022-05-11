/**
 * 
 */

var LiveChat =function(option){
	var input=option.input;
	var sendButton=option.sendButton;
	var sendUrl=option.sendUrl;
	var loadUrl=option.loadUrl;
	
	var room=option.room;
	var csrf=option.csrf;
	var draw=option.draw;
	var loadComplete=option.loadComplete;
	var updateOnline=option.updateOnline;
	var checkOnline=option.checkOnline;
	var sendCallback=option.sendCallback;
	var webSocketHost=option.webSocketHost;
	var webSocketId=option.webSocketId;
	var chatType=option.chatType;
	var chatUser=option.chatUser;
	var firstChat=option.firstChat;
	var mobile=option.mobile ? option.mobile:false;
	var inputField=null;
	var inputButton=null;
	
	var lastChatIndex="";
	var firstChatIndex="";
	var laodTimerId="";
	var defaultTimeout=option.timeout ? option.timeout:3500;
	var reloadable=true;
	
	var queue=[];
	
	var loadable=true;
	
	
	var ws=null;
	var firstOpen=false;
	
	var hidden=null, visibilityChange=null;
	
	this.init=function(){
		inputField=document.querySelector(input);
		
		if(!mobile){
			
			inputField.addEventListener("keyup",function(e){
				if (e && e.which == 13 && !e.shiftKey){
			        var text=inputField.value.trim();
			        if(text==""){
			        	inputField.value="";
			        	return false;
			        }
		        	_sendMessage({type:"text",text:text},true/*test*/);
		        	inputField.value="";
		        	return false;
		        }
			});			
		}
	    if(sendButton){
	    	inputButton=document.querySelector(sendButton);
	    	inputButton.addEventListener("click",function(e){
	    		inputField.focus();
		        var text=inputField.value.trim();
		        if(text==""){
		        	inputField.value="";
		        	return false;
		        }
		        _sendMessage({type:"text",text:text},true/*test*/);
		        inputField.value="";
	    		
	        	return false;
		        
			});
	    }
		drawQueue();
		loadList(true);
	}
	this.sendMessage=function(){
		
		var text=inputField.value.trim();
        if(text==""){
        	inputField.value="";
        	return false;
        }
    	_sendMessage({type:"text",text:text},true/*test*/);
    	inputField.value="";
    	  
	}
	
	var _sendMessage=function(msg, load){
		
		//clearTimeout(laodTimerId);
		var p={};
		p[csrf.name]=csrf.val;
		p.type=msg.type;
		p.message=msg.text;
		p.is_view="1";
		if(checkOnline && checkOnline()){
			
			p.is_view="0"
		}
		
		
		if(firstChat && chatType=="single"){
			
			Toast.show("1:1 대화는 메세지 전송 건당 젤리 2개가 소모 됩니다.<br>메세지를 보내겠습니까?",{ok:function(){
				writeMessage(p,load);
			},cancel:function(){}});
		}else{
			writeMessage(p,load);
		}
		
		
		
	}
	var writeMessage=function( p,load){
		
		$.post(sendUrl,p,function(res){
			
			if(!res)return;
			var data=JSON.parse(res);
			
			
			if(data.result=="Y"){
				//test
				clearTimeout(laodTimerId);
				if(loadable){
					if(data.msg!="" && sendCallback)sendCallback(data.msg);
					if(load){
						loadList(true);
					}
					firstChat=false;
					
					return;
				}
				var wait = setInterval(function() {				        
			        if (loadable) {
			        	if(data.msg!="" && sendCallback)sendCallback(data.msg);
			        	clearInterval(wait);
			        	if(load){
							loadList(true);
						}
			        	firstChat=false;
			        	
			        }				        
			    }, 5);
				
			}else if(data.result=="C"){
				Toast.show(data.msg,{ok:function(){
					top.location.href="/shop";
				},cancel:function(){
					
				}});
			}
			else{
				Toast.show(data.msg,{modal:true});
			}
			
		}).fail(function(){});
	}
	var loadList=function(firstTime, scroll){
		
		if(!reloadable || !loadable)return;
		loadable=false;
		var p={};
		p[csrf.name]=csrf.val;
		p.chat_idx=lastChatIndex;
		
		$.post(loadUrl,p,function(res){
			if(res==""){
				loadable=true;
				return;
			}
			if(loadComplete){
				loadComplete();
			}
			try{
				var chatData=JSON.parse(res);
				if(updateOnline){					
					updateOnline(chatData.online);
					
				}
				if(chatData.online){
					draw({"type":"read_local"});
				}
								
				if(lastChatIndex==""){
					for(var i=0;i<chatData.list.length;i++){
						
						draw(chatData.list[i]);
						
						lastChatIndex=chatData.list[i].chat_idx;
						if(i==0){
							firstChatIndex=chatData.list[i].chat_idx;
						}
					}
					//test
					laodTimerId=setTimeout(loadList,defaultTimeout);
				}else{
					if(!chatData.list.length){
						//test
						laodTimerId=setTimeout(loadList,defaultTimeout);
						
					}else{
						lastChatIndex=chatData.list[chatData.list.length-1].chat_idx;
						if(scroll){
							chatData.scroll=true;
						}else{
							chatData.scroll=false;
						}
						queue.push(chatData);
						//test
						var timeout=chatData.timeout;
						laodTimerId=setTimeout(loadList,timeout);					
					}
				}
			}catch(Err){
				//test
				laodTimerId=setTimeout(loadList,defaultTimeout);
			}
			if(firstTime){
			//	websocketInit();
			}
			loadable=true;
			
		}).fail(function(){
				if(loadComplete){
					loadComplete();
				}
				loadable=true;
				//test
				laodTimerId=setTimeout(loadList,defaultTimeout);
			}
		);
	}
	
	
	
	var sendDraw=function(data, last,timeout){
		draw(data);
		if(last){
			lastChatIndex=data.chat_idx;
			//test
			setTimeout(loadList,timeout);
		}
	}

	var drawQueue=function(){
		var startTime=0;
		
		while(queue.length){
			
			var chatData=queue.shift();
			for(var i=0;i<chatData.list.length;i++){
				var data=chatData.list[i];
				//draw(data,chatData.scroll);
				//test
				setTimeout(draw, data.timeout+startTime, data,chatData.scroll) ;	
				//console.log("draw time "+(data.timeout+startTime));
			}
			//test
			startTime=startTime+(defaultTimeout-chatData.timeout);
		}
		setTimeout(drawQueue,10);
		
		
		
	}


	this.historyLoadList=function(){
		if(!reloadable || !loadable)return;
		loadable=false;
		var p={};
		p[csrf.name]=csrf.val;
		p.chat_idx=firstChatIndex;
		p.history="Y";			
		$.post(loadUrl,p,function(res){
			if(res==""){
				loadable=true;
				return;
			}
			if(loadComplete){
				loadComplete();
			}
			try{
				var chatData=JSON.parse(res);
				
				if(chatData.reload!="Y"){
					reloadable=false;
					return;
				}
				if(!chatData.list.length){
					firstChatIndex=-1;
					return;
				}
	
				
				for(var i=0;i<chatData.list.length;i++){						
					draw(chatData.list[i]);				
					if(i==chatData.list.length-1){
						firstChatIndex=chatData.list[i].chat_idx;
					}
				}
			}catch(Err){}
			loadable=true;
		}).fail(function(){
			if(loadComplete){
				loadComplete();
			}
			loadable=true;
		});
	}
	this.isHistoryLoadable=function(){
		return firstChatIndex!=-1;
	}
	this.sendExtra=function(msg){
		_sendMessage(msg,true/*test*/);
	}
	this.loadDirect=function(){
		//test
		clearTimeout(laodTimerId);
		if(loadable){			
			loadList(false,true);
			return;
		}
		var wait = setInterval(function() {				        
	        if (loadable) {	        	
	        	clearInterval(wait);
	        	loadList(false, true);				            
	        }				        
	    }, 5);
	}
	this.setFirstChat=function(b){
		firstChat=b;
	}
		
}