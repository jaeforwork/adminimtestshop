
function setFixedPopLocation($obj, $pop, extraCss){
	var top=$obj.position().top+$obj.outerHeight()+parseInt($obj.css("margin-top").replace("px",""))+3;
	var left=$obj.position().left + parseInt($obj.css("margin-left").replace("px",""))-$pop.width()+$obj.outerWidth();
	$pop.css({left:left+"px",top:top+"px"});
	if(extraCss){
		$pop.css(extraCss);
	}
}
$(function(){
	$(window).scroll(function(){
        if ($(this).scrollTop() > 200){
            $('.btn-top').fadeIn();
        } else {
            $('.btn-top').fadeOut();
        }
    });
    $('.btn-top').click( function() {
        $('html, body').animate({
			scrollTop : 0
		}, 200);
        return false;
    });
	var buttonUtil=null;
	var popUtil=null;
	//버튼 클릭 시 하단에 생기는 팝업 제거
	
	/*$('html,body').on("click", function(){
		$(".util-popup-wrap").remove();
		
	});*/
	
	//버튼 클릭 시 하단에 생기는 팝업 제거
	$('body').on("click", function(e){	
		var target=$(e.target);		
		if(!target.hasClass("util-popup-wrap")){			
			var pop=$('.util-popup-wrap');
			if(pop.length){				
				var popId=pop.attr("data-pop-id");
				
				if(popId){
					var targetPopId=target.attr("data-pop-id");					
					if(targetPopId && popId==targetPopId){						
						event.stopPropagation();						
					}
				}
				pop.remove();	
				
			}
		}
		
	});
	
	
	
	//1:1대화 util버튼
	$(document).on("click",'.util-btn.chat__single', function(){
		
		var $obj=$(this);
		var room_idx=$obj.attr("data-room-idx");
		var mem_idx=$obj.attr("data-mem-idx");
		var nick_name=$obj.attr("data-nick-name");
		var pop=$("<div class='util-popup-wrap active' ><ul class='util-popup'>" +
				"<li class='util-popup__item'><a href='javascript:;' onclick='single_chat_out(\""+room_idx+"\",\""+mem_idx+"\")'><span class='icon-exit icon-common'></span>대화 나가기</a></li>" +
				"<li class='util-popup__item'><a href='javascript:;' onclick='member_deny(\""+mem_idx+"\",\"S\")'><span class='icon-block icon-common'></span>차단하기</a></li>" +
				"<li class='util-popup__item'><a href='javascript:;' onclick='report_chat(\""+room_idx+"\",\""+mem_idx+"\",\""+nick_name+"\",\"S\")'><span class='icon-report icon-common'></span>신고하기</a></li></ul></div>");
		
		//$("body").append(pop);
		$obj.parent().append(pop);
		
		setFixedPopLocation($obj,pop);
		var popId=$obj.attr("data-pop-id");
		if(!popId){
			popId="chat__single_"+Math.random();			
		}
		setPopId($obj,pop,popId);
		return false;
	});
	
	//크리에이터대화 util버튼
	$(document).on("click",'.util-btn.chat__multi', function(){
		
		var $obj=$(this);
		var my_room=$obj.attr("data-my-room");
		var status=$obj.attr("data-status");		
		var owner=$obj.attr("data-owner");
		var pop="";
		
		if(my_room=="Y"){
			var strStatus= status=="Y" ? "대화중지":"대화재개";
			pop=$("<div class='util-popup-wrap active' ><ul class='util-popup' >" +
					"<li class='util-popup__item'><a href='javascript:;' onclick='multiRoomStatus(\""+owner+"\",\""+status+"\")'><span class='icon-chat icon-common'></span>"+strStatus+"</a></li></ul></div>");
		}else{
			pop=$("<div class='util-popup-wrap active' ><ul class='util-popup' data-pop-id='"+popId+"'>" +
					"<li class='util-popup__item'><a href='/chat?t=single&u="+owner+"' ><span class='icon-dm icon-common'></span>1:1대화</a></li></ul></div>");
		}
			
		$obj.parent().append(pop);		
		setFixedPopLocation($obj,pop);
		var popId=$obj.attr("data-pop-id");
		if(!popId){
			popId="chat__multi_"+Math.random();			
		}
		setPopId($obj,pop,popId);
		return false;
	});
	
	
	//우상단 프로필 메뉴 토글
	$(document).on("click",'.util-btn.my-icon', function(){
		
		var $obj=$(this);
		
		var noti=$obj.data("noti");		
		var pop=$("<div class='util-popup-wrap active' ><ul class='util-popup' >" +
				"<li class='util-popup__item'><a href='javascript:;' class='util-popup__signal btn-mysignal'>알림 "+noti+"</a></li>" +
				"<li class='util-popup__item'><a href='/myprofile' class='link-silence'>마이페이지</a></li>" +
				"<li class='util-popup__item'><a href='/account/logout'>로그아웃</a></li></ul></div>");		
		$obj.parent().append(pop);		
		setFixedPopLocation($obj,pop);	
		var popId=$obj.attr("data-pop-id");
		if(!popId){
			popId="my-icon_"+Math.random();			
		}
		setPopId($obj,pop,popId);
		return false;
	});
	
	
	
	
	//timeline util popup
	$(document).on("click",".util-btn.timeline", function(){
		
		var $obj=$(this);
		var doc_idx=$obj.data("doc-idx");
		var mem_idx=$obj.data("mem-idx");
		var type=$obj.data("type");	
		var nickName=$obj.data("nick_name");	
		
		var pop="";
		var host=location.protocol+"//"+location.hostname;
		if(type=="mypage"){
			var open=$obj.attr("data-open");
			
			var openStr=open=="Y" ? "비공개":"공개";
			var openVal=open=="Y" ? "N":"Y";
			var menuOpen="<li class='util-popup__item'><a href='javascript:;' onclick='openTimeline(\""+doc_idx+"\",\""+openVal+"\")'>"+openStr+"</a></li>"+
						"<li class='util-popup__item'><a href='javascript:;' onclick='copy_to_clipboard(\""+host+"/view/"+doc_idx+"\")'>게시물 주소 복사</a></li>";
			pop=$("<div class='util-popup-wrap active '><ul class='util-popup'><li class='util-popup__item'><a href='javascript:;' onclick='removeTimeline(\""+doc_idx+"\")'>삭제</a></li><li class='util-popup__item'><a href='/posting/"+doc_idx+"' class='link-silence'>수정</a></li>"+menuOpen+"</ul></div>");
		}else if(type=="timeline"){
			var login=$obj.attr("data-login");
			var str="";
			if(login=="true"){
				str="<div class='util-popup-wrap active'><ul class='util-popup'><li class='util-popup__item'><a href='javascript:;' onclick='report(\""+doc_idx+"\",\""+nickName+"\")'>신고하기</a></li>" +
				"<li class='util-popup__item'><a href='javascript:;' onclick='member_blind(\""+mem_idx+"\")'>블라인드</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' data-chat='"+mem_idx+"' class='go-chat-multi'>크리에이터대화 바로가기</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='copy_to_clipboard(\""+host+"/view/"+doc_idx+"\")'>게시물 주소 복사</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"kakao\",\""+doc_idx+"\")'>카카오페이지 공유</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"facebook\",\""+doc_idx+"\")'>페이스북 공유</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"twitter\",\""+doc_idx+"\")'>트위터 공유</a></li></ul></div>"
				
			}else{
				str="<div class='util-popup-wrap active'><ul class='util-popup'>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='copy_to_clipboard(\""+host+"/view/"+doc_idx+"\")'>게시물 주소 복사</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"kakao\",\""+doc_idx+"\")'>카카오페이지 공유</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"facebook\",\""+doc_idx+"\")'>페이스북 공유</a></li>"+
				"<li class='util-popup__item'><a href='javascript:;' onclick='share(\"twitter\",\""+doc_idx+"\")'>트위터 공유</a></li></ul></div>"
			}
			pop=$(str);
		}
		if(pop!=""){			
						
			
			$obj.parent().append(pop);
			setFixedPopLocation($obj,pop);
			
			var popId=$obj.attr("data-pop-id");
			if(!popId){
				popId="timeline_"+Math.random();			
			}
			setPopId($obj,pop,popId);
			
		}
		return false;
	});
	
	//자주 사용 하는 계좌 팝업 메뉴
	$(document).on("click",".btn-normal.btn-bank-account", function(){
		
		var $obj=$(this);
		
		var str="<div class='util-popup-wrap active '><ul class='util-popup box-scroll' style='max-height:200px;'>";
		if(recentAccountList.length){
			for(var i=0;i<recentAccountList.length;i++){
				var account=recentAccountList[i].replaceAll("|"," ");
				str+="<li class='util-popup__item'><a href='javascript:;' onclick='setAccount(\""+recentAccountList[i]+"\")'>"+account+"</a></li>";
			}
		}else{
			str+="<li class='util-popup__item'><a href='javascript:;' >계좌 없음</a></li>";
		}				
		str+=	"</ul></div>";				
		var pop=$(str);		
		
		$obj.parent().append(pop);
		setFixedPopLocation($obj,pop,{"z-index":"99999"});
		
		var popId=$obj.attr("data-pop-id");
		if(!popId){
			popId="btn-bank-account_"+Math.random();			
		}
		setPopId($obj,pop,popId);
		return false;
		
	});
	
	
	
	
	
	
	$(document).on("click", '.btn-top' ,function() {
        $( 'html, body' ).animate( { scrollTop : 0 }, 200 );
        return false;
    } );
	
	//구독, 구독 해제
	$(document).on("click",".subscribe-btn",function(){
		var $obj=$(this);
		var celeb_idx=$obj.data("celeb-idx");
		var subOn=$obj.hasClass("active");
		
		if($obj.hasClass("mbs-active")){
			//Toast.show("멤버십 회원입니다.<br>멤버십 해지 후 구독해지가 가능 합니다.",{modal:true});
			return;
		}
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.celeb_idx=celeb_idx;
		if(subOn){
			Toast.show("구독 해지 하겠습니까?", {ok:function(){
				$.post("/member/subscribe",p,function(res){
					var data=JSON.parse(res);
					if(data.result=="Y"){
						if(data.msg=="ON"){
							$(".subscribe-btn[data-celeb-idx="+celeb_idx+"]").addClass("active").html("구독중");
							$(".celeb-inner__btn[data-celeb-idx="+celeb_idx+"]").addClass("active");
						}else if(data.msg=="OFF"){
							$(".subscribe-btn[data-celeb-idx="+celeb_idx+"]").removeClass("active").html("구독");
							$(".celeb-inner__btn[data-celeb-idx="+celeb_idx+"]").removeClass("active");
						}
					}else{
						Toast.show(data.msg,{modal:true});
					}
				});
			}});
			
		}else{
			$.post("/member/subscribe",p,function(res){
				var data=JSON.parse(res);
				if(data.result=="Y"){
					if(data.msg=="ON"){
						$(".subscribe-btn[data-celeb-idx="+celeb_idx+"]").addClass("active").html("구독중");
						$(".celeb-inner__btn[data-celeb-idx="+celeb_idx+"]").addClass("active");
					}else if(data.msg=="OFF"){
						$(".subscribe-btn[data-celeb-idx="+celeb_idx+"]").removeClass("active").html("구독");
						$(".celeb-inner__btn[data-celeb-idx="+celeb_idx+"]").removeClass("active");
					}
				}else if(data.result=="L"){
					Toast.show(data.msg,{ok:function(){						
						loginform();
					},cancel:function(){
						
					}, buttonOk:"로그인"});
				}else{
					Toast.show(data.msg,{modal:true});
				}
			});
		}
	});
	
	$(document).on("click",".btn-mbs-active",function(){
		location.href="/record#membership";
	});
	
	
	$(document).on("click",".btn-favor-doc",function(){
		var $obj=$(this);
		var doc_idx=$obj.data("doc-idx");
		var celeb_idx=$obj.data("celeb_idx");
		var p={};
		
		p[window.csrf_name]=window.csrf_val;
		p.doc_idx=doc_idx;
		
		$.post("/article/heart",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				
				var resultData=JSON.parse(data.msg);
				if(resultData.updated_heart==1){
					votedShow();
					$obj.addClass("active").next().html(resultData.total_heart);
				}else if(resultData.updated_heart==-1){
					$obj.removeClass("active").next().html(resultData.total_heart);
				}
				
				$(".aside__heart-count[data-celeb_idx="+celeb_idx+"] > em").html(resultData.total_heart.format());
				
				
			}else if(data.result=="N"){
				Toast.show(data.msg);
			}
		});
	});
	
		
	
	
	
	
	// celeb > 게시물 아이템 클릭 > 갤러리 오픈
	$(document).on("click",".celeb__item-box",function(){	
		
		var mem_idx=$(this).data("mem-idx");
		var startIndex=$(this).data("index");
		$.get("/article/list/"+mem_idx+"?size=3",function(res){
			var json=JSON.parse(res);
			var data=JSON.parse(json.msg);
			if(data.list.length){
				var str=""; 
				for(var i=0;i<data.list.length;i++){
					var doc=data.list[i];
					var link="";
					if(doc.link){
						link="<br><a href='"+doc.link+"' target='_blank'>"+doc.link_title+"</a>";
					}
					
					var allMedia="";
					for(var k=0;k<doc.files.length;k++){
						if(k==0){
							allMedia=doc.files[k].url;
						}else{
							allMedia+="|"+doc.files[k].url;
						}
					}
					
					
					var media="";
					/*if(doc.files[0].file_type=="image"){
						media="<div class='gallery__item'><img src='"+doc.files[0].url+"' class='btn-all-media' data-all-media='"+allMedia+"'></div>";
					}else if(doc.files[0].file_type=="mov"){
						media="<div class='gallery__item'><!-- <button class='btn-video-play'><span class='icon-common'></span></button> <span class='vedeo-play-time'>1:56</span>--><video class='video-js' controls preload='metadata' width='600' data-setup='{}'><source src='"+doc.files[0].url+"'></source></video></div>";
					}else{
						
					}*/
					
					
					
					if( doc.price>0){
						
						if(doc.is_mine){
							if(doc.files[0]){
								if(doc.files[0].file_type=="image"){							
									media="<div class='gallery__item' ><img src='"+doc.files[0].url+"' ></div>";							
								}else if(doc.files[0].file_type=="mov"){
									if(doc.files[0].encode=="Y"){
										media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:337px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"' ></iframe></div>";
									}else{
										media="<div class='loading'><div class='loading__img'><img src='/assets/img/Spinner-1s-101px.svg' style='display:inline !important'></div><div class='loading__text'>인코딩 중입니다.</div></div>";
									}	
									
								}else if(doc.files[0].file_type=="audio"){
									media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:227px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"'></iframe></div>";
								}
							}
						}else{
							
							media="<div class='timeline__contents none-thumb' style='max-height: inherit; height: 600px; text-align:center;'>"+
							"<img src='/assets/img/jelly"+doc.jelly+".png' style='display:inline-block'>"+
							"<p class='none-thumb__desc'>유료 컨텐츠입니다.</p>"+
							"</div>";
							
						}
						
					}else if(doc.files[0]){
						if(doc.files[0].file_type=="image"){							
							media="<div class='gallery__item' ><img src='"+doc.files[0].url+"' ></div>";							
						}else if(doc.files[0].file_type=="mov"){
							if(doc.files[0].encode=="Y"){
								media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:337px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"' ></iframe></div>";
							}else{
								media="<div class='loading'><div class='loading__img'><img src='/assets/img/Spinner-1s-101px.svg' style='display:inline !important'></div><div class='loading__text'>인코딩 중입니다.</div></div>";
							}	
							
						}else if(doc.files[0].file_type=="audio"){
							media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:227px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"'></iframe></div>";
						}
					}
					
					
					
					
					/*
					if(doc.files[0]){
						if(doc.files[0].file_type=="image"){							
							media="<div class='gallery__item' ><img src='"+doc.files[0].url+"' ></div>";							
						}else if(doc.files[0].file_type=="mov"){
							if(doc.files[0].encode=="Y"){
								media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:337px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"' ></iframe></div>";
							}else{
								media="<div class='loading'><div class='loading__img'><img src='/assets/img/Spinner-1s-101px.svg' style='display:inline !important'></div><div class='loading__text'>인코딩 중입니다.</div></div>";
							}	
							
						}else if(doc.files[0].file_type=="audio"){
							media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:227px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"'></iframe></div>";
						}
					}
					
					*/
					
					
					var heartCss="";
					if(doc.heart_checked=="Y"){
						heartCss="active";
					}
					
					var btnHeartCss="";
					if(doc.heart<10){
						btnHeartCss="global__heart-btn";
					}else if(doc.heart<20){
						btnHeartCss="global__good-btn";
					}else if(doc.heart<30){
						btnHeartCss="global__star-btn";
					}else{
						btnHeartCss="global__crown-btn";
					}
					
					var inStr="<li>"+media+
									"<div class='gallery__content' style='position:inherit'>"+
									"	<p class='gallery__text' style='float:inherit;max-height:200px;overflow:auto;max-width:inherit'>"+doc.content+/*link+*/"</p>"+
									"	<div class='gallery__bottom-right'>"+
									"		<button class='btn-favor-doc active-anim "+btnHeartCss+" "+heartCss+"' data-doc-idx='"+doc.doc_idx+"' data-celeb-idx='"+doc.mem_idx+"'><span class='icon-common'></span></button><em class='global__heart-count'>"+doc.heart+"</em>"+
									"		<button class='global__share-btn btn-share'><span class='icon-common'></span></button>"+
									"	</div>"+
									"</div>"+
								"</li>";
					str+=inStr;
				}
				
				var popup=Popup.show("","<div><div style='width:720px;'><ul class='bxslider item-list'>"+str+"</ul></div><div class='slider-overlay'></div></div>");
				
				mySlider = $('.bxslider').bxSlider({
					mode: 'horizontal',
					moveSlides: 1,
					slideMargin: 10,
					infiniteLoop: false,
					slideWidth: 720,
					minSlides: 1,
					maxSlides: 1,
					speed: 800,
					pager: false,
					controls: true,
					preloadImages: 'all',
					startSlide:startIndex
				});
				popup.relocate();
				popup.resetBodyScrollbar();
			}
		});
		
	});
	
	$(document).on("click",".gallery__close",function(){
		$(".gallery-wrap").remove();
	});
	
	
	//게시물 이미지 슬라이더
	$(document).on("click",".btn-all-media",function(){
		
		var allMedia=$(this).data("all-media");
		var src=$(this).attr('src');
		var mediaList=allMedia.split("|");
		var mediaStr="";
		var index=0;
		for(var i=0;i<mediaList.length;i++){	
			
	        touchtime = 0;
	        var windHeight=$(window).height();
	        var topData=$(window).scrollTop();
	        
			if(!mediaStr){
				mediaStr="<div class='swiper-slide' style='line-height:"+(windHeight-250)+"px'><div class='swiper-zoom-container'><img src='"+mediaList[i]+"' style='max-height: inherit;'></div></div>";
			}else{
				mediaStr+="<div class='swiper-slide' style='line-height:"+(windHeight-250)+"px'><div class='swiper-zoom-container'><img src='"+mediaList[i]+"' style='max-height: inherit;'></div></div>";
			}
			
			if(src==mediaList[i]){
				index=i;
			}
			
		}	
		
		var str="<div id='_image_view_wrap' class='swiper-container' style='overflow-y:auto;width:100%;height:100%;background:#000000;position:fixed;top:0px;left:0;z-index:111090'><div class='swiper-wrapper'>"+mediaStr+"</div><div class='swiper-pagination-viewimg'></div></div>";
		var $buttonWrap=$("<div id='_close_wrap' style='position:absolute;top:10px;left:0;z-index:111091;width:100%;height:40px'><button style='position:fixed;right:30px; top:20px;width:40px;line-height:20px;border:1px solid #fff;background:rgba(0,0,0,0.7);color:#fff;border-radius:4px' onclick='history.back()'>닫기</button></div>");
		$("body").append(str);
		$("body").append($buttonWrap);
		var swiper = new Swiper('#_image_view_wrap', {
    			pagination: {
    		        el: '.swiper-pagination-viewimg',
    		        clickable: true,
    		        renderBullet: function (index, className) {
    		          return '<span class="' + className + '">' + (index + 1) + '</span>';
    		        },
    			},
    			zoom: {
    			    maxRatio: 4,
    			}
    		}
    	);		 
		swiper.slideTo(index);
		history.pushState({page: 1}, "", "");		
		
		var hideAnim=false;
		function hideImageShow(){
			if(hideAnim)return;
			hideAnim=true;
			$("#_close_wrap").remove();
			
			$("#_image_view_wrap").animate(
				{ left: "-100%"}, 
				400,
				function(){
					$("#_image_view_wrap").remove();
					/*$('body').css({
		                overflow: 'auto',
		                height: 'auto'
		            });*/
					$("body").css("overflow-y","");
					hideAnim=false;
				}
			);
		}
		
		window.onpopstate = function(e) { 
			hideImageShow();
		};
		$("body").css("overflow-y","hidden");		
		
	});
	
	
	//이미지 더블 클릭
	$(document).on("dblclick",".btn-dblclick",function(){
		var $obj=$(this);
		var doc_idx=$obj.data("doc-idx");
		
		var heartBtn=$("#timeline_"+doc_idx+" .btn-favor-doc");
		heartBtn.click();
	});
	
	//set profile image  
	$(document).on("click", ".img-profile__normal.profile", function(){
		var obj=$(this);
		var file_idx=obj.data("file-idx");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.file_idx=file_idx;
		$.post("/account/setprofileimage",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				var info=JSON.parse(data.msg);
				if(info.type=="R"){
					Toast.show("해제 되었습니다.");
					$('.img-profile__normal').removeClass('active');
				}else{
					Toast.show("프로필 이미지로 설정 되었습니다.");
					obj.addClass("active");
					$('.img-profile__normal').not(obj).removeClass('active');
				}
				$(".my-profile-image").attr("src",info.profile_image).addClass("hide");
				showImage(".my-profile-image.hide",true);
				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	});
	
	//profile iamge remove
	$(document).on("click", ".img-delete.profile", function(){
		var obj=$(this);
		var file_idx=obj.data("file-idx");
		var wrap_id=obj.data("id");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.file_idx=file_idx;
		$.post("/account/removeprofileimage",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				
				$("#"+wrap_id).remove();
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
		return false;
	});
	
	
	
	
	
	
	
	
	
	
	
	$(document).on("click",".mysettings.chk-setup", function(){
		var $obj=$(this);
		var name=$obj.data("name");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.name=name;
		p.val=$obj.prop("checked") ? "Y":"N";
		$.post("/account/modsetting",p,function(res){
			var data=JSON.parse(res);
			if(data.result!="Y"){
				Toast.show(data.msg);
				$obj.prop("checked",!$obj.prop("checked"));
			}
		});
		
	});
	
	
	
	
	$(document).on("click",".btn-mysignal",function(){
		
		dispNotiList(1);
	});
	
	$(document).on("click",".chk-item-all",function(){
		var $chk=$(this);
		var name=$chk.data("name");
		var checked=$chk.prop("checked");
		$("input[name="+name+"]").each(function(){
			$(this).prop("checked",checked);
			
		});
	});
	
	$(document).on("click",".btn-detail-list",function(){
		window.log_popup=Popup.show("","<div class='global-pop__contents-wrap' style='width:800px;'></div>");
		dispDiaList(1);
	});
	
	$(document).on("click",".btn-exchange",function(){
		
		window.log_popup=Popup.show("환전 신청하기","<div class='pop__contents-wrap' style='width:520px;max-height:calc(100vh - 250px)'></div>");
		$.get("/account/exchangeform",function(res){
			$(".pop__contents-wrap").html(res);
			window.log_popup.relocate();
		});
		window.log_popup.removeBodyScrollbar();
	});
	
	
	$(document).on("click",".paging > a",function(){
		var $obj=$(this);
		var type=$obj.data("type");
		var page=$obj.data("page");
		if(page){
			if(type=="lognoti"){			
				dispNotiList(page);
			}else if (type=="logdia"){
				dispDiaList(page);
			}
		}
	});
	
	
	//1:1 채팅 신청
	$(document).on("click",".go-chat-single",function(){		
		var chatUser=$(this).attr("data-chat");		
		var p={};
		p[window.csrf_name]=window.csrf_val;
		$.post("/chat/joinsingle/"+chatUser,p,function(res){
			try{
				var data=JSON.parse(res);
				
				if(data.result=="Y"){
					location.href="/chat?t=single&u="+chatUser;
				}else if(data.result=="C"){
					Toast.show(data.msg,{
						ok:function(){
							subscribeAuto(chatUser);
						},
						buttonOk:"구독하기"
					});
				}else{
					if(data.msg.indexOf("{")==-1){
						Toast.show(data.msg,{modal:true});
						return;
					}
					var info=JSON.parse(data.msg);
					if(info.type=="join"){						
						createChat(chatUser);						
					}else{
						Toast.show(info.info,{modal:true});
					}
				}				
				
			}catch(Err){console.log(Err);}
		}).fail(function(){});
	});
	
	//크리에이터대화 바로가기
	$(document).on("click",".go-chat-multi",function(){
		
		var chatUser=$(this).attr("data-chat");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		$.post("/chat/joinmulti/"+chatUser,p,function(res){
			try{
				var data=JSON.parse(res);
				if(data.result=="Y"){
					location.href="/chat?t=multi&u="+chatUser;
				}else if(data.result=="C"){
					Toast.show(data.msg,{
						ok:function(){
							subscribeAuto(chatUser);
						},
						buttonOk:"구독하기"
					});
				}else{
					Toast.show(data.msg,{modal:true});
				}
			}catch(Err){console.log(Err);}
		}).fail(function(){});
		
	});
	
	
	//크리에이터대화
	$(document).on("click",".chat__item.chat__multi",function(){
		var owner=$(this).data("owner");
		joinMultiChat(owner);
	});
	
	//1:1대화
	$(document).on("click",".chat__item.chat__single, a.chat__single",function(){
		var owner=$(this).attr("data-owner");
		
		joinSingleChat(owner,true);
	});
	
	$(document).on("keyup","#search_nick",function(e){
		
		
		var text=$.trim($(this).val());
        if(text==""){
        	$(".chat__item.chat__multi").removeClass("hide");
        	return false;
        }
        
        $(".chat__item.chat__multi").each(function(){
        	var nickName=$(this).data("nick");
        	
        	if(nickName.indexOf(text)==-1){
        		$(this).addClass("hide");
        	}else{
        		$(this).removeClass("hide");
        	}
        	
        });
        
    	return false;
	});
	$(document).on("click",".btn-account.btn-auto",function(){
		var obj=$(this);
		var name=obj.attr("data-name");
		var price=obj.attr("data-price");
		
		
	});
	
	
	//정기 멤버십 구매
	$(document).on("click",".btn_membership",function(){
		
		var obj=$(this);
		var goods_idx=obj.attr("data-goods-idx");
		var price=obj.attr("data-price");
		var celeb_idx=obj.attr("data-celeb-idx");
		
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.goods_idx=goods_idx;
		$.post("/shop/supportmethod/"+celeb_idx,p, function(res){
			var data=JSON.parse(res);
			if(data.result=="C"){
				Toast.show(data.msg,{
					ok:function(){
						subscribeAuto(celeb_idx, function(){
							obj.click();
						});
					},
					buttonOk:"구독하기"
				});
				return;
			}else if(data.result!="Y"){
				Toast.show(data.msg,{modal:true});
				return;
			}
			
			var info=JSON.parse(data.msg);
			var goods=info.goods;
			
			
			var str="<div style='width:540px;'><div class='global-pop__contents-wrap pad_lr_10'><h2>주문상품</h2><table class='data-contents'><caption>상품정보</caption><tbody><th>상품명</th><td>"+goods.name+" 가입</td></tr><tr><th>대상</th><td>"+info.celeb_nick_name+"</td></tr><tr><th>결제 금액</th><td>"+goods.sale_price.format()+"원</td></tr></tbody></table>";
			str+="<h2>결제방법</h2><ul class='payment'>";			
			for(var i=0;i<info.list.length;i++){
				var method=info.list[i];				
				str+="<li class='pay-method' data-pay-idx='"+method.idx+"' data-pgname='"+method.pgname+"' data-page='"+method.start_page+"'>"+method.name+"</li>";					
			}
			str+="</ul><ol class='tips'><li>멤버십 가입 시 30일간 유지되며, 별도 해지가 없으면 자동 연장 됩니다.</li><li>멤버십 연장 시 자동으로 동일 금액이 결제 됩니다.</li><li>결제 금액은 상품금액에 10%의 VAT가 추가 됩니다.</li></ol>";
			str+="<button class='btn-card-info' onclick='showCardLimit(this)'>신용카드 금액 제한 안내<span class='icon-common'></span></button>";
			str+="<div class='tb-layout' id='card_limit_info'><table><thead><tr><th>제한사항</th><th>삼성카드</th><th>현대카드</th><th>신한카드</th><th>롯데카드</th><th>KB국민카드</th><th>하나카드</th><th>NH농협카드</th><th>비씨카드</th></tr></thead><tbody><tr><td>1회금액제한</td><td class='price'>500,000</td><td class='num' rowspan='4'>한도없음</td><td class='price'></td><td class='price'>100,000</td><td class='price'></td><td class='num' rowspan='4'>한도없음</td><td class='price'>100,000</td><td class='num' rowspan='4'>한도없음</td></tr><tr><td>1일금액제한</td><td class='price'></td><td class='price'>50,000</td><td class='price'>300,000</td><td class='price'></td><td class='price'>500,000</td></tr><tr><td>1달금액제한</td><td class='price'>500,000</td><td class='price'></td><td class='price'></td><td class='price'>1,000,000</td><td class='price'></td></tr><tr><td>1일횟수제한</td><td class='num'></td><td class='num'></td><td class='num'>3</td><td class='num'>4</td><td class='num'>10</td></tr></tbody></table></div>";
			str+="<div class='checkbox'><input type='checkbox' id='agree-pay' name='agree-pay'><label for='agree-pay' class='checkbox__item'><span class='icon-common'></span><em>결제할 상품정보에 동의하십니까?</em></label></div></div>";
			str+="<div class='agreement agreement--payment'><button type='button' class='btn-agreement btn-paystart' data-type='support' data-goods-idx='"+goods.idx+"' data-celeb-idx='"+info.celeb_idx+"' disabled>결제하기</button></div></div>";
			window.payMethodPop=Popup.show("",str);
			
		});
		
		
	});
	
	
	
	//젤리 선물	
	$(document).on("click",".btn_give_dia",function(){
		if(window.openGiftForm)return;
		window.openGiftForm=true;
		var $obj=$(this);
		var from_chat=$obj.hasClass("from-chat") ? "Y" : "N";
		var celeb_idx=$obj.attr("data-celeb-idx");
		var type=$obj.attr("data-type");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.celeb_idx=celeb_idx;
		p.type=type;
		p.from_chat=from_chat;
		try{
			$.post("/gift/diaform",p,function(res){
							
				var data=JSON.parse(res);
				if(data.result=="N"){
					Toast.show(data.msg,{modal:true});
					return;
				}
				
				var info=JSON.parse(data.msg);
				var str="<div class='tab-content current'><div class='send-coin-wrap'><div class='icon-coin'><span class='icon-common'></span></div>";
				str+="<input type='text' class='input-coin' placeholder='선물 할 젤리 수량을 입력하세요.' onkeyup='giftFormFormat(this)' id='gift_input_dia' maxlength='10'>";
				str+="<button class='gift-btn' id='btn_gift' data-nick-name='"+info.celeb_nick_name+"' data-celeb-idx='"+info.celeb_idx+"' data-type='"+info.type+"' disabled onclick='giftDia(this)' data-min-val='1'>선물하기</button></div>";
				str+="<div class='product__list-wrap'><ul class='product__list'>";
				for(var i=0;i<info.list.length;i++){
					
					
					if(from_chat=="Y"){
						str+="<li class='product__item'><a href='javascript:;' data-nick-name='"+info.celeb_nick_name+"' data-celeb-idx='"+info.celeb_idx+"' data-type='"+info.type+"' data-dia='"+info.list[i].token+"' onclick='giftDia(this,\""+info.list[i].key+"\");'><span class='product__pic'>" +
						"<img src='"+info.list[i].icon+"' style='width:80%'></span><span class='product__num'>"+info.list[i].token+"</span></a></li>";
					}else{
						
						str+="<li class='product__item'><a href='javascript:;' data-nick-name='"+info.celeb_nick_name+"' data-celeb-idx='"+info.celeb_idx+"' data-type='"+info.type+"' data-dia='"+info.list[i].token+"' onclick='giftFormSetDia("+info.list[i].token+");'><span class='product__pic'>" +
						"<img src='"+info.list[i].icon+"' style='width:80%'></span><span class='product__num'>"+info.list[i].token+"</span></a></li>";
					}
				}
				
				for(var i=0;i<3-info.list.length%3;i++){
					str+="<li class='product__item'><a><img src='/assets/img/blank.png'><span class='product__num'>&nbsp;</span></a></li>";
				}
				
				str+="</ul></div><ol class='tips'><li>젤리 목록에서 선택 하거나 선물 할 토큰 수를 직접 입력 하세요.</li><li>젤리 선물은 1개 부터 할 수 있습니다.</li></ol></div>"
				var html="<div style='width:680px;'>"+str+"</div>"
				window.dia_gift_popup=Popup.show("젤리 선물",html);
				window.openGiftForm=false;
			});
		}catch(E){window.openGiftForm=false;}
		
	});
	
	//상품 리스트 충전 버튼
	$(document).on("click",".btn_charge_dia",function(){
		
		var $obj=$(this);
		var goods_idx=$obj.attr("data-idx");
		showChargeMethod(goods_idx)
		
		
	});
	
	//결제 방법 아이템 클릭
	$(document).on("click",".pay-method",function(res){
		var $obj=$(this);
		var idx=$obj.attr("data-pay-idx");
		var pgname=$obj.attr("data-pgname");
		var startPage=$obj.attr("data-page");
		$(".pay-method").removeClass("active");
		$obj.addClass("active");
		$(".btn-paystart").attr("data-pay-idx",idx);
		$(".btn-paystart").attr("data-pgname",pgname);
		$(".btn-paystart").attr("data-page",startPage);
		checkPayStart();
	});
	
	//결제 상품 정보 동의
	$(document).on("click","#agree-pay",function(){		
		checkPayStart();
	});
	
	//결제 상품 정보 동의
	$(document).on("click","#agree-cash-receipt",function(){		
		checkPayStart();
	});
	
	//결제 시작
	$(document).on("click",".btn-paystart",function(){
		
		var $obj=$(this);
		var type=$obj.attr("data-type");
		var payIdx=$obj.attr("data-pay-idx");
		var pgname=$obj.attr("data-pgname");
		var startPage=$obj.attr("data-page");
		var cachReceipt=$obj.attr("data-cash-receipt");
		
		if(pgname=="KSPAY"){		
			loadJavascript("kspay_script","https://kspay.ksnet.to/store/KSPayWebV1.4/js/kspay_web_ssl.js", function(){
				//var url="/shop/kspaystart/"+type;
				
				var p={};
				p[window.csrf_name]=window.csrf_val;
				p.pay_idx=payIdx;
				if(type=="support"){
					p.celeb_idx=celebIdx;
					p.dia=$obj.attr("data-dia");
					p.celeb_idx=celebIdx;
				}else if(type=="charge"){
					p.goods_idx=$obj.attr("data-goods-idx");
				}
				
				
				$.post(startPage,p,function(res){
					var json=JSON.parse(res);
					if(json.result=="N"){
						Toast.show(json.msg,{modal:true});
					}else{
						//window.payMethodPop.close();
						var str="<div id='payform_wrap'><form  name='payform' method='post'>";
						var jsonMsg=JSON.parse(json.msg);
						var order_values=jsonMsg.order_values;
						for(key in order_values){
							
							str+="<input type='hidden' name='"+key+"' value='"+order_values[key]+"'>";
						}
						str+="</form><script>function mcancel(){closeEvent();} " +
								"function goResult(){document.payform.action = '/shop/kspayresult';document.payform.submit();} " +
								"function eparamSet(rcid, rctype, rhash){document.payform.reWHCid.value = rcid;document.payform.reWHCtype.value = rctype;document.payform.reWHHash.value = rhash;}</script></div>";
						$("#payform_wrap").remove();
						
						var $form=$(str);
						$("body").append($form);
						
						_pay(document.payform);
					}
				});
			});
		}else if(pgname=="DANAL"){
			var type=$obj.attr("data-type");
			//var path= type=="support" ? "/shop/danalpayautostart" : "/shop/danalpaystart";
			var goods_idx=$obj.attr("data-goods-idx");
			var str="<div id='payform_wrap'><form  name='payform' method='post' action='"+startPage+"' target='_danaPay'>";
			str+="<input type='hidden' name='"+window.csrf_name+"' value='"+window.csrf_val+"'>";
			str+="<input type='hidden' name='goods_idx' value='"+goods_idx+"'>";
			str+="<input type='hidden' name='pay_idx' value='"+payIdx+"'>";
			str+="<input type='hidden' name='cash_receipt' value='"+cachReceipt+"'>";
			str+="</form></div>";
			$("#payform_wrap").remove();
			
			var $form=$(str);
			$("body").append($form);
			window.open("about:blank",'_danaPay',"width=500 ,height=420, resizable=0 ,scrollbars=0");
			document.payform.submit();
		}else if(pgname=="WEPAY"){
			var type=$obj.attr("data-type");
			
			var goods_idx=$obj.attr("data-goods-idx");
			
			var str="<div id='payform_wrap'><form  name='payform' method='post' action='"+startPage+"' target='_wePay'>";
			str+="<input type='hidden' name='"+window.csrf_name+"' value='"+window.csrf_val+"'>";
			str+="<input type='hidden' name='goods_idx' value='"+goods_idx+"'>";
			str+="<input type='hidden' name='pay_idx' value='"+payIdx+"'>";
			str+="<input type='hidden' name='cash_receipt' value='"+cachReceipt+"'>";
			str+="</form></div>";
			$("#payform_wrap").remove();
			
			var $form=$(str);
			$("body").append($form);
			window.open("about:blank",'_wePay',"width=500 ,height=420, resizable=0 ,scrollbars=0");
			document.payform.submit();
		}
		
		
		
	});
	
	
	
	
	
	//Record 후원 내역 paging
	$(document).on("click","a[data-type=record_support_send_page],button[data-type=record_support_send_page]",function(){
		var page=$(this).attr("data-page");		
		$("#record_form input[name=page]").val(page);		
		recordSupport("#record_form","send");
	});
	
	//Record 후원 받은 내역 paging
	$(document).on("click","a[data-type=record_support_rev_page],button[data-type=record_support_rev_page]",function(){
		var page=$(this).attr("data-page");		
		$("#record_form input[name=page]").val(page);
		recordSupport("#record_form","rev");
	});
	
	
	
	
	
	
	//결제 내역 상세
	$(document).on("click",".btn-paydetail",function(){
		var $obj=$(this);
		var idx=$obj.attr("data-idx");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.idx=idx;
		$.post("/record/paydetail",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				var payment=JSON.parse(data.msg);
				var str="<table class='payment-table'>	<colgroup><col width='30%'><col width='70%'></colgroup>";
					str+="<tr><th>상품명</th><td>"+payment.goods_name+"</td></tr>"
						+"<tr><th>상품금액</th><td>"+payment.price.format()+"원</td></tr>"
						+"<tr><th>상품유형</th><td>"+payment.type+"</td></tr>";
					
					if(payment.type=="젤리"){
						str+="<tr><th>젤리수량</th><td>"+payment.dia.format()+"개</td></tr>";
					}
						str+="<tr><th>주문시간</th><td>"+payment.str_regdate+"</td></tr>"
						+"<tr><th>결제수단</th><td>"+payment.method_name+"</td></tr>"
						+"<tr><th>결제완료시간</th><td>"+payment.str_paydate+"</td></tr>"
						+"<tr><th>결제상태</th><td>"+(payment.status =="Y" ? "결제완료" : (payment.status =="R" ?"입금대기":"결제취소"))+"</td></tr>";
					if(payment.rbank_name){
						str+="<tr><th>입금은행</th><td>"+payment.rbank_name+"</td></tr>"
							+"<tr><th>입금계좌번호</th><td>"+payment.rbank_account+"<span style='float:right'><a href='javascript:;' onclick='copy_to_clipboard(\""+payment.rbank_account+"\")'>계좌복사</a></span></td></tr>";
					}
					if(payment.status =="N"){
						str+="<tr><th>취소시간</th><td>"+payment.str_canceldate+"</td></tr>";
					}
					
					var html="<div style='width:500px;height:400px'>"+str+"</div>"
					window.paydetail_popup=Popup.show("결제 상세 정보",html);
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	});
	
	$(document).on("click",".btn-terminate-membership",function(){
		Toast.show("멤비십을 해지 하겠습니까?",{ok:function(){
			Toast.show("해지 되었습니다.",{modal:true});
			window.membershipPopup.close();
		}});
		
	});
	
	
	//post membership
	$(document).on("click",".btn-mbs-pass",function(){
		var memIdx=$(this).attr("data-mem-idx");
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.mem_idx=memIdx;
		$.post("/membership/popuplist",p, function(res){
			var data=JSON.parse(res);
			
			if(data.result=="N"){
				Toast.show(data.msg,{modal:true});
			}else{
				var memData=JSON.parse(data.msg);
				var str=
						"<article class='celeb-timeline-mbs'>"+
						"<div class='mbs-ready mbs-type"+(memData.list.length > 2 ? "2":"1")+"'>";
				if(memData.membership_title){
					str+="<h2>"+memData.membership_title+"</h2>";
				}
				
				str+="<ul class='mbs-wrap'>";
				for(var i=0;i<memData.list.length;i++){
					var membership=memData.list[i];
					str+="<li class='mbs-item'><div class='mbs-box'>"+
						"<h2>"+membership.name+"</h2>"+
						"<div class='mbs-price'><p class='period'>매월</p><p class='price'><span>₩</span><em>"+membership.price.format()+"</em></p></div>"+
						"<ul class='tips'>";
					for(var k=0;k<membership.info.tips.length;k++){
						str+="<li>"+membership.info.tips[k]+"</li>";
					}
					str+="</ul>";
					
					str+="<button class='btn-account btn_membership' data-goods-idx='"+membership.idx+"' data-price='"+membership.price+"'  data-celeb-idx='"+membership.mem_idx+"' onclick='window.membership_popup.close()'>멤버십 가입</button></div></li>";
				}
				str+="</ul></div></article>";
				var html="<div style='width:"+(memData.list.length > 2 ? "800": "500")+"px;'>"+str+"</div>"
				window.membership_popup=Popup.show("멤버십 가입",html);
			}
			
		});
	});
	
	
	$(document).on("click",".btn_vid_play",function(){
		var idx=$(this).data("idx");
		
		var str="<div class='gallery__item vid' id='vid_"+idx+"'><iframe id='vid_frame_"+idx+"' frameborder='0' style='width:100%;height:337px;' scrolling='no' src='/article/viewmedia/"+idx+"?auto=on'></iframe></div>";
		$("#vid_thumb_"+idx).after(str);
		$("#vid_thumb_"+idx).remove();
		
		//$("#vid_frame_"+idx)[0].contentWindow.startPlay();
		
		
	});
	
	$(window).scroll(function(){
		var num = $(this).scrollTop();
		if(num > 340){
			$('.aside-wrap--mypage').addClass('aside-fixed');
		}else {
			$('.aside-wrap--mypage').removeClass('aside-fixed');
		}
	});
});



//선물/후원 창에서 사용.
function giftFormFormat(obj){
	var val=obj.value.replace(/\,/g,"");
	obj.value=val.format();
		
	val=parseInt(obj.value.replace(/\,/g,""));
	var minValue=parseInt($("#btn_gift").attr("data-min-val"));
	if(!val || val < minValue){		
		$("#btn_gift").removeClass("active").prop("disabled",true);
	}else{
		$("#btn_gift").addClass("active").prop("disabled",false);
		
	}
	
	$("#btn_gift").attr("data-dia",val);
}
//선물/후원 창에서 사용.
function giftFormSetDia(dia){
	var obj=document.getElementById("gift_input_dia");
	obj.value=dia;
	giftFormFormat(obj);
}

//선물/후원 창에서 사용.
function startSupport(btn){
	var $obj=$(btn);
	var nickName=$obj.attr("data-nick-name");
	var celebIdx=$obj.attr("data-celeb-idx");	
	var dia=$obj.attr("data-dia");
	if(!dia || dia==0){
		Toast.show("후원 할 금액을 입력 하세요.",{modal:true});
		return;
	}
	if(dia<1000){
		Toast.show("최소 1,000원부터 후원이 가능 합니다.",{modal:true});
		return;
	}
	if(dia%1000!=0){
		Toast.show("1,000원 단위로 후원이 가능 합니다.",{modal:true});
		return;
	}
	if(window.dia_gift_popup){
		window.dia_gift_popup.close();
	}
	//후원 하기 위한 결제 수단 선택
	//showSupportMethod(celebIdx, dia);
	
	
}

//선물/후원 창에서 사용.
function giftDia(btn, icon){
	var $obj=$(btn);
	var nickName=$obj.attr("data-nick-name");
	var celebIdx=$obj.attr("data-celeb-idx");
	var type=$obj.attr("data-type");
	var dia=$obj.attr("data-dia");
	if(!dia || dia==0){
		Toast.show("선물 할 젤리 수를 입력 하세요.",{modal:true});
		return;
	}
	
	Toast.show(nickName+"님에게 젤리 "+dia+"개를 선물 하겠습니까?",
		{
			ok:function(){
				var p={};
				p[window.csrf_name]=window.csrf_val;
				p.type=type;
				p.dia=dia;
				p.celeb_idx=celebIdx;
				p.icon= icon ? icon : "";
				
				if(type.indexOf("대화")!=-1){
					if(checkOnline && checkOnline()){
						p.is_view="0"
					}else if(checkOnline && !checkOnline()){
						p.is_view="1";
					}
				}
				if(window.dia_gift_popup){
					window.dia_gift_popup.close();
				}
				$.post("/gift/dia",p,function(res){
					if(res==""){
						
						return;
					}
					var data=JSON.parse(res);
					if(data.result=="Y"){
						var myDia=data.msg;
						
						if(type.indexOf("대화")==-1){
							Toast.show(nickName+"님에게 젤리 "+dia+"개를 선물 하였습니다.");
						}else{
							window.liveChat.loadDirect();
						}
						updateDia(myDia);
						
					}else if(data.result=="C"){
						Toast.show(data.msg,{ok:function(){
							top.location.href="/shop";
						},cancel:function(){
							
						}});
						
					}else {					
						Toast.show(data.msg,{modal:true});
					}
					
				});
			
			}
		}
	);	
}

//젤리 충전 결제 방법 창
function showChargeMethod(goods_idx){
	if(window.openChargeMethod)return;
	window.openChargeMethod=true;
	var p={};
	p[window.csrf_name]=window.csrf_val;
	try{
		$.post("/shop/chargemethod/"+goods_idx,p, function(res){
			var data=JSON.parse(res);
			if(data.result!="Y"){
				Toast.show(data.msg,{modal:true});
				window.openChargeMethod=false;
				return;
			}
			var info=JSON.parse(data.msg);
			var goods=info.goods;
			var count=goods.bonus_amount > 0 ? goods.amount.format() + " + "+goods.bonus_amount.format() : goods.amount.format();
			var price=goods.sale_price.format();
			
			var str="<div style='width:540px;'><div class='global-pop__contents-wrap pad_lr_10'><h2>주문상품</h2><table class='data-contents'><caption>상품정보</caption><tbody><tr><th>상품명 ("+goods.name+")</th><td>"+count+"개</td></tr><tr><th>결제 금액</th><td>"+price+"원</td></tr></tbody></table>";
			str+="<h2>결제방법</h2><ul class='payment'>";			
			for(var i=0;i<info.list.length;i++){
				var method=info.list[i];				
				str+="<li class='pay-method' data-pay-idx='"+method.idx+"' data-pgname='"+method.pgname+"' data-page='"+method.start_page+"'>"+method.name+"</li>";					
			}
			str+="</ul>";
			str+="<button class='btn-card-info' onclick='showCardLimit(this)'>신용카드 금액 제한 안내<span class='icon-common'></span></button>";
			str+="<ol class='tips'><li>결제 금액은 상품금액에 10%의 VAT가 추가 됩니다.</li><li>OK캐시백으로 결제 시에는 사용되는 캐시백 포인트에 따라 충전되는 젤리 수량이 정해 집니다.</li></ol>";
			str+="<div class='tb-layout' id='card_limit_info'><table><thead><tr><th>제한사항</th><th>삼성카드</th><th>현대카드</th><th>신한카드</th><th>롯데카드</th><th>KB국민카드</th><th>하나카드</th><th>NH농협카드</th><th>비씨카드</th></tr></thead><tbody><tr><td>1회금액제한</td><td class='price'>500,000</td><td class='num' rowspan='4'>한도없음</td><td class='price'></td><td class='price'>100,000</td><td class='price'></td><td class='num' rowspan='4'>한도없음</td><td class='price'>100,000</td><td class='num' rowspan='4'>한도없음</td></tr><tr><td>1일금액제한</td><td class='price'></td><td class='price'>50,000</td><td class='price'>300,000</td><td class='price'></td><td class='price'>500,000</td></tr><tr><td>1달금액제한</td><td class='price'>500,000</td><td class='price'></td><td class='price'></td><td class='price'>1,000,000</td><td class='price'></td></tr><tr><td>1일횟수제한</td><td class='num'></td><td class='num'></td><td class='num'>3</td><td class='num'>4</td><td class='num'>10</td></tr></tbody></table></div>";
			str+="<div class='checkbox'><input type='checkbox' id='agree-pay' name='agree-pay'><label for='agree-pay' class='checkbox__item'><span class='icon-common'></span><em>결제할 상품정보에 동의하십니까?</em></label>" +
					"<input type='checkbox' id='agree-cash-receipt' name='agree-cash-receipt'><label for='agree-cash-receipt' class='checkbox__item' style='margin-left:40px'><span class='icon-common'></span><em>현금영수증 발급</em></label></div></div>";
			
			str+="<div class='agreement agreement--payment'><button type='button' class='btn-agreement btn-paystart' data-type='charge' data-goods-idx='"+goods.idx+"' disabled>결제하기</button></div></div>";
			window.payMethodPop=Popup.show("",str);
			window.openChargeMethod=false;
			
		});
	}catch(E){window.openChargeMethod=false;}
}

function showCardLimit(obj){
	var btn=$(obj);
	if(btn.hasClass("on")){
		btn.removeClass("on");
		$("#card_limit_info").removeClass("on");
	}else{
		btn.addClass("on");
		$("#card_limit_info").addClass("on");
	}
}


//결제 방법 선택 창
function checkPayStart(){
	var agree=$("#agree-pay").prop("checked");	
	var selPay=$(".pay-method").hasClass("active");
	if(agree && selPay){
		$(".btn-paystart").prop("disabled",false).addClass("active");
	}else{
		$(".btn-paystart").prop("disabled",true).removeClass("active");
	}
	var $receipt=$("#agree-cash-receipt");
	
	if($receipt.length && $receipt.prop("checked")){
		$(".btn-paystart").attr("data-cash-receipt","1");
	}else{
		$(".btn-paystart").attr("data-cash-receipt","0");
	}
}




function joinMultiChat(owner){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	$.post("/chat/joinmulti/"+owner,p,function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			var ownerInfo=JSON.parse(data.msg);
			$(".chat__item").removeClass("active");
			$("#chat_item_m_"+owner).addClass("active");
			$(".chatroom__header .chat__pic").html("<img src='"+ownerInfo.profile_image+"' class='orientation hide' onclick='location.href=\"/home/"+ownerInfo.nick_name+"\"'></div>");
			$(".chatroom__header .chat__nick").html(ownerInfo.nick_name);
			$(".chatroom__header .btn_give_dia").attr("data-celeb-idx",ownerInfo.owner).attr("data-type","크리에이터대화").addClass("from-chat");
			$(".chatroom__header .btn_support").attr("data-celeb-idx",ownerInfo.owner);
			$("#chat_frame").attr("src","/chat/livemulti/"+ownerInfo.room_id);
			$("#chat_select_info").addClass("hide");
			showImage(".orientation.hide",true);
		}else{
			Toast.show(data.msg,{modal:true});
		}
	});	
}

