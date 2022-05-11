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
	var websocketInit=function(){
		ws=new WebSocket("wss://"+webSocketHost+"/ws/"+webSocketId);

		ws.onopen = function()
		{
			var msg={};
			msg.type="join";  //연결			
			ws.send(JSON.stringify(msg) );

		
			msg={};
			msg.type=chatType; // 게시물보기 입장
			msg.room_no=room;
			ws.send(JSON.stringify(msg) );
		
			msg={};
			msg.type="info_"+chatType;  // 게시물보기 입장
			msg.room_no=room;
			ws.send(JSON.stringify(msg) );
			
			
			
			if(!firstOpen){
				firstOpen=true;
				
				if (typeof document.hidden !== "undefined") {
				    hidden = "hidden";
				    visibilityChange = "visibilitychange";
				} else if (typeof document.mozHidden !== "undefined") {
				    hidden = "mozHidden";
				    visibilityChange = "mozvisibilitychange";
				} else if (typeof document.msHidden !== "undefined") {
				    hidden = "msHidden";
				    visibilityChange = "msvisibilitychange";
				} else if (typeof document.webkitHidden !== "undefined") {
				    hidden = "webkitHidden";
				    visibilityChange = "webkitvisibilitychange";
				}
	
				function handleVisibilityChange() {
					if(document[hidden]){
						//console.log("document hidden");
					}else{
						//console.log("document visible");
						
						$.get("/dummy",function(){
							csrf.val=getCookie(window.csrf_name);
							if(ws.readyState==WebSocket.CLOSED){
								//websocketInit();
								//lastChatIndex="";
								//firstChatIndex="";
								//var data={};
								//data.type="clear";
								//draw(data);
								loadList(true);
							}
						});
					}
				}
				if(hidden){
					document.addEventListener(visibilityChange, handleVisibilityChange, false);
				}
			}
		}
		
		ws.onmessage = function(message)
		{
			
			var data=JSON.parse(message.data);
			
			switch(data.cmd){
			case "info_multi":
				
				if(updateOnline){					
					updateOnline(data.owner=="on");
				}
				break;
			case "info_single":				
				if(updateOnline /*&& data.user!=chatUser*/){					
					updateOnline(data.connected=="on");
				}
				if(data.user!=chatUser){//상대방 접속. 상대방 미확인 대화 읽음 표시
					var chatMsg={};
					chatMsg.type="read";	
					var chatData={};
					chatData.list=[];
					chatData.list.push(chatMsg);
									
					queue.push(chatData);
				}
				break;
			case "send_multi":
				var chatMsg=JSON.parse(data.msg);
				chatMsg.from= chatMsg.mem_idx==chatUser ? "me" : "you";				
				chatMsg.timeout=0;
				if(chatMsg.mem_idx==chatUser){
					chatMsg.celeb=false;
				}else{
					chatMsg.celeb=chatMsg.mem_idx==chatMsg.owner_idx;
				}
				var chatData={};
				chatData.list=[];
				chatData.list.push(chatMsg);
				queue.push(chatData);				
				break;
				
			case "send_single":
				var chatMsg=JSON.parse(data.msg);
				chatMsg.from= chatMsg.mem_idx==chatUser ? "me" : "you";				
				chatMsg.timeout=0;
				
				var chatData={};
				chatData.list=[];
				chatData.list.push(chatMsg);
				queue.push(chatData);				
				break;
			case "send_all_conn":
				var chatMsg=JSON.parse(data.msg);
						
				chatMsg.timeout=0;
				
				var chatData={};
				chatData.list=[];
				chatData.list.push(chatMsg);
				queue.push(chatData);				
				break;
			default:
				if(!data.msg)break;
				var chatMsg=JSON.parse(data.msg);
				var chatData={};
				chatData.list=[];
				chatData.list.push(chatMsg);
				queue.push(chatData);	
				break;
			}
			
			
		};
		ws.onclose=function(){
			
		}
		
		
		/*
		var infoTimer = setInterval(function() {				        
			var msg={};
			msg.type="info_"+chatType;  // room 정보
			msg.room_no=room;
			ws.send(JSON.stringify(msg) );		        
	    }, 5000);*/
	}
	this.wsclose=function(){ws.close();}
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
		        	_sendMessage({type:"text",text:text});
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
		        
	    		if(ws.readyState==WebSocket.CLOSED){
					
	    			$.get("/dummy",function(){
						csrf.val=getCookie(window.csrf_name);
						//lastChatIndex="";
						//firstChatIndex="";
						//var data={};
						//data.type="clear";
						//draw(data);
						//loadList(true);
						inputField.focus();
				        					        
			        	_sendMessage({type:"text",text:text},true);
			        	inputField.value="";
						
					});
					
				}else{
					
		        	_sendMessage({type:"text",text:text});
		        	inputField.value="";
				}
	    		
	    		
	    		
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
    	_sendMessage({type:"text",text:text});
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
				
								
				if(lastChatIndex==""){
					for(var i=0;i<chatData.list.length;i++){
						
						draw(chatData.list[i]);
						
						lastChatIndex=chatData.list[i].chat_idx;
						if(i==0){
							firstChatIndex=chatData.list[i].chat_idx;
						}
					}
					
					//laodTimerId=setTimeout(loadList,defaultTimeout);
				}else{
					if(!chatData.list.length){
						//laodTimerId=setTimeout(loadList,defaultTimeout);
						
					}else{
						lastChatIndex=chatData.list[chatData.list.length-1].chat_idx;
						if(scroll){
							chatData.scroll=true;
						}else{
							chatData.scroll=false;
						}
						queue.push(chatData);
						
						//laodTimerId=setTimeout(loadList,timeout);					
					}
				}
			}catch(Err){
				//laodTimerId=setTimeout(loadList,defaultTimeout);
			}
			if(firstTime){
				websocketInit();
			}
			loadable=true;
			
		}).fail(function(){
				if(loadComplete){
					loadComplete();
				}
				loadable=true;
				//laodTimerId=setTimeout(loadList,defaultTimeout);
			}
		);
	}
	
	
	
	var sendDraw=function(data, last,timeout){
		draw(data);
		if(last){
			lastChatIndex=data.chat_idx;
			//setTimeout(loadList,timeout);
		}
	}

	var drawQueue=function(){
		var startTime=0;
		
		while(queue.length){
			
			var chatData=queue.shift();
			for(var i=0;i<chatData.list.length;i++){
				var data=chatData.list[i];
				draw(data,chatData.scroll);
				//setTimeout(draw, data.timeout+startTime, data,chatData.scroll) ;						
			}
			//startTime=startTime+(defaultTimeout-chatData.timeout);
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
		_sendMessage(msg);
	}
	this.loadDirect=function(){
		//clearTimeout(laodTimerId);
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