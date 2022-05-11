var processing=false;
function checkProcessing(){
	if(processing){
		return true;
	}
	processing=true;
	return false;
}

function resetProcessing(){
	processing=false;
}

function checkHashtag(str, maxlen,maxTagCount){
	str=str.trim();
	if(!str){
		return 1;
	}
	var first=str.substring(0,1);
	if(first!="#"){
		//return -1;
	}
	var list=str.split('#');
	var checkList=[];
	for(var i=0;i<list.length-1 ; i++){
		var a=list[i].trim();
		if(!a)continue;
		for(var j=i+1;j<list.length ; j++){
			var b=list[j].trim();
			if(!b)continue;			
			if(a.toLowerCase()==b.toLowerCase()){
				return -3;
			}
		}		
	}
	
	for(var i=0;i<list.length ; i++){
		var a=list[i].trim();	
		if(!a)continue;
		checkList.push(a);
	}
	
	if(checkList.length>maxTagCount){
		return maxTagCount;
	}
	for(var i=0;i<checkList.length;i++){
		if(checkList[i].length>maxlen){
			return -2;
		}
		//if(!/^[가-힣a-zA-z0-9_]{1,20}$/.test(checkList[i])){			
		if(/[\s#\&\+\%@=\/\\\:;,'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i.test(checkList[i])){		
		
			return -1;
		}
	}
	return 1;
}






function share(type,idx){
	if(type=="kakao"){
		share_kakao_story("","https://www.hoo.co.kr/view/"+idx);
	}else if(type=="facebook"){
		share_facebook("https://www.hoo.co.kr/view/"+idx);
	}else if(type=="twitter"){
		share_twitter("","https://www.hoo.co.kr/view/"+idx);
	}
}



function share_facebook(url){
	window.open("https://www.facebook.com/sharer/sharer.php?u="+url+"&amp;src=sdkpreparse","_share_facebook","width=680,height=480");
}

function share_twitter(title,url){
	window.open("https://twitter.com/intent/tweet?text="+title+"&url="+url,"_share_twitter","width=680,height=480");

	
}

function share_naver(title,url){
	window.open("https://share.naver.com/web/shareView.nhn?title="+title+"&url="+url,"_share_naver","width=680,height=480");
}

function share_kakao_story(title,url){
	
	window.open("https://story.kakao.com/s/share?title="+title+"&url="+url,"_share_kakao","width=680,height=480");
	
}

function share_band(title,url){
	window.open("https://www.band.us/plugin/share?body="+title+"&route="+url,"_share_naver","width=710,height=700,resizable=no");
}




//forEach for ie11
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
}


function loadJavascript(id,url, callback) {
	
	if(document.getElementById(id)){
		callback();
		return;
	}
	
    var head= document.getElementsByTagName('head')[0];
    var script= document.createElement('script');
    script.type= 'text/javascript';
    script.id=id;
    var loaded = false;
    script.onreadystatechange= function () {
        if (this.readyState == 'loaded' || this.readyState == 'complete') {
            if (loaded) {
                return;
            }
            loaded = true;
            callback();
        }
    }
    script.onload = function () {
        callback();
    }
    script.src = url;
    head.appendChild(script);
}