function joinSingleChat(owner,from_list){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	
	if(from_list){
		p.from="Y";
	}
	$.post("/chat/joinsingle/"+owner,p,function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			var ownerInfo=JSON.parse(data.msg);
			$(".chat__item").removeClass("active");
			$("#chat_item_s_"+owner).addClass("active");
			$(".chatroom__header .chat__pic").html("<img src='"+ownerInfo.profile_image+"' alt='' class='orientation hide'></div>");
			$(".chatroom__header .chat__nick").html(ownerInfo.nick_name);
			$(".chatroom__header .btn_give_dia").attr("data-celeb-idx",ownerInfo.user).attr("data-type","1:1대화").addClass("from-chat");
			$(".chatroom__header .btn_support").attr("data-celeb-idx",ownerInfo.owner);
			$(".chatroom__header .connection").html("<em>Offline</em>");
			
			$("#chat_frame").attr("src","/chat/livesingle/"+ownerInfo.room_id);
			$("#chat_select_info").addClass("hide");
			showImage(".orientation.hide",true);
			$("#uc_"+owner).remove();
		}else{
			if(data.msg.indexOf("{")==-1){
				Toast.show(data.msg,{modal:true});
				return;
			}
			var info=JSON.parse(data.msg);
			if(info.type=="join"){
				//Toast.show(info.info,{ok:function(){
					createChat(owner);
				//},cancel:function(){}});
			}else if(info.type=="empty"){
				//Toast.show(info.info,{ok:function(){
				Toast.show(info.info,{modal:true,callback:function(){$("#chat_item_s_"+owner).remove();}});
				return;
				
			
			}else{
				Toast.show(info.info,{modal:true});
			}
		}
	});	
}



