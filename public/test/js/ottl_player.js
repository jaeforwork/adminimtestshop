/**
 * 
 */

var ottlPlayer={
	initWidth:0,
	initHeight:0,
	progressSeek:false,
	volProgressSeek:false,
	videoObj:null,
	seekTime:0,
	defaultVolume:0.4,
	volumeBubbleX:0,
	videoContainer:null,
	showControll:false,
	loading:false,
	popupTime:null,
	curPopupTime:0,
	
	init:function(initW,initH,autoplay){
		ottlPlayer.initWidth=initW;
		ottlPlayer.initHeight=initH;
		video=document.getElementById("ottl_video");
		ottlPlayer.videoContainer=document.getElementById("video_wrap");	
		ottlPlayer.videoObj=video;
		video.msZoom=false;
		//ottlPlayer.videoContainer.style.width=initW+"px";
		//ottlPlayer.videoContainer.style.height=initH+"px";
		
		video.addEventListener("loadedmetadata",function(){
			ottlPlayer.loading=true;
			
			
			if(video.videoHeight>video.videoWidth){
				video.setAttribute("data-orientation","vertical");				
				//video.style.height=initH+"px";
			}else{
				video.setAttribute("data-orientation","horizontal");
			}
			
			ottlPlayer.duration=video.duration;
			ottlPlayer.hour=parseInt(video.duration/3600);
			ottlPlayer.min=parseInt( (video.duration-ottlPlayer.hour*3600)/60);
			ottlPlayer.sec=parseInt( video.duration-ottlPlayer.hour*3600-60*ottlPlayer.min);
					
			ottlPlayer.popupTime=$("<span class='popup_curtime' style='position:fixed; bottom:-555px; color:#fff; background-color:#777; border-radius:3px; padding:5px; font-weight:bold; z-index:999999; '></span>");
			$("#video_wrap").append(ottlPlayer.popupTime);
			//ottlPlayer.popupTime.css("display","none");
			
			
			
			var volume=ottlPlayer.getVolCookie();
			if(volume==""){
				video.volume=ottlPlayer.defaultVolume;
			}else{
				video.volume=volume;
			}
			ottlPlayer.volumeBubbleX=video.volume  * $(".vl_bg_progress").width();
			
			ottlPlayer.volumeCtrl(video);
			ottlPlayer.draw();
			$(".layer_player button").addClass("play").removeClass("stop");
			setTimeout(function(){
				ottlPlayer.popupTime.css("display","none");
				ottlPlayer.popupTime.css("bottom","55px");
			},1000);
			
			
		},false);
		
		
		video.addEventListener("timeupdate", function () {
			//  Current time
			var currentTime = ottlPlayer.currentTime=video.currentTime;
			ottlPlayer.curHour=parseInt(currentTime/3600);
			ottlPlayer.curMin=parseInt( (currentTime-ottlPlayer.curHour*3600)/60);
			ottlPlayer.curSec=parseInt( currentTime-ottlPlayer.curHour*3600-60*ottlPlayer.curMin);
			ottlPlayer.draw();
			
		}, false);

		video.addEventListener("pause", function () {
			$("button.btn_mov").addClass("btn_play").removeClass("btn_stop");
			var svganim = document.getElementById("equalizer");
			if(svganim){svganim.pauseAnimations();}
			
		}, false);
		video.addEventListener("play", function () {
			$("button.btn_mov").removeClass("btn_play").addClass("btn_stop");
			var svganim = document.getElementById("equalizer");
			if(svganim){
				svganim.unpauseAnimations();
			}
		}, false);

		video.addEventListener("volumechange", function () {
			
			ottlPlayer.volumeCtrl(video);
		}, false);

		video.addEventListener("ended", function () {
			$("button.btn_mov").addClass("btn_play").removeClass("btn_stop");
			var svganim = document.getElementById("equalizer");
			if(svganim){svganim.pauseAnimations();}
		}, false);

		video.addEventListener("progress", function () {
			ottlPlayer.draw();
		}, false);
		video.addEventListener("resize", function () {			
			//video.height=$(window).height();
		//	ottlPlayer.resize(video);
					
		}, false);

		
		
		window.addEventListener("resize",function () {			
			//video.height=$(window).height();
			//ottlPlayer.resize(video);
			if(ottlPlayer.isFullScreen()){
				ottlPlayer.videoContainer.setAttribute("data-fullscreen","true");
				$("button.btn_screen").addClass("btn_fullscreen").removeClass("btn_standard");
			}else{				
				ottlPlayer.videoContainer.setAttribute("data-fullscreen","false");
				$("button.btn_screen").addClass("btn_standard").removeClass("btn_fullscreen");
			}			
		});
		
		$("#video_wrap").bind('resize',function(){
			ottlPlayer.resize(video);
			if(ottlPlayer.isFullScreen()){
				$("button.btn_screen").addClass("btn_fullscreen").removeClass("btn_standard");				
			}else{				
				$("button.btn_screen").addClass("btn_standard").removeClass("btn_fullscreen");
			}
		});
		
		$(video).on("contextmenu",function(){			
			return false;
		});
		$(".video_wrap").on("contextmenu",function(){			
			return false;
		});
		$(".play_controller").click(function(){
			return false;
		});

		//영상 progress 클릭시 
		$(".play_progress_wrap").click( function(e) {
			if(!ottlPlayer.loading){
				return false;
			}
			var pos = (e.pageX  - (this.offsetLeft + this.offsetParent.offsetLeft)) / this.offsetWidth;
			video.currentTime = pos * video.duration;
		});

		//volume progress 클릭시 
		$(".vl_progress_wrap").click( function(e) {
			if(!ottlPlayer.loading){
				return false;
			}
			ottlPlayer.volumeBubbleX = (e.pageX  - (this.offsetLeft + this.offsetParent.offsetLeft));
			video.volume = ottlPlayer.volumeBubbleX / this.offsetWidth;
			ottlPlayer.draw();
		});


		//progress over 시
		$(".play_progress_wrap").hover( function() {
			if(!ottlPlayer.loading){
				return false;
			}
			$(".play_progress_bubble").show();
			$(".bg_progress").addClass("progress_on");
			$(".load_progress").addClass("progress_on");
			$(".play_progress").addClass("progress_on");
		},function(){
			if(!ottlPlayer.loading){
				return false;
			}
			if(!ottlPlayer.progressSeek){
				$(".play_progress_bubble").hide();
				$(".bg_progress").removeClass("progress_on");
				$(".load_progress").removeClass("progress_on");
				$(".play_progress").removeClass("progress_on");
			}
		});
		
		
		$(".play_progress_wrap").mousemove( function(e) {
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.played.length>0 && !video.ended){
				$(".play_progress_bubble").show();
				$(".bg_progress").addClass("progress_on");
				$(".load_progress").addClass("progress_on");
				$(".play_progress").addClass("progress_on");
				
				
				var x=(e.pageX  - (this.offsetLeft + this.offsetParent.offsetLeft));// * video.duration;
				ottlPlayer.curPopupTime=x / this.offsetWidth * video.duration;
				//console.log(video.duration+","+x+", "+curTime);
				
				if(ottlPlayer.popupTime!=null && ottlPlayer.popupTime.length>0){
					x=x+20 - ottlPlayer.popupTime.width()/2 -5;
					ottlPlayer.popupTime.css("display","block");
					ottlPlayer.popupTime.css("left",x+"px");
					ottlPlayer.draw();
				}
			}
				
		});
		$(".play_progress_wrap").mouseleave( function(evt) {
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.played.length>0){
				
				ottlPlayer.popupTime.css("display","none");
			}
			//ottlPlayer.progressSeek=false;
			
		});

		
		
		var handler=null;
		
		$(".video_wrap").mousemove( function(evt) {
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.played.length>0){
				ottlPlayer.showControll=true;
				$(".play_controller").fadeIn(200);
				$(".player_dimm").fadeIn(200);
				
				
				
				if(handler!=null){
					clearTimeout(handler);
				}
				handler=setTimeout(function(){					
					$(".play_controller").fadeOut(200);
					$(".player_dimm").fadeOut(200);
					
					handler=null;
					
				},4000);
			}
				
		});
		$(".video_wrap").mouseleave( function(evt) {
			if(!ottlPlayer.loading){
				return false;
			}
			if(!video.paused && !video.ended){
				$(".play_controller").fadeOut(200);
				$(".player_dimm").fadeOut(200);
			}else{
				if(handler!=null){
					clearTimeout(handler);
				}
			}
		});
		

		//영상 progress drag
		$(".play_progress_bubble").draggable(				
			{
				axis: "x",
				containment: ".play_progress_area",						
				drag: function(){
					ottlPlayer.progressSeek=true;
					var x=$(".play_progress_bubble").css("left").replace("px","");
					ottlPlayer.seekTime=parseInt(x / $(".bg_progress").width() * video.duration);
					ottlPlayer.draw();
		        },
		        stop: function(){
		        	ottlPlayer.progressSeek=false;
		        	$(".play_progress_bubble").hide();     	
		        	
					$(".bg_progress").removeClass("progress_on");
					$(".load_progress").removeClass("progress_on");
					$(".play_progress").removeClass("progress_on");
		            

		            var x=$(".play_progress_bubble").css("left").replace("px","");			            
		            video.currentTime = parseInt(x / $(".bg_progress").width() * video.duration);
		            
		          
		        }
			} 
		);

		//volume progress drag
		$(".vl_progress_bubble").draggable(
			{
				axis: "x",
				containment: ".vl_bg_progress",						
				drag: function(){
					ottlPlayer.volProgressSeek=true;
					ottlPlayer.volumeBubbleX=$(".vl_progress_wrap .vl_progress_bubble").css("left").replace("px","");
					var vol=ottlPlayer.volumeBubbleX / $(".vl_bg_progress").width();
					video.volume=vol;
					
					ottlPlayer.draw();
		        },
		        stop: function(){	        	
		        	ottlPlayer.volProgressSeek=false;
		        }
			} 
		);

		// movie play start / pause
		$("button.btn_mov").click(function(){
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.paused || video.ended){			
				video.play();				
			}else{
				video.pause();			
			}
		});

		//audio mute on / off 
		$("button.btn_audio").click(function(){
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.volume==0){
				video.volume=ottlPlayer.defaultVolume;
			}else if(video.muted ){			
				video.muted=false;				
			}else{
				video.muted=true;
			}
		});

		
		/*
		full screen on / off 시 progressbar 사이즈 조정하기 위함.
		*/
		$(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange msfullscreenchange', function(e){
		//	var fullWdith=$(ottlPlayer.videoContainer).width();
		//	$(".play_progress_area").css("width",(fullWdith-40)+"px");
			
		});
		$("button.btn_screen").click(function(){
			if(!ottlPlayer.loading){
				return false;
			}
			var videoContainer=ottlPlayer.videoContainer;
			if(ottlPlayer.isFullScreen()){
				if (document.exitFullscreen) document.exitFullscreen();
				else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
				else if (document.webkitCancelFullScreen) document.webkitCancelFullScreen();
				else if (document.msExitFullscreen) document.msExitFullscreen();
				
				
			}else{
				if (videoContainer.requestFullscreen) videoContainer.requestFullscreen();
				else if (videoContainer.mozRequestFullScreen) videoContainer.mozRequestFullScreen();
				else if (videoContainer.webkitRequestFullScreen) videoContainer.webkitRequestFullScreen();
				else if (videoContainer.msRequestFullscreen) videoContainer.msRequestFullscreen();				
				
			}
			
		});

		$(".video_wrap").click(function(){
			if(!ottlPlayer.loading){
				return false;
			}
			if(video.paused || video.ended){
				$(".layer_player button").addClass("play").removeClass("stop");
				if(video.played.length==0){
					$(".play_controller").fadeIn(200);
					$(".player_dimm").fadeIn(200);
				}
				video.play();
			}else{
				$(".layer_player button").addClass("stop").removeClass("play");
				video.pause();
			}
			$(".layer_player").fadeIn(400,function(){
				$(this).hide();
			});
		});
		
	},
	resize:function(video){
		if(video.videoHeight>0){
			var h=$(ottlPlayer.videoContainer).height();
			var w=$(ottlPlayer.videoContainer).width();
			var ratioW=w/video.videoWidth;
			var ratioH=h/video.videoHeight;
			if(ratioW<ratioH){
				video.width=ratioW*video.videoWidth;
				video.height=ratioW*video.videoHeight;
			}else{
				video.height=ratioH*video.videoHeight;
				video.width=ratioH*video.videoWidth;
			}
			
			
			
		}
		var fullWdith=$(ottlPlayer.videoContainer).width();
		$(".play_progress_area").css("width",(fullWdith-40)+"px");
	},
	isFullScreen:function() {
		   return !!(document.fullScreen || document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement || document.fullscreenElement);
	},
	draw:function(){
		var duration="";
		var playTime="";
		var video=ottlPlayer.videoObj;

		
		
		var hour=parseInt(video.duration/3600);
		var min=parseInt( (video.duration-hour*3600)/60);
		var sec=parseInt( video.duration-hour*3600-60*min);

		var curHour=parseInt(video.currentTime/3600);
		var curMin=parseInt( (video.currentTime-curHour*3600)/60);
		var curSec=parseInt( video.currentTime-curHour*3600-60*curMin);
		
		if(hour>0){
			duration+=parseInt(hour/10)+""+(hour%10);

			playTime+=parseInt(curHour/10)+""+(curHour%10);
		}
		
		if(duration!=""){
			duration+=":";
		}
		if(playTime!=""){
			playTime+=":";
		}
		duration+=parseInt(min/10)+""+(min%10);
		playTime+=parseInt(curMin/10)+""+(curMin%10);
		
		
		if(duration!=""){
			duration+=":";
		}
		if(playTime!=""){
			playTime+=":";
		}
		duration+=parseInt(sec/10)+""+(sec%10);
		playTime+=parseInt(curSec/10)+""+(curSec%10);
		
		
		var playPercent=0;
		var bufferedPercent=0;
		if(video.duration>0){
			playPercent=video.currentTime/video.duration * 100;

			for (var i = 0; i < video.buffered.length; i++) {
	            if (video.buffered.start(video.buffered.length - 1 - i) < video.currentTime) {
	            	bufferedPercent = (video.buffered.end(video.buffered.length - 1 - i) / video.duration) * 100 ;
	                break;
	            }
	        }
		}
		
		$(".play_progress_area .load_progress").css("width",bufferedPercent+"%");			
		$("#duration").html(duration);
		
		if(!ottlPlayer.progressSeek){
			$("#play_time").html(playTime);
			$(".play_progress_bubble").css("left",playPercent*$(".bg_progress").width()/100);
			$(".play_progress_area .play_progress").css("width",playPercent+"%");	
		}else{
			var seekTimeStr="";
			var seekHour=parseInt(ottlPlayer.seekTime/3600);
			var seekMin=parseInt( (ottlPlayer.seekTime-seekHour*3600)/60);
			var seekSec=parseInt( ottlPlayer.seekTime-seekHour*3600-60*seekMin);
			if(seekHour>0){
				seekTimeStr+=parseInt(seekHour/10)+""+(seekHour%10);
			}
					
			if(seekTimeStr!=""){
				seekTimeStr+=":";
			}				
			seekTimeStr+=parseInt(seekMin/10)+""+(seekMin%10);
		
		
			if(seekTimeStr!=""){
				seekTimeStr+=":";
			}				
			seekTimeStr+=parseInt(seekSec/10)+""+(seekSec%10);

			playPercent=ottlPlayer.seekTime/video.duration * 100;
			$(".play_progress_area .play_progress").css("width",playPercent+"%");	
			$("#play_time").html(seekTimeStr);
		}

		//volume draw
		var volumePercent=video.volume  * 100;
		$(".vl_progress_wrap .vl_progress").css("width",volumePercent+"%");
		if(!ottlPlayer.volProgressSeek){
			//var volumeBubbleX=Math.ceil(video.volume  * $(".vl_bg_progress").width());
			$(".vl_progress_wrap .vl_progress_bubble").css("left",ottlPlayer.volumeBubbleX);
		}
		
		
		/**
		 *popup time  
		 */
		
		if(ottlPlayer.popupTime!=null && ottlPlayer.popupTime.length>0){
			
			var popupTime="";
			var phour=parseInt(ottlPlayer.curPopupTime/3600);
			var pmin=parseInt( (ottlPlayer.curPopupTime-phour*3600)/60);
			var psec=parseInt( ottlPlayer.curPopupTime-phour*3600-60*pmin);


			if(phour>0){
				popupTime=parseInt(phour/10)+""+(phour%10);

			}
			if(popupTime!=""){
				popupTime+=":";
			}
			popupTime+=parseInt(pmin/10)+""+(pmin%10);
			if(popupTime!=""){
				popupTime+=":";
			}
			popupTime+=parseInt(psec/10)+""+(psec%10);
			ottlPlayer.popupTime.text(popupTime);
		}
		
	},
	volumeCtrl:function(video){
		if (video.muted) {
			$("button.btn_audio").removeClass("btn_vl_half").removeClass("btn_vl_max").addClass("btn_vl_zero");
		} else {
			if(video.volume>0.5){
				$("button.btn_audio").addClass("btn_vl_max").removeClass("btn_vl_half").removeClass("btn_vl_zero");
			}else if(video.volume==0){
				$("button.btn_audio").addClass("btn_vl_zero").removeClass("btn_vl_half").removeClass("btn_vl_max");
			}else{
				$("button.btn_audio").addClass("btn_vl_half").removeClass("btn_vl_zero").removeClass("btn_vl_max");
			}

			ottlPlayer.setVolCookie(video.volume);
		}
	},
	setVolCookie:function ( cValue){
		var expire = new Date();
	    expire.setDate(expire.getDate() + 999);
	    cookies = 'ottl_play_vol=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	    if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
	    document.cookie = cookies;
	},
	getVolCookie:function () {
	    cName = 'ottl_play_vol=';
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
	},
	play:function(){
		if(ottlPlayer.videoObj){			
			ottlPlayer.videoObj.play();
			$(".play_controller").hide();
			$(".player_dimm").hide();			
			$(".layer_player").hide();
		}
	},
};