Number.prototype.format = function(){
    if(this==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
};
 
// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
String.prototype.format = function(){
    var num = parseFloat(this);
    if( isNaN(num) ) return "0";
 
    return num.format();
};

function getFormatDate(date){
    var year = date.getFullYear();              //yyyy
    var month = (1 + date.getMonth());          //M
    month = month >= 10 ? month : '0' + month;  //month 두자리로 저장
    var day = date.getDate();                   //d
    day = day >= 10 ? day : '0' + day;          //day 두자리로 저장
    return  year + '' + month + '' + day;       //'-' 추가하여 yyyy-mm-dd 형태 생성 가능
}
function getDateStr(d){
	var date=d.substring(0,4)+"-"+d.substring(4,6)+"-"+d.substring(6,8)+" "+d.substring(8,10)+":"+d.substring(10,12)+":"+d.substring(12);
	var nowTime=new Date().getTime();
	nowTime=Math.floor(nowTime/1000);
	var compTime= Math.floor(Date.parse(date)/1000);
	
	var timeDiff=nowTime -  compTime;
	var writeDay=d.substring(0,8);
	var currentDay=getFormatDate(new Date());//.toISOString().split('T')[0].replace(/[\-]/g,"");
	
	
	if(writeDay==currentDay){
		return getChatTime(d);
	}
	
	var day=Math.floor(timeDiff/86400)+1;
	
	if(day<=7){
		return day+"일 전";
	}
	return d.substring(0,4)+"-"+d.substring(4,6)+"-"+d.substring(6,8);
}

function getDateStr2(d){
	var date=d.substring(0,4)+"-"+d.substring(4,6)+"-"+d.substring(6,8);
	return date;
	
}

function getChatTime(d){
	// yyyy mm dd hh ii ss
	var hh=parseInt(d.substring(8,10));
	var mm=parseInt(d.substring(10,12));
	var ss=parseInt(d.substring(12,14));
	if(mm<10)mm="0"+mm;
	var times="";
	if(hh<12){
		if(hh==0)hh=12;
		if(hh<10)hh="0"+hh;
		times="오전 "+hh+":"+mm;
	}else{
		hh=hh%12;
		if(hh==0)hh=12;
		if(hh<10)hh="0"+hh;
		times="오후 "+hh+":"+mm;
	}
	return times;		
}


function getChatDay(d){
	
	var yy=d.substring(0,4);
	var mm=d.substring(4,6);
	var dd=d.substring(6,8);
	
	var day=parseInt(yy)+"년 "+parseInt(mm)+"월 "+parseInt(dd)+"일";

	var week = ['일', '월', '화', '수', '목', '금', '토'];
	var dayOfWeek = week[new Date(yy+"-"+mm+"-"+dd).getDay()];		
	
	return day+" "+dayOfWeek+"요일";		
}

function getImageSize(src, callback) {
	var img = document.createElement('img');
	img.src=src;
	
    var wait = setInterval(function() {
        var w = img.naturalWidth,
            h = img.naturalHeight;
        if (w && h) {
            clearInterval(wait);
            callback.apply(this, [w, h]);
        }
        if(window.debugEnable){
        	console.log("image size "+w+","+h);
        }
    }, 30);
    img.onerror=function(){
    	clearInterval(wait);
    	callback.apply(this, [-1, -1]);
    }
}


function getVideoSize(src, callback) {
	var video = document.createElement('video');	
	video.addEventListener("loadedmetadata",function(){
		var w = video.videoWidth,
        h = video.videoHeight;
	    if (w && h) {
	        
	        callback.apply(this, [w, h]);
	    }
	    if(window.debugEnable){
        	
        }
	});
	
	video.addEventListener("error",function(){
		callback.apply(this, [-1, -1]);
	});
	
	video.src=src;
	
    
}
function getCookie(cName) {
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

function setCookie(cName, cValue, cDay){
	var domain=document.domain;
	var index=domain.indexOf(".");
	
	var expire = new Date();
    expire.setDate(expire.getDate() + cDay);
    cookies = cName + '=' + escape(cValue) + '; path=/ ;domain='+domain.substring(index)+';'; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
    if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
    document.cookie = cookies;
}

$(document).on("click",".header-search__btn",function(){
	var query=$.trim($("#search_query").val());
	if(query==""){
		Toast.show("검색어를 입력 하세요.",{modal:true});
		return;
	}
	
	
	
	var temp=query.split(" ");
	for(var i=0;i<temp.length;i++){
		if(temp[i].length<2){
			Toast.show("검색어는 2자 이상 입력 하세요.",{modal:true});
			return;
		}
	}
	
	location.href="/search/query?q="+query;
});


String.prototype.replaceAll=function(oldStr, newStr, ignoreCase){
	
	if(ignoreCase){
		var re=new RegExp(oldStr,'gi');
		return this.replace(re, newStr);
	}else{
		var re=new RegExp(oldStr,'g');
		return this.replace(re, newStr);
	}
	
}


function showPopLoading(){
	var str="<div class='loading-layer'><div class='layer_inner'><img src='/assets/img/Spinner-1s-61px.svg'></div></div>";
	$("body").append(str);
}

function hidePopLoading(){
	$(".loading-layer").remove();
}

function showBtnLoading($obj){
	$obj.html($obj.html()+"<img id='btn_load_image' src='/assets/img/Spinner-1s-61px.svg' style='vertical-align:middle;height:100%'>");
}
function hideBtnLoading($obj){
	$("#btn_load_image").remove();
}


function copy_to_clipboard(str,callback){
	var top=$(window).scrollTop();
	var el=document.createElement("textarea");
	el.id="copy_obj";
	el.style.width="1px";
	el.style.height="1px";
	el.style.border="0";
	el.style.position="absolute";
	el.style.top=top+"px";
	el.value=str;
	//document.body.appendChild(el);
	
	$("body").append(el);
	
	el.select();
	document.execCommand('copy');
	$("#copy_obj").remove();
	
	if(callback){
		callback();
	}else{
		Toast.show("복사 되었습니다");
	}
}


function member_deny(useridx,type){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.user_idx=useridx;
	p.type=type;
	Toast.show("차단하겠습니까?",{ok:function(){
		$.post("/account/deny",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{modal:true});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}


function member_blind(useridx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.user_idx=useridx;
	
	Toast.show("대상 회원의 게시글을<br>모두 블라인드 하겠습니까?",{ok:function(){
		$.post("/article/blind",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{callback:function(){$("."+useridx).remove();}});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}