//1:1채팅 생성
function createChat(user){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	$.post("/chat/createsingle/"+user,p,function(res){
		try{
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				location.href="/chat?t=single&u="+user;				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		}catch(Err){}
	}).fail(function(){});
}

//removeChecked(\"chk-noti\",\"/account/removenoti\",\"선택된 알림을 삭제 하겠습니까?\",dispNotiList(1,\".global-pop__contents-wrap\",noti_popup))
function removeChecked(ele_name, url, msg, callback){
	var val="";
	$("input[name="+ele_name+"]").each(function(){
		if($(this).prop("checked")){
			if(val==""){
				val=$(this).val();
			}else{
				val+=","+$(this).val();
			}
		}
		
	});
	
	if(val==""){
		Toast.show("삭제 할 데이터를 선택 하세요.",{modal:true});
		return;
	}
	
	Toast.show(msg,
		{			
			ok:function(){
				var p={};
				p[window.csrf_name]=window.csrf_val;
				p.val=val;
				$.post(url,p,function(res){
					var data=JSON.parse(res);
					if(data.result=="Y"){
						Toast.show(data.msg,{callback:callback});
						$("#chk-item-all").prop("checked",false);
					}else{
						Toast.show(data.msg,{modal:true});
					}
				});
			},
			cancel:function(){}
		}
	);
}
function openTimeline(doc_idx, open){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.doc_idx=doc_idx;
	p.open=open;
	$.post("/article/open",p, function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			if(open=="Y"){
				
				Toast.show("공개 되었습니다.");
			}else{
				Toast.show("비공개 되었습니다.");
			}
			$("#timeline_"+doc_idx).toggleClass("inactive");
			$(".util-btn.timeline[data-doc-idx="+doc_idx+"]").attr("data-open",open);
		}else{
			Toast.show(data.msg,{modal:true});
		}
	});
}

function removeTimeline(doc_idx){
	Toast.show("삭제 하겠습니까?", {cancel:function(){}, ok:function(){
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.doc_idx=doc_idx;
		
		$.post("/article/remove",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				Toast.show(data.msg,{callback:function(){$("#timeline_"+doc_idx).remove()}});
				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}

function report(doc_idx,nick_name){
	
	var str=
		"<div class='inputbox-modify' style='width:450px'><ul><li><h3>신고대상</h3><div class='input-active input-inactive'><input type='text' value='"+nick_name+"' disabled='' style='padding-left:10px'></div></li>"+
		"<li><h3>분류</h3><div class='input-active'><select id='report_type' class='common-selector'>" +
		"<optgroup><option value='욕설/분란조장'>욕설/분란조장</option><option value='젤리사기'>젤리사기</option><option value='크리에이터사칭'>크리에이터사칭</option><option value='기타'>기타</option></optgroup></select></div></li>" +
		"<li><h3>신고내용</h3><div class='input-active input-active--textarea'><textarea id='report_text' placeholder='500자 이내로 신고하실 내용을 작성해주세요.자세할수록 좋습니다.' max-length=500></textarea></div></li></ul></div>";
	
	
	
	window.report_popup=Popup.show("신고하기",str,function(){
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.doc_idx=doc_idx;
		p.report=$.trim($("#report_text").val());
		p.type=$.trim($("#report_type").val());
		if(!p.report){
			Toast.show("신고내용을 작성 하세요.",{modal:true});
			return;
		}
		
		$.post("/article/report",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				
				Toast.show(data.msg,{callback:function(){window.report_popup.close();}});				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
		
	});
	window.report_popup.setOkButtonText("신고하기");
	
}

function report_chat(room_idx,user_idx,nick_name,chat_type){
	
	var str=
		"<div class='inputbox-modify' style='width:450px'><ul><li><h3>신고대상</h3><div class='input-active input-inactive'><input type='text' value='"+nick_name+"' disabled='' style='padding-left:10px'></div></li>"+
		"<li><h3>분류</h3><div class='input-active'><select id='report_type' class='common-selector'>" +
		"<optgroup><option value='욕설/분란조장'>욕설/분란조장</option><option value='젤리사기'>젤리사기</option><option value='크리에이터사칭'>크리에이터사칭</option><option value='기타'>기타</option></optgroup></select></div></li>" +
		"<li><h3>신고내용</h3><div class='input-active input-active--textarea'><textarea id='report_text' placeholder='500자 이내로 신고하실 내용을 작성해주세요.자세할수록 좋습니다.' max-length=500></textarea></div></li></ul></div>";
	
	
	
	window.report_popup=Popup.show("신고하기",str,function(){
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.room_idx=room_idx;
		p.user_idx=user_idx;
		p.chat_type=chat_type;
		p.report=$.trim($("#report_text").val());
		p.type=$.trim($("#report_type").val());
		if(!p.report){
			Toast.show("신고내용을 작성 하세요.",{modal:true});
			return;
		}
		
		$.post("/chat/report",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				
				Toast.show(data.msg,{callback:function(){window.report_popup.close();}});				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
		
	});
	window.report_popup.setOkButtonText("신고하기");
	
}





function showTimelineImage(target, orientation){
	var winWidth=600;
	var winHeight=$(window).height();
	var ratio=winHeight/winWidth;
	document.querySelectorAll(target).forEach(function(img){
		
		if(img.naturalWidth){
			var $img=$(img);
			img.classList.remove("hide");
			$img.next(".timeline__loading").remove();
			if(orientation==true){
				var css="landscape";
				
				var ratio=winWidth/img.naturalWidth;
				var imgWidth=parseInt(ratio*img.naturalWidth);
				var imgHeight=parseInt(ratio*img.naturalHeight);
				
				
				var maxImgHeight=800;
				if(img.naturalWidth==img.naturalHeight){
					$img.parent().css("height",winWidth+"px");
				}
				else if(imgHeight > maxImgHeight){
					$img.parent().css("height",maxImgHeight+"px");
				}else{
					$img.parent().css("height",imgHeight+"px");
				}
				
				if(img.naturalWidth<img.naturalHeight && imgHeight>=$img.parent().height()){
					css="portrait";
				}
				
				
				
				img.classList.add(css);
			}
			
			
		}else{
			img.addEventListener("load",function(){
				var $img=$(img);
				img.classList.remove("hide");	
				$img.next(".timeline__loading").remove();
				if(orientation==true){
					var css="landscape";
					var ratio=winWidth/img.naturalWidth;
					var imgWidth=parseInt(ratio*img.naturalWidth);
					var imgHeight=parseInt(ratio*img.naturalHeight);
					
					
					var maxImgHeight=800;
					if(img.naturalWidth==img.naturalHeight){
						$img.parent().css("height",winWidth+"px");
					}
					else if(imgHeight > maxImgHeight){
						$img.parent().css("height",maxImgHeight+"px");
					}else{
						$img.parent().css("height",imgHeight+"px");
					}
					
					if(img.naturalWidth<img.naturalHeight && imgHeight>=$img.parent().height()){
						css="portrait";
					}
					
					
					
					img.classList.add(css);	
				}
				
				
			});
		}
	});
	
}


function showImage(target, orientation,parentHeightResize){
	
	document.querySelectorAll(target).forEach(function(img){
		
		if(img.naturalWidth){
			var $img=$(img);
			img.classList.remove("hide");
			$img.next(".timeline__loading").remove();
			if(orientation==true){
				var css="landscape";
				if(img.naturalWidth<img.naturalHeight /*&& img.naturalHeight>=600*/){
					css="portrait";
				}
				
				if(parentHeightResize){
					var h=img.height;
					if(parentHeightResize>h){
						$img.parent().css("height", h+"px");
					}else{						
						$img.parent().css("height", parentHeightResize+"px");
					}
					
				}
				img.classList.add(css);
			}
			
			
		}else{
			img.addEventListener("load",function(){
				var $img=$(img);
				img.classList.remove("hide");	
				$img.next(".timeline__loading").remove();
				if(orientation==true){
					var css="landscape";
					if(img.naturalWidth<img.naturalHeight /*&& img.naturalHeight>=600*/){
						css="portrait";
					}				
					
					if(parentHeightResize){
						var h=img.height;
						if(parentHeightResize>h){
							$img.parent().css("height", h+"px");
							
						}else{
							$img.parent().css("height", parentHeightResize+"px");
						}
						
					}
					img.classList.add(css);		
				}
				
				
			});
		}
	});
	
}

function showVideo(target, orientation){
	
	document.querySelectorAll(target).forEach(function(vid){
		if(vid.videoWidth){
			if(orientation==true){
				var css="landscape";					
				if(vid.videoWidth<vid.videoHeight){
					css="portrait";
				}
				vid.classList.add(css);
			}			
			
			vid.classList.remove("hide");
		}else{
			vid.addEventListener("loadedmetadata",function(){				
				if(orientation==true){
					var css="landscape";					
					if(vid.videoWidth<vid.videoHeight){
						css="portrait";
					}
					vid.classList.add(css);
				}			
				
				vid.classList.remove("hide");
				
				
			});
		}
	});	
}

	


function updateDia(dia){
	$(".coin-data em").html(dia.format());
	
}


//Report 관련


//후원 내역
function recordSupport(frm,what){	
	var param="";
	if(frm!="" && $(frm).length){
		$("#record_form_wrap input, #record_form_wrap select").each(function(){
			var $p=$(this);
			if($p.val()!=""){			
				if(param==""){
					param="?"+$p.attr("name")+"="+$p.val();
				}else{
					param +="&"+$p.attr("name")+"="+$p.val();
				}
			}
		});
	}
	
	$.get("/record/supportlist/"+what+param,function(res){
		var data=JSON.parse(res);
		if(data.result=="N"){
			Toast.show(data.msg,{modal:true});
			return;
		}
		dispRecordSupport(JSON.parse(data.msg), "#record_form_wrap", "#record_data", what);
	});
	
	$(".global-tabs li").removeClass("active");
	$(".global-tabs .support-"+what).addClass("active");
}









//후원자 목록
function recordSupportUsers(frm){
	/*
	var param="";
	if(frm!="" && $(frm).length){
		$("#record_form_wrap input, #record_form_wrap select").each(function(){
			var $p=$(this);
			if($p.val()!=""){			
				if(param==""){
					param="?"+$p.attr("name")+"="+$p.val();
				}else{
					param +="&"+$p.attr("name")+"="+$p.val();
				}
			}
		});
	}*/
	
	$.get("/record/supportusers",function(res){
		var data=JSON.parse(res);
		if(data.result=="N"){
			Toast.show(data.msg,{modal:true});
			return;
		}
		dispRecordSupportUsers(JSON.parse(data.msg), "#record_form_wrap", "#record_data");
	});
	
	$(".global-tabs li").removeClass("active");
	$(".global-tabs .support-list").addClass("active");
}





function stopSupport(idx){
	Toast.show("후원을 중단 하겠습니까?.", {cancel:function(){}, ok:function(){
		
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.idx=idx;
		
		$.post("/record/supportstop",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				Toast.show(data.msg);
				$wrap=$("#stop_support_"+idx).addClass("strong").html("중단 됨");
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}

function terminateMembership(celebIdx, nickName){
	var str=
		"<div class='global-pop__box--withdraw'>"+
		
		"	<div class='global-pop__contents-wrap'>"+
		"		<ol><li>1. 멤버십을 해지 하시면 다음 회차 부터 결제가 자동 중지 됩니다.</li>"+
		"		<li>2. 멤버십 해지를 하셔도 남은 기간 동안은 해당 멤버십이 유지 됩니다.</li><li>3. 해지 후 멤버십 재가입은 멤버십 기간이 종료 된 후에 가능합니다.</li></ol>"+
		"		<div class='checkbox mb15'><input type='checkbox' id='agree_end_membership' name='agree_end_membership'><label for='agree_end_membership' class='checkbox__item'><span class='icon-common'></span><em>해당 내용을 모두 확인했으며, 멤버시 해지에 동의합니다.</em></label></div>"+
		"	</div>"+
		"	<div class='agreement'><button type='submit' class='btn-agreement active btn-terminate-membership' data-celeb-idx='"+celebIdx+"'>멤버십 해지</button></div>"+
		"</div>";
	window.membershipPopup=Popup.show(nickName+"님 멤버십 해지",str);
}


function multiRoomStatus(owner,status){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.owner=owner;
	p.status=status=="Y" ? "N": "Y";
	var message= status=="Y" ? "대화중지 하겠습니까?" : "대화를 재개하겠습니까?";
	Toast.show(message,{ok:function(){
		$.post("/chat/multiroomstatus",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				var nickName=$("#chat_item_m_"+owner+" .chat__nick").html().replace(" - 중지 됨","");
				
				if(p.status=="N"){
					$("#chat_item_m_"+owner+" .chat__nick").html(nickName+" - 중지 됨");
				}else{
					$("#chat_item_m_"+owner+" .chat__nick").html(nickName);
				}
				
				$("#chat_item_m_"+owner+" .util-btn").attr("data-status",p.status);
				Toast.show(data.msg);
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
	
}

function single_chat_out(roomidx,memidx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.room=roomidx;
	
	Toast.show("대화방을 나가겠습니까?<br>나갈경우 대화가 모두 삭제되어<br>상대방도 확인 할 수 없습니다.",{ok:function(){
		$.post("/chat/singlechatout",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				$("#chat_item_s_"+memidx).remove();
				Toast.show(data.msg,{modal:true,callback:function(){location.href="/chat"}});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}

function single_chat_deny(useridx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.user_idx=useridx;
	
	Toast.show("차단하겠습니까?",{ok:function(){
		$.post("/chat/denysingle",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{modal:true});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}


function subscribeAuto(celeb_idx, callback){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.celeb_idx=celeb_idx;
	
		
	$.post("/member/subscribe",p,function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){			
			$(".subscribe-btn[data-celeb-idx="+celeb_idx+"]").addClass("active").html("구독중");
			$(".celeb-inner__btn[data-celeb-idx="+celeb_idx+"]").addClass("active");
			if(!callback){
				Toast.show("구독 되었습니다.");
			}else{
				callback();
			}
		}else{
			Toast.show(data.msg,{modal:true});
		}
	});
}
function setPopId(target, pop, id){
	target.attr("data-pop-id",id);
	target.find("*").attr("data-pop-id",id);
	pop.attr("data-pop-id",id);
}

function kspayEnd(){
	if(window.payMethodPop){
		window.payMethodPop.close();
		window.payMethodPop=null;
	}
	$.get("/account/simpleinfo",function(res){
		if(res=="")return;
		var data=JSON.parse(res);
		if(data.result=="Y"){
			var info=JSON.parse(data.msg);
			$(".coin-data em").html(info.dia.format());
			$(".util-btn.my-icon").attr("data-noti",info.noti);
			if(info.noti>9){
				$(".util-btn.my-icon em").html("9+").removeClass("hide");
			}else if(info.noti==0){
				$(".util-btn.my-icon em").html("").addClass("hide");
			}else{
				$(".util-btn.my-icon em").html(info.noti).removeClass("hide");
			}
			
		}
	});
	mcancel();
}



function viewComments(doc_idx){
	
	var popup=Popup.show("댓글","<div style='width:520px;height:640px'><iframe id='comments_frame' src='/article/comments/"+doc_idx+"' width='100%' height='100%' frameborder='0' style='height:100%'></iframe></div>");
	
}






function removeComment(comm_idx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.comm_idx=comm_idx;

	
	$.post("/article/removecomment",p, function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			
			Toast.show(data.msg,{callback:function(){
					
					var delObj=$("li[data-comm-idx="+comm_idx+"]");
					var id=delObj.attr("id");
					if(!id){//대 댓글
						id=delObj.attr("data-header");
						var headerNo=id.replace("header_","");
						var replyCount=parseInt($("#count_"+headerNo).html());
						if(replyCount==1){
							$("#reply_area_"+headerNo+" button").remove();
						}else{
							$("#count_"+headerNo).html(replyCount-1);
						}
					}
					delObj.remove();
				}
			});				
		}else{
			Toast.show(data.msg,{modal:true});
		}
	});
}
function reportComment(comm_idx,nick_name){
	
	var str=
		"<div class='inputbox-modify' style='width:450px'><ul><li><h3>신고대상</h3><div class='input-active input-inactive'><input type='text' value='"+nick_name+"' disabled='' style='padding-left:10px'></div></li>"+
		"<li><h3>분류</h3><div class='input-active'><select id='report_type' class='common-selector'>" +
		"<optgroup><option value='욕설/분란조장'>욕설/분란조장</option><option value='젤리사기'>젤리사기</option><option value='크리에이터사칭'>크리에이터사칭</option><option value='기타'>기타</option></optgroup></select></div></li>" +
		"<li><h3>신고내용</h3><div class='input-active input-active--textarea'><textarea id='report_text' placeholder='500자 이내로 신고하실 내용을 작성해주세요.자세할수록 좋습니다.' max-length=500></textarea></div></li></ul></div>";
	
	
	
	window.report_popup=Popup.show("신고하기",str,function(){
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.comm_idx=comm_idx;
		p.report=$.trim($("#report_text").val());
		p.type=$.trim($("#report_type").val());
		if(!p.report){
			Toast.show("신고내용을 작성 하세요.",{modal:true});
			return;
		}
		
		$.post("/article/reportcomment",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				
				Toast.show(data.msg,{callback:function(){window.report_popup.close();}});				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
		
	},"rgba(0,0,0,0)");
	window.report_popup.setOkButtonText("신고하기");


	
}



function loginform(){
	
	window.loginformPopup=Popup.show("로그인","<div style='width:520px;height:500px'></div>");
	$.get("/account/loginform",function(res){
		var str="<div style='width:520px;height:500px'>"+res+"</div>";
		window.loginformPopup.setContent(str);
	});
}

function closeLoginform(){
	window.loginformPopup.close();
	
}

function buyDocument(btn, doc_idx){
	if(!window.login){
		Toast.show("로그인 후 구매가 가능합니다.<br>로그인 하겠습니까?",{ok:function(){
			loginform();
		},buttonOk:"로그인"});
		return;
	}
	Toast.show("구매 하겠습니까?",{ok:function(){
		var $btn=$(btn);
		$btn.attr("disabled","disabled");
		var oldHtml=$btn.html();
		$btn.html("<img src='/assets/img/Spinner-1s-61px.svg' style='width:50px'>");
		buyDocInner(doc_idx,function(){
			$btn.html(oldHtml);
			$btn.removeAttr("disabled");
		});
		
	}, buttonOk:"구매"});
}

function viewBuyDocument(btn, doc_idx){
	var $btn=$(btn);
	$btn.attr("disabled","disabled");
	var oldHtml=$btn.html();
	$btn.html("<img src='/assets/img/Spinner-1s-61px.svg' style='width:50px'>");
	buyDocInner(doc_idx,function(){
		$btn.html(oldHtml);
		$btn.removeAttr("disabled");
	});
}

function buyDocInner(doc_idx,callback){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.doc_idx=doc_idx;
	$.post("/article/buydoc",p,function(res){
		var r=JSON.parse(res);
		if(r.result=="Y"){
			var data=JSON.parse(r.msg);
			var doc=data.doc;
			$(".header__coin em").html(data.dia.format());
			var article=makeTimelineDoc(doc,"",true,false,false,true);
			$("#timeline_"+doc_idx).html(article);
			showTimelineImage(".timeline__contents  img.orientation",true,600);
			showImage(".orientation.hide",true);
			
			
			$("#message_"+doc_idx+" > p").each(function(){
				
				var $obj=$(this);
				
				if($obj.prop("offsetHeight") < $obj.prop("scrollHeight")){
					$obj.after("<a href='javascript:;' class='timeline__message__more'>더보기</a>");
				}
			});
			
			var obj=$("#_content_"+doc_idx);
			
			if(obj.length){
				var swiper=new Swiper("#_content_"+doc_idx, {
						autoHeight : true,
		    			pagination: {
		    		        el: '.swiper-pagination_list',
		    		        clickable: true,
		    		        renderBullet: function (index, className) {
		    		          return '<span id="bullet_'+doc_idx+'" class="' + className + ' swiper-pagination-bullet-reset"></span>';
		    		        }
		    		        
		    			},
		    			navigation: {
	    		            nextEl: '.swiper-button-next',
	    		            prevEl: '.swiper-button-prev',
	    		        }
	    		        
	    			}
				);
				$("#_content_"+doc_idx).on("mouseover",function(){
					var $content=$(this);
					var content_idx=$content.attr("id").replace("_content_","");
					$("#sw_btn_prev_"+content_idx).removeClass("hide");
					$("#sw_btn_next_"+content_idx).removeClass("hide");
					$("#sw_pagination_"+content_idx).removeClass("hide");
					
					
				});
				$("#_content_"+doc_idx).on("mouseout",function(){
					var $content=$(this);
					var content_idx=$content.attr("id").replace("_content_","");
					$("#sw_btn_prev_"+content_idx).addClass("hide");
					$("#sw_btn_next_"+content_idx).addClass("hide");
					$("#sw_pagination_"+content_idx).addClass("hide");
				});
				
			}
			
		}else if(r.result=="C"){
			Toast.show(r.msg,{ok:function(){
				location.href="/shop";
			},cancel:function(){
				callback();
			
			}});
		}else{
			callback();
			
			Toast.show(r.msg,{modal:true});
		}
	}).fail(function(){
		callback();		
	});
}
function downloadAttachFileInfo(doc_idx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.doc_idx=doc_idx;
	$.post("/article/buyfileinfo",p,function(res){
		var r=JSON.parse(res);
		if(r.result=="Y"){
			
			var info=JSON.parse(r.msg);
			var str="<div class='download-ui' style='width:500px;margin-top:10px'>"+
				"<div style='font-size:1.3em;font-weight:500'>파일명</div>"+
				"<div class='download-ui__desc'>"+info.file_name+"</div>"+
				"<div style='font-size:1.3em;font-weight:500;margin-top:30px'>컨텐츠 설명</div>"+
				"<div class='download-ui__desc' style='line-height:1.6em'>"+info.content+"</div>";
				//"<div class='report-wrap'><button class='btn-report-download'><span class='icon-common'></span>불량컨텐츠 신고</button></div>"+
			if(info.membership_free){
				str+="<button class='btn-bottom active' onclick='downloadAttachFile(\""+doc_idx+"\");window.downloadpopup.close();'>멤버십회원 <em>무료</em> 다운로드</button>"+
				"</div>";
			}
			else if(info.free){
				str+="<button class='btn-bottom active' onclick='downloadAttachFile(\""+doc_idx+"\");window.downloadpopup.close();'>컨텐츠 <em>무료</em> 다운로드</button>"+
				"</div>";
			}else{
				str+="<button class='btn-bottom active' onclick='downloadAttachFile(\""+doc_idx+"\");window.downloadpopup.close();'><img src='/assets/img/jelly_s"+info.jelly+".png' ><em>"+info.price+"</em>개로 컨텐츠 다운로드</button>"+
				"</div>";
			}
				
			
			
			
			window.downloadpopup=Popup.show("유료 다운로드 컨텐츠",str);
		}else{
			Toast.show(r.msg,{modal:true});
		}
	});
}


function downloadAttachFile(doc_idx){
	if(!window.login){
		Toast.show("로그인 후 다운로드가 가능합니다.<br>로그인 하겠습니까?",{ok:function(){
			loginform();
		},buttonOk:"로그인"});
		return;
	}
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.doc_idx=doc_idx;
	$.post("/article/buyfile",p,function(res){
		var r=JSON.parse(res);
		if(r.result=="Y"){
			var data=JSON.parse(r.msg);
			var $frame=$("#download_frame");
			if($frame.length){
				$frame.attr("src","/file/donwloadattached/"+data.file_idx);
			}else{
				
				$("body").append("<iframe id='download_frame' style='width:0px;height:0px;border:0' src='/file/donwloadattached/"+data.file_idx+"'></iframe>")
			}
			$(".header__coin em").html(data.dia.format());

		}else if(r.result=="C"){
			Toast.show(r.msg,{ok:function(){
				location.href="/shop";
			},cancel:function(){
				
			}});
		}else{
			
			Toast.show(r.msg,{modal:true});
		}
	}).fail(function(){
		
	});
	return;
	
}

function adultVerify(){
	
	if(window.login){
		window.open("/adultauth/startmobile","_auth","width=445, height=580, resizable=0");
	}else{
		Toast.show("로그인 후 인증이 가능 합니다.",{ok:function(){loginform();},buttonOk:"로그인"});
	}
}



function votedShow(){
	
	
	
	var goodDiv=$('<div class="fadein" style="display:block;z-index:99999"><img src="/assets/img/fadein1.png" alt=""></div>');
	$("body").append(goodDiv);
	
	goodDiv.fadeIn(400,function(){
		goodDiv.fadeOut(400,function(){
			goodDiv.fadeIn(400,function(){
				goodDiv.fadeOut(400,function(){
					goodDiv.remove();
				});
			});
		});
	});
	
}