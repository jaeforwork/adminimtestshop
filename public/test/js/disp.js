function dispSearchCelebs(data,wrap){
	if(data.list.length){
		var str="";
		for(var i=0;i<data.list.length;i++){
			var celeb=data.list[i];
			var cssActive=celeb.is_my_celeb=="Y" ? "active":"";
			var strSubscribe=celeb.is_my_celeb=="Y" ? "구독중":"구독";
			
			var btnMembership="";
			if(celeb.is_membership=="Y"){
				
				btnMembership="<button class='btn-mbs-active'>멤버십</button>";
			}
			
			var nickName=celeb.nick_name;			
			var tagtemp=celeb.tag;
			
			
			
			var tagList=tagtemp ? tagtemp.split(" ") : [];
			
			var tag="";
			for(var k=0;k<tagList.length;k++){
				tagList[k]="<a href='/search/memtag?q="+tagList[k]+"' class='active'>"+tagList[k]+"</a>";

				
			}
			tag=tagList.join(" ");		
			
			var inStr=
				"<li class='celeb-list__item'>"+
				"	<div class='celeb-list__pic-box'>"+
				"		<a href='/home/"+celeb.nick_name+"' class='link-silence'><img src='"+celeb.profile_image+"' class='orientation hide'></a>"+
				"	</div>"+
				"	<div class='celeb-list__nick-box'>"+
				"		<span class='celeb-list__info'><a href='/home/"+celeb.nick_name+"' class='celeb-list__nick'>"+nickName+"</a></span>"+
				"		<div class='celeb-list__heart-count'>구독자<em>"+celeb.subscriber.format()+"</em>받은하트<em>"+celeb.heart.format()+"</em></div>"+
				"		<div class='celeb-category'>"+tag+"</div>"+				
				"	</div>"+
				"	<div class='celeb-inner__utility celeb-inner__utility--list'>"+
				"		<span class='celeb-inner__btn "+cssActive+"' data-celeb-idx='"+celeb.mem_idx+"'>"+
				"			<button class='dm-btn go-chat-single' data-chat='"+celeb.mem_idx+"'><span class='icon-common'></span></button>"+
				"			<button class='chat-btn go-chat-multi' data-chat='"+celeb.mem_idx+"'><span class='icon-common'></span></button>"+						
				"		</span>"+
				"		<button class='subscribe-btn "+cssActive+"' data-celeb-idx='"+celeb.mem_idx+"'>"+strSubscribe+"</button>"+btnMembership+
				"	</div>"+
				"</li>";
			str+=inStr;
		}
		
		$(wrap).append(str);
		showImage(".orientation.hide",true);		
		
	}
}

function dispSideSubscribe(data,wrap,title){
	if(!title){
		title="구독중인 크리에이터";
	}
	if(data.list.length){
		var str="<h2 class='aside-title'>"+title+"</h2><ul>";
		for(var i=0;i<data.list.length;i++){
			var subdata=data.list[i];
			
			var btnSubscriber="";
			if(subdata.is_membership=="Y"){
				btnSubscriber="<button class='btn-mbs-active'>멤버십</button>";
			}else if(subdata.is_my_celeb=="Y"){
				btnSubscriber="<button class='subscribe-btn active' data-celeb-idx='"+subdata.mem_idx+"'>구독중</button>";
			}else{
				btnSubscriber="<button class='subscribe-btn' data-celeb-idx='"+subdata.mem_idx+"'>구독</button>";
			}
			
			
			var instr="<li class='aside__item'>"+
				"<div class='aside__pic-box'>"+
				"	<a href='/home/"+subdata.nick_name+"' class='link-silence'><img src='"+subdata.profile_image+"' alt='"+subdata.nick_name+"' class='profile-image orientation hide' ></a>"+
				"</div>"+
				(subdata.is_my_celeb=="Y" ? "<div class='aside__pic-box--bg'></div>" : "")+
				"<div class='aside__nick-box'>"+
				"	<a href='/home/"+subdata.nick_name+"' class='aside__nick link-silence'>"+subdata.nick_name+"</a>"+
				"	<div class='aside__heart-count'><span class='icon-common'></span><em>"+subdata.heart.format()+"</em></div>"+
				"</div>"+
				"<div class='subscribe'>"+btnSubscriber
				
				"</div>"+
			"</li>";
			str+=instr;
		}		
		str+="</ul>";
		if(title!="급상승 인기 크리에이터"){
			str+="<div class='default-more'><a href='/creator' class='link-silence'>전체보기</a></div>";
		}
		$(wrap).html(str).removeClass("hide");
		$(".orientation.hide").each(function(){
			var $obj=$(this);
			var src=$obj.attr("src");
			getImageSize(src,function(w,h){
				if(w<0 || h<0)return;
				var css="landscape";
				if(w<h){
					css="portrait";
				}

				$obj.removeClass("hide").addClass(css);
			});
		});
	}
}




function dispTimeline(data,wrap,unum, mypage){
	if(data.list.length){
		var str="";
		var adultIndex=-1;
		if(!mypage ){
			adultIndex=data.show_adt_index;
		}
		for(var i=0;i<data.list.length;i++){
			
			
			var doc=data.list[i];
			var article="";
			if(doc.doc_idx){
				if($("#timeline_"+doc.doc_idx).length)continue;
				article=makeTimelineDoc(doc,unum,data.login,data.query_list,mypage);
			}
			str+=article;
			if(adultIndex==i){
				str+=dispAdult(data.login);
			}
		}
		
		
		$(wrap).append(str);
		
		
		
		showTimelineImage(".timeline__contents  img.orientation",true,600);
		showImage(".orientation.hide",true);
		//showVideo(".video-js.video-orientation");
		
				
		for(var i=0;i<data.list.length;i++){
			var doc_idx=data.list[i].doc_idx;
			
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
			
		}
		
	}
}


function makeTimelineDoc(doc,unum,login,query_list,mypage,stripBuy){
	
	
	var link="";
	
	if(doc.link_info_list.length){
		link="<span class='link-info-wrap'>";
		for(var i=0;i<doc.link_info_list.length;i++){ 
			link+="<a href='"+doc.link_info_list[i].link+"' target='"+doc.link_info_list[i].link_target+"' class='link-info'>"+doc.link_info_list[i].link_title+"</a>";
		}
		link+="</span>";
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
	var dataTextCss="text-content";
	if(!mypage && !stripBuy){
		dataTextCss="";
	}
	if( !mypage && !stripBuy && doc.price>0){
		
		if(doc.mov_encode=="N"){
			media="<div class='loading'><div class='loading__img'><img src='/assets/img/Spinner-1s-101px.svg' ></div><div class='loading__text'>인코딩 중입니다.</div></div>";
		}else{
			if(doc.free_for_membership || doc.buy || doc.is_mine){
				var buttonText="구매 컨텐츠 보기";
				if(doc.free_for_membership){
					buttonText="멤버십회원 무료 컨텐츠 보기"
				}else if(doc.is_mine){
					buttonText="컨텐츠 보기";
				}
				if(doc.poster){
					media="<button class='btn-purchase buy-already' type='button' onclick='viewBuyDocument(this,\""+doc.doc_idx+"\")'>"+buttonText+"</button>" +
						"<div class='timeline__contents poster'>"+
						"<img src='"+doc.poster+"' class='btn-dblclick' data-doc-idx='"+doc.doc_idx+"'>"+
						"</div>";
				}else{
					media="<button class='btn-purchase buy-already-jelly' type='button' onclick='viewBuyDocument(this,\""+doc.doc_idx+"\")'>"+buttonText+"</button>" +
					"<div class='timeline__contents none-thumb btn-dblclick' style='max-height: inherit; height: 600px;' data-doc-idx='"+doc.doc_idx+"'>"+
					"<img src='/assets/img/jelly"+doc.jelly+".png' >"+
					"</div>";
				}
			}else{
				if(doc.poster){
					media="<button class='btn-purchase' type='button' onclick='buyDocument(this,\""+doc.doc_idx+"\")'>"+doc.price+"젤리로 구매하기</button>" +
						"<div class='timeline__contents poster' >"+
						"<img src='"+doc.poster+"' class='btn-dblclick' data-doc-idx='"+doc.doc_idx+"'>"+
						"</div>";
				}else{
					media="<button class='btn-purchase' type='button' onclick='buyDocument(this,\""+doc.doc_idx+"\")'>"+doc.price+"젤리로 구매하기</button>" +
					"<div class='timeline__contents none-thumb btn-dblclick' style='max-height: inherit; height: 600px;' data-doc-idx='"+doc.doc_idx+"'>"+
					"<img src='/assets/img/jelly"+doc.jelly+".png' >"+
					"<p class='none-thumb__desc'>유료 컨텐츠입니다.<em>젤리로 구매하실 수 있습니다.</em></p>"+
					"</div>";
				}
			}
		}
		
	}else if(doc.files[0]){
		dataTextCss="";
		if(doc.files[0].file_type=="image"){
			if(doc.files.length==1){
				//media="<div class='timeline__contents' style='max-height:inherit'><img src='"+doc.files[0].url+"' class='orientation btn-all-media' data-all-media='"+allMedia+"'></div>";
				media="<div class='timeline__contents' style='max-height:inherit'><img src='"+doc.files[0].url+"' class='orientation btn-dblclick' data-doc-idx='"+doc.doc_idx+"' ></div>";
			}else{
				
				media="<div class='swiper-container' id='_content_"+doc.doc_idx+"'><div class='swiper-wrapper'>";
				for(var k=0;k<doc.files.length;k++){
					//media+="<div class='swiper-slide' style='max-height:inherit'><div class='timeline__contents swiper' style='max-height:inherit'><img src='"+doc.files[k].url+"' class='orientation btn-all-media' data-all-media='"+allMedia+"'></div></div>";
					media+="<div class='swiper-slide' style='max-height:inherit'><div class='timeline__contents swiper' style='max-height:inherit'><img src='"+doc.files[k].url+"' class='orientation btn-dblclick' data-doc-idx='"+doc.doc_idx+"'></div></div>";
				}
				
				media+="</div><div class='swiper-pagination_list hide' id='sw_pagination_"+doc.doc_idx+"' ></div><div class='swiper-button-prev my-swipe-prev hide' style='pointer-events: inherit !important;' id='sw_btn_prev_"+doc.doc_idx+"' data-id='"+doc.doc_idx+"'><img src='/assets/img/icon_arrow_left.png'></div><div class='swiper-button-next my-swipe-next  hide' style='pointer-events: inherit !important;' id='sw_btn_next_"+doc.doc_idx+"' data-id='"+doc.doc_idx+"'><img src='/assets/img/icon_arrow_right.png'></div>" +
						"<div class='my-swiper-arrow-bg left' id='sw_btn_prev_bg_"+doc.doc_idx+"' data-id='"+doc.doc_idx+"'></div><div class='my-swiper-arrow-bg right' id='sw_btn_next_bg_"+doc.doc_idx+"' data-id='"+doc.doc_idx+"'></div></div>";
			}
		}else if(doc.files[0].file_type=="mov"){
			if(doc.files[0].encode=="Y"){
				var path=null;
				if(doc.files[0].thumb_upload){
					path=doc.files[0].thumb_upload;
				}else if(doc.files[0].thumb){
					path=doc.files[0].thumb;
				}
				if(path){ 
					media="<div class='timeline__contents' style='max-height:600px' id='vid_thumb_"+doc.files[0].file_idx+"'><img src='"+path+"' class='orientation '><img src='/assets/img/btn_play.png' class='btn_vid_play' data-idx='"+doc.files[0].file_idx+"' ></div>";					
				}else{				
					media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:337px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"'></iframe></div>";
				}
				
			}else{
				media="<div class='loading'><div class='loading__img'><img src='/assets/img/Spinner-1s-101px.svg' ></div><div class='loading__text'>인코딩 중입니다.</div></div>";
			}
			
			
		}else if(doc.files[0].file_type=="audio"){
			media="<div class='gallery__item'><iframe frameborder='0' style='width:100%;height:227px;' scrolling='no' src='/article/viewmedia/"+doc.files[0].file_idx+"'></iframe></div>";
		}
	}
			
	
	var subscriber="";
	var globalBtn="";
	var resetClass="reset";
	if(!mypage ){
		if(doc.can_sub){//구독 가능?
			
			/*
			if(doc.subscribe=="Y"){
				if(doc.is_membership=="Y"){
					subscriber="<button type='button' class='timeline__subscribe subscribe-btn mbs-active' data-celeb-idx='"+doc.mem_idx+"'>멤버십</button>";
				}else{
					subscriber="<button type='button' class='timeline__subscribe subscribe-btn active' data-celeb-idx='"+doc.mem_idx+"'>구독중</button>";
				}
			}else{
				subscriber="<button type='button' class='timeline__subscribe subscribe-btn' data-celeb-idx='"+doc.mem_idx+"'>구독</button>";
			}*/
			
			if(doc.subscribe=="Y"){				
				subscriber="<button type='button' class='timeline__subscribe subscribe-btn active' data-celeb-idx='"+doc.mem_idx+"'>구독중</button>";
				
			}else{
				subscriber="<button type='button' class='timeline__subscribe subscribe-btn' data-celeb-idx='"+doc.mem_idx+"'>구독</button>";
			}
			
			if(doc.is_membership=="Y"){
				subscriber+=" <button type='button' class='btn-mbs-active'>멤버십</button>";
			}
			resetClass="";
		}
		globalBtn+="<button type='button' class='global__dm-btn go-chat-single' data-chat='"+doc.mem_idx+"'><span class='icon-common'></span></button>";
		var supportClass="";
		if(doc.is_support=="Y"){
			supportClass="active";
		}
		if(!window.review){
			//globalBtn+="<button class='global__donate-btn "+supportClass+" btn_give_dia' data-celeb-idx='"+doc.mem_idx+"' data-type='일반'><span class='icon-common'></span></button>";
		}
		globalBtn+="<button type='button' class='global__chat-btn go-chat-multi' data-chat='"+doc.mem_idx+"'><span class='icon-common'></span></button>";
		
	}
	
	if(doc.attach_zip=="Y"){
		globalBtn+="<button class='global__download-btn' onclick='downloadAttachFileInfo(\""+doc.doc_idx+"\")'><span class='icon-common'></span></button>";
	}
	
	
	var utilType="timeline";
	if(mypage || doc.is_mine){
		utilType="mypage"
	}
	
	var heartCss="";
	if(doc.heart_checked=="Y"){
		heartCss="active";
	}
	var inactiveCss="";
	if(doc.open!="Y"){
		inactiveCss="inactive";
	}
	
	var time=doc.regdate ? getDateStr2(doc.regdate):"";
	
	var tagInfo="";
	var tagMember="";
	if( doc.hashtag){
		var taglist=doc.hashtag.split(" ");
		
		
		tagInfo="";
		if(taglist.length){
			tagInfo="<div class='tag'>";
			for(var k=0;k<taglist.length;k++){
				if(unum){
					tagInfo+="<a href='/tags/"+taglist[k]+"/"+unum+"' >#"+taglist[k]+"</a> ";
				}else{
					tagInfo+="<a href='/tags/"+taglist[k]+"' >#"+taglist[k]+"</a> ";
				}
			}
			tagInfo+="</div>";
		}
		
	}
	var cssEmptyTag="none-tag";
	if( doc.tag){
		var taglist=doc.tag.split(" ");
		
		
		
		if(taglist.length){
			tagMember="<p class='celeb-category'>";
			for(var k=0;k<taglist.length;k++){
				
				tagMember+="<a href='/search/memtag?q="+taglist[k]+"' >"+taglist[k]+"</a> ";
				
			}
			tagMember+="</p>";
			cssEmptyTag="";
		}
		
	}
	
	var content=doc.content;
	
	if(query_list){
		for(var j=0;j<query_list.length;j++){
			//tagtemp=tagtemp.split(data.query_list[j]).join("<bm>"+data.query_list[j]+"</bm>");
			//nickName=nickName.split(data.query_list[j]).join("<nm>"+data.query_list[j]+"</nm>");
			var pattern=new RegExp(query_list[j],"gi");
			
			content=content.replace(pattern,"<bm>"+query_list[j]+"</bm>");
			
		}
	}
	var userStat="<p class=stats-count>구독자<em>"+doc.sub_count.format()+"</em>게시물<em>"+doc.document+"</em></p>";
		
	var instr="<article class='timeline__item "+inactiveCss+" "+doc.mem_idx+"' id='timeline_"+doc.doc_idx+"'>"+			
					"<div class='timeline__item-top'>";
	if(doc.membership=="Y"){
		instr+="<button class='btn-mbs-pass' data-mem-idx='"+doc.mem_idx+"'>멤버십 가입</button>";
	}
	instr+="<button class='util-btn timeline' data-login='"+login+"' data-mem-idx='"+doc.mem_idx+"' data-doc-idx='"+doc.doc_idx+"' data-type='"+utilType+"' data-open='"+doc.open+"' data-nick_name='"+doc.nick_name+"'><span class='icon-common'></span></button>";
							
	//instr+="	<div class='util-popup-wrap util-popup-wrap--timeline'></div>"+
	
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
	
	instr+=
		"	<div class='timeline__info'>"+
		"		<div class='timeline__pic-box'>"+
		"			<a href='/home/"+doc.nick_name+"' ><img src='"+doc.profile_image+"' alt='"+doc.nick_name+"' class='orientation hide"+(mypage ? " my-profile-image":"")+"'></a>"+
		"		</div>"+
		"		<div class='timeline__nick-box "+cssEmptyTag+"'>"+							
		"			<a href='/home/"+doc.nick_name+"' class='timeline__nick '>"+doc.nick_name+"</a>"+
		"			"+userStat+
		"		</div>"+
		"	</div>"+
		
		"</div>"+
		"<div class='timeline__contents-wrap'>"+media+"</div>"+
		"<div class='timeline__item-bottom'>"+
		"	<div class='timeline__bottom-left'>"+subscriber+							
		"		<span class='timeline__date "+resetClass+"'>"+time+"</span>"+
		"	</div>"+
		"	<div class='timeline__bottom-right'>"+
		"		<button type='button' class='btn-favor-doc "+btnHeartCss+" active-anim "+heartCss+"' data-doc-idx='"+doc.doc_idx+"' data-celeb-idx='"+doc.mem_idx+"'><span class='icon-common'></span></button><em class='global__heart-count'>"+doc.heart+"</em>"+
		"		"+globalBtn+							
		"	</div>"+
		"</div>";
	if(mypage && (doc.price>0 || doc.attach_zip=="Y")){
		instr+="<div class='timeline__paid-count'>";
		if(doc.price>0){
			instr+="<span>유료 컨텐츠<em><img src='/assets/img/jelly_s"+doc.jelly+".png' >"+doc.price+"개</em><em class='count'>구매 "+doc.buy_count+"건</em></span>";
		}
		if(doc.attach_zip=="Y"){
			var dl_price=doc.dl_price ? doc.dl_price:0;
			var dl_buy_count=doc.dl_buy_count ? doc.dl_buy_count:0;
			instr+="<span>다운로드 컨텐츠<em><img src='/assets/img/jelly_s"+doc.jelly2+".png'>"+dl_price+"개</em><em class='count'>구매 "+dl_buy_count+"건</em></span>";
		}
		instr+="</div>";
	}	
	instr+="	<div class='timeline__message' id='message_"+doc.doc_idx+"'>"+
		"		<p class='"+dataTextCss+"'>"+content+"</p>"+link+tagInfo+							
		"	</div>";
	instr+="<div class='timeline__comment-box'>";
	instr+="<div class='comment__inp-box ta'><textarea placeholder='댓글달기...' autocomplete='off' autocorrect='off' id='input_comm_"+doc.doc_idx+"' onkeyup='commInputResize(this)'></textarea><button type='button' class='btn-write inactive' disabled onclick='writeComment(\""+doc.doc_idx+"\")'>작성</button></div>";
	instr+="<ul class='comment__list' id='wrap_comm_"+doc.doc_idx+"'>";
	for(var ci=0;ci<doc.comment_list.length;ci++){
		var comment=doc.comment_list[ci];
		
		var floatButton="";
		var addCss="";
		if(comment.reportable){
			addCss="class='pad_r60'";
			floatButton="<div class='comment__floating'><button class='btn-comment-deny' onclick='member_deny(\""+comment.mem_idx+"\",\"C\")' title='차단'><span class='icon-common'></span></button><button class='btn-comment-report' onclick='reportComment(\""+comment.comm_idx+"\",\""+comment.nick_name+"\")' title='신고'><span class='icon-common'></span></button></div>";
		}
		if(comment.deletable){
			floatButton="<div class='comment__floating'><button class='btn-comment-delete' onclick='removeComment(\""+comment.comm_idx+"\")'><span class='icon-common'></span></button></div>";
		}
		var report_css=comment.reported ? "reported":"";
		
		instr+="<li id='header_"+comment.header+"' data-comm-idx='"+comment.comm_idx+"' "+addCss+"><a href='/home/"+comment.nick_name+"' class='comment__writer'>"+comment.nick_name+"</a><span class='comment__text "+report_css+"'>"+comment.comment+"</span>"+floatButton+"</li>";
	}
	instr+="</ul>";
	if(doc.comment_list.length){
		instr+="<div><a href='javascript:;' onclick='viewComments(\""+doc.doc_idx+"\")' class='comment__more'>전체 댓글 보기</a></div>";
	}
	instr+="</div></article>";
	return instr;
}

function dispAdult(){
	var str="<article class='timeline__19banner'><div class='timeline__contents-wrap'><button class='btn-purchase' onclick='adultVerify()'>성인인증하기</button><div class='timeline__contents' style='max-height: inherit; height: 540px;'><img src='/assets/img/19adult.png' alt=''><p class='banner__desc'><img src='/assets/img/19adult_desc.png' alt=''></p></div></div></article>";
	return str;
}
function commInputResize(obj){	
	$(obj).css("height","21px");
	$(obj).css("height",obj.scrollHeight+"px");
	var text=$.trim(obj.value);
	var btn=$(obj).next();
	if(text==""){
		btn.prop("disabled",true).addClass("inactive");
	}else{
		btn.prop("disabled",false).removeClass("inactive");
	}
}

function writeComment(doc_idx){
	
	if(checkProcessing()){		
		return false;
		
	}
	
	var obj=$("#input_comm_"+doc_idx);
	var comment=$.trim(obj.val());
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.doc_idx=doc_idx;
	p.comment=comment;
	$.post("/comment/write",p, function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			obj.val("");
			commInputResize(obj);
			var comment=JSON.parse(data.msg);
			
			$("#wrap_comm_"+doc_idx).prepend("<li id='header_"+comment.header+"' data-comm-idx='"+comment.comm_idx+"'><a href='/home/"+comment.nick_name+"' class='comment__writer'>"+comment.nick_name+"</a><span class='comment__text'>"+comment.comment+"</span><div class='comment__floating'><button class='btn-comment-delete' onclick='removeComment(\""+comment.comm_idx+"\")'><span class='icon-common'></span></button></div></li>");
			resetProcessing();
		}else{
			Toast.show(data.msg,{modal:true,callback:resetProcessing});
		}
	});
}

function dispCelebList(data,wrap, mypage){
	
	if(data.page==1 && !data.has_next){
		$(wrap).html("<article class='celeb-inner-wrap'><div class='celeb-none'>현재 구독중인 크리에이터가 없습니다.</div></article>");
	}
	
	if(data.list.length){
		var str="";
		var subscriberClass="active";
		var subscriberText="구독중";
		if(data.is_new=="Y"){
			subscriberClass="";
			subscriberText="구독";
		}
		
		for(var i=0;i<data.list.length;i++){
			var celeb=data.list[i];
			if($("#celeb_"+celeb.mem_idx).length)continue;
			var supportClass="";			
			if(celeb.is_support=="Y"){
				supportClass="active";			
			}
			var btnMembership="";
			if(data.is_new!="Y"){
				if(celeb.is_membership=="Y"){
					subscriberClass="mbs-active";
					subscriberText="멤버십";
					btnMembership="<button class='btn-mbs-active'>멤버십</button>";
				}
				
				
				subscriberClass="active";
				subscriberText="구독중";
				
			}
			var tagInfo="";
			if(celeb.taglist && celeb.taglist.length){
				tagInfo="<p class='celeb-category'>";
				for(var k=0;k<celeb.taglist.length;k++){
					tagInfo+="<a href='/search/memtag?q="+celeb.taglist[k]+"'>"+celeb.taglist[k]+"</a>";
				}
				tagInfo+="</p>";
			}
			
			var inStr=
						"<article class='celeb-inner-wrap' id='celeb_"+celeb.mem_idx+"'>"+
							"<div class='celeb-inner'>"+
							"	<div class='celeb-inner__pic-box'>"+
							"		<a href='/home/"+celeb.nick_name+"' class='link-silence'><img src='"+celeb.profile_image+"' alt='"+celeb.nick_name+"' class='orientation hide'></a>"+
							"		<div class='celeb-inner__pic-box--bg'></div>"+
							"	</div>"+
							"	<div class='celeb-inner__infobox'>"+
							"		<a href='/home/"+celeb.nick_name+"' class='celeb-inner__nick link-silence'>"+celeb.nick_name+"</a>"+
							"		"+tagInfo+
							"		<p class='celeb-inner__intro'>"+celeb.comment+"</p>"+
							"		<div class='celeb-inner__stats'>"+
							"			<span>구독자<em>"+celeb.subscriber.format()+"</em></span>"+
							"			<span>받은하트<em>"+celeb.heart.format()+"</em></span>"+
							"			<span>게시물<em>"+celeb.document.format()+"</em></span>"+
							"		</div>"+
							"		<div class='celeb-inner__utility'>"+
							"			<button class='dm-btn go-chat-single' data-chat='"+celeb.mem_idx+"' title='1:1대화'><span class='icon-common'></span></button>"+
							"			<button class='chat-btn go-chat-multi' data-chat='"+celeb.mem_idx+"' title='크리에이터대화'><span class='icon-common'></span></button>"+
							"			<button class='subscribe-btn "+subscriberClass+"' data-celeb-idx='"+celeb.mem_idx+"'>"+subscriberText+"</button>"+	btnMembership+						
							"		</div>";
				if(!window.review){
					//inStr+=	"		<button class='global__donate-btn global__donate-btn--modify "+supportClass+" btn_give_dia' data-celeb-idx='"+celeb.mem_idx+"' data-type='일반'><span class='icon-common'></span></button>";
				}
					inStr+=	"	</div>"+
							"</div>";
			if(celeb.membership_list){
				inStr+="<div class='mbs-ready mbs-type"+celeb.membership_list.length +"'>";
				if(celeb.membership_title){
					inStr+="<h2>"+celeb.membership_title+"</h2>";
				}
				inStr+="<ul class='mbs-wrap'>";
							    	
	    	
				for(var k=0;k<celeb.membership_list.length;k++){
					var membership=celeb.membership_list[k];
					inStr+="<li class='mbs-item'><div class='mbs-box'><h2>"+membership.name+"</h2>";
					inStr+="<div class='mbs-price'><p class='period'>매월</p><p class='price'><span>₩</span><em>"+membership.price.format()+"</em></p></div>";
					if(membership.info && membership.info.tips){
						inStr+="<ul class='tips'>";
						for(var j=0;j<membership.info.tips.length;j++){
							inStr+="<li>"+membership.info.tips[j]+"</li>";
						}
						inStr+="</ul>";
					}
					
					inStr+="<button class='btn-account btn_membership' data-goods-idx='"+membership.idx+"' data-price='"+membership.price+"'  data-celeb-idx='"+celeb.mem_idx+"'>멤버십 가입</button>";
					
					inStr+="</li>";
					
				}
				inStr+="</ul></div>"
			}
			if(!mypage){
				inStr+=		"<div class='celeb__contents'>"+
							"	<ul class='celeb__item-wrap'>";
						if(celeb.documents.length){
							for(var j=0;j<celeb.documents.length;j++){
								
								if(celeb.documents[j].price>0){
									inStr+="<li class='celeb__item'>"+
									"			<a href='javascript:;' class='celeb__item-box my-creator-preview' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'>" +
									"<img src='/assets/img/jelly"+celeb.documents[j].jelly+".png' style='max-width:95%'><br>유료 컨텐츠 입니다.</a>"+
									"		</li>";
								}else{
									if(celeb.documents[j].file_type=="image"){
										inStr+="<li class='celeb__item'>"+
										"			<a href='javascript:;' class='celeb__item-box' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'><img src='"+celeb.documents[j].file_url+"' alt='' class='orientation hide'>"+loadingStr+"</a>"+
										"		</li>";
									}else if(celeb.documents[j].file_type=="mov"){
										if(celeb.documents[j].file_url=="encode"){
											inStr+="<li class='celeb__item'>"+
											"			<a href='javascript:;' class='celeb__item-box' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'><img src='/assets/img/enc_start.png' alt='' class='orientation hide'></a>"+
											"		</li>";
										}else{
											
											inStr+="<li class='celeb__item'>"+
											"			<a href='javascript:;' class='celeb__item-box' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'><img src='"+celeb.documents[j].file_url+"' alt='' class='orientation hide'></a>"+
											"		</li>";
										}
									}else if(celeb.documents[j].file_type=="audio"){
										inStr+="<li class='celeb__item'>"+
										"			<a href='javascript:;' class='celeb__item-box' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'><img src='/assets/img/ico_music.png' alt='' class='orientation hide'></a>"+
										"		</li>";
									}else{
										inStr+="<li class='celeb__item'>"+
										"			<a href='javascript:;' class='celeb__item-box my-creator-preview' style='line-height:178px;' data-mem-idx='"+celeb.documents[j].mem_idx+"' data-index='"+j+"'>텍스트 컨텐츠 입니다.</a>"+
										"		</li>";
										
									}
								}
								
							}
						}
						inStr+="	</ul>"+
							"	<div class='default-more'>"+
							"		<a href='/home/"+celeb.nick_name+"' class='link-silence'>전체보기</a>"+
							"	</div>"+
							"</div>";
			}
			inStr+=		"</article>";
			str+=inStr;
		}
		
		$(wrap).append(str);
		showImage(".orientation.hide",true);
		showVideo(".video-orientation.hide",true);
		
	}
}




$(document).on("click",".timeline__message__more",function(){
	
	var $obj=$(this).prev();
	
	$obj.toggleClass("active");
	if($obj.hasClass("active")){
		$(this).html("닫기");
	}else{
		$(this).html("더보기");
	}
	
});







function dispSearchFavorCelebs(data,wrap){
	if(data.list.length){
		var str="";
		for(var i=0;i<data.list.length;i++){
			var celeb=data.list[i];
			var cssActive=celeb.is_my_celeb=="Y" ? "active":"";
			var strSubscribe=celeb.is_my_celeb=="Y" ? "구독중":"구독";
			
			var inStr="<article class='celeb-inner-wrap celeb-inner-wrap--int'>"+
							"<div class='celeb-inner'>"+
							"	<div class='celeb-inner__pic-box'>"+
							"		<a href='/home/"+celeb.nick_name+"' class='link-silence'><img src='"+celeb.profile_image+"' alt='"+celeb.nick_name+"' class='orientation hide'></a>"+
							(celeb.is_my_celeb=="Y" ?"		<div class='celeb-inner__pic-box--bg'></div>" : "")+
							"	</div>"+
							"	<div class='celeb-inner__infobox'>"+
							"		<a href='/search/query?tag="+celeb.tag+"' class='celeb-inner__nick link-silence' style='display:inline'><em>"+celeb.tag+"</em></a><a href='/home/"+celeb.nick_name+"' style='display:inline' class='celeb-inner__nick link-silence'>"+celeb.nick_name+"</a>"+
							"		<p class='celeb-inner__intro'>"+celeb.comment+"</p>"+
							"		<div class='celeb-inner__stats'>"+
							"			<span>구독자<em>"+celeb.subscriber.format()+"</em></span>"+
							"			<span>받은하트<em>"+celeb.heart.format()+"</em></span>"+
							"			<span>게시물<em>"+celeb.document.format()+"</em></span>"+
							"		</div>"+
							"		<div class='celeb-inner__utility celeb-inner__utility--modify'>"+
							"			<span class='celeb-inner__btn "+cssActive+"' data-celeb-idx='"+celeb.mem_idx+"'>"+		
							"				<button class='dm-btn go-chat-single' data-chat='"+celeb.mem_idx+"'><span class='icon-common'></span></button>"+
							"				<button class='chat-btn go-chat-multi' data-chat='"+celeb.mem_idx+"'><span class='icon-common'></span></button>"+
							"			</span>"+
							"			<button class='subscribe-btn "+cssActive+"' data-celeb-idx='"+celeb.mem_idx+"'>"+strSubscribe+"</button>"+
							"		</div>"+
							"	</div>"+
							"</div>"+							
						"</article>";
			str+=inStr;
		}
		if(data.more_data=="Y"){
			str+="<div class='default-more default-more--celeb'><a href='/search/favor' class='link-silence'>최다하트 크리에이터 순위 더보기</a></div>";
		}
		
		$(wrap).append(str);
		showImage(".orientation.hide",true);		
		
	}
}






function dispMyFans(data,wrap){
	if(data.list.length){
		var str="";
		for(var i=0;i<data.list.length;i++){
			var celeb=data.list[i];
			if($("#celeb_"+celeb.mem_idx).length)continue;
			
			var strSupport="";
			if(celeb.is_membership =="Y"){
				strSupport="<span class='donation__box pdl'> <a>멤버십</a></span>";
			}
			
			
			
			
			var taglist=celeb.tag ? celeb.tag.split(" "):[];
			
			
			var tagInfo="";
			if(taglist.length){
				tagInfo="<div class='celeb-category'>";
				for(var k=0;k<taglist.length;k++){					
					tagInfo+="<a href='/search/memtag?q="+taglist[k]+"' >#"+taglist[k]+"</a> ";
					
				}
				tagInfo+="</div>";
			}
			
			var inStr=
				"<li class='celeb-list__item celeb-list__item--fan' id='celeb_"+celeb.mem_idx+"'>"+
				
				"	<div class='celeb-list__pic-box celeb-list__pic-box--fan'>"+
				"		<a href='/home/"+celeb.nick_name+"'><img src='"+celeb.profile_image+"' alt='"+celeb.nick_name+"' class='orientation hide'></a>"+
				"	</div>"+
				"	<div class='celeb-list__nick-box celeb-list__nick-box--fan'>"+
				"		<button class='util-btn chat__multi' data-owner='"+celeb.mem_idx+"' data-status='Y' data-my-room='N'><span class='icon-common' ></span></button>"+	
				"		<a href='/home/"+celeb.nick_name+"' class='celeb-list__nick'>"+celeb.nick_name+"</a>"+tagInfo+				
				
				"	</div>"+
				
				"</li>";			
			
			str+=inStr;
		}		
		$(wrap).append(str);
		showImage(".orientation.hide",true);		
		
	}
}


function dispMyModifyForm(data,wrap){
	var tagOption="";
	var memberInfo=data.member_info;
	
	var profileImage="";
	for(var i=0;i<memberInfo.image_list.length;i++){
		var representCss="";
		if(memberInfo.image_list[i].represent=="Y"){
			representCss="active";
		}
		profileImage+="<li class='img-profile__normal profile "+representCss+"' id='img_wrap_"+memberInfo.image_list[i].file_idx+"' data-file-idx='"+memberInfo.image_list[i].file_idx+"'>"+
			"<img src='"+memberInfo.image_list[i].url+"' alt='' class='orientation hide'><button type='button' class='img-delete profile' data-id='img_wrap_"+memberInfo.image_list[i].file_idx+"' data-file-idx='"+memberInfo.image_list[i].file_idx+"'><span class='icon-common'></span></button></li>";
	}
	var strTag="";
	if(memberInfo.tag){
		var tagTemp=memberInfo.tag.split(" ");
		for(var i=0;i<tagTemp.length;i++){
			tagTemp[i]="#"+tagTemp[i];		
		}
		strTag=tagTemp.join(" ");
	}
	if(!memberInfo.thanks_msg)memberInfo.thanks_msg="";
	var str=
		"<ul>"+
		"	<li><h3>닉네임</h3><div class='input-active input-check'><input type='text' value='"+memberInfo.nick_name+"' name='nick_name'><span class='icon-common right'></span></div><p class='insert-tip'></p></li>"+
		"	<li><h3>#태그</h3><div class='input-active input-check'><input type='text' value='"+strTag+"' name='tag' placeholder='#태그. 띄어쓰기 구분 5개이하. 태그는  20자이하 작성.'><span class='icon-common right'></span></div><p class='insert-tip'></p></li>"+
		
		"	<li><h3>한줄소개</h3><div class='input-active input-check' ><textarea name='comment' style='height:40px;'>"+memberInfo.comment+"</textarea><span class='icon-common confirm'></span></div><p class='insert-tip'></p></li>"+
		"	<li><h3>구독 감사 메세지</h3><div class='input-active input-check' ><textarea name='thanks_msg' maxlength='255' style='height:40px;' placeholder='구독자에게 감사 알림을 보냅니다'>"+(memberInfo.thanks_msg ? memberInfo.thanks_msg:"")+"</textarea><span class='icon-common confirm'></span></div><p class='insert-tip'></p></li>"+
		//"	<li><h3>휴대폰번호</h3><div class='input-active input-check'><input type='number' name='phone' value='"+memberInfo.phone+"'><span class='icon-common confirm'></span></div><p class='insert-tip'></p></li>"+
		"	<li><h3>프로필사진</h3><p class='profile-img-info'>프로필 이미지로 설정 하려면 이미지를 클릭하세요.</p><ol class='img-profile'>"+profileImage+"<li class='btn-img-upload'><label for='img-profile-upload' id='btn_upload'><span class='icon-img-upload'></span></label><label for='img-profile-upload1' id='upload_progress' class='progress hide'><span>0%</span></label><input type='file' id='file' name='img-profile-upload' accept='image/jpeg,image/png,image/gif'></li></ol></li>"+
		"</ul>";
	$(wrap).append(str);
	showImage(".orientation.hide",true);
	
}


function dispNotiList(page){
	if(!page)page=1;
	var wrap=".global-pop__contents-wrap";
	//var log_popup=window.log_popup;
	$.get("/account/notilist?page="+page, function(res){
		var temp=JSON.parse(res);
		var data=JSON.parse(temp.msg);
		if(page==1 && !data.list.length){			
			if(window.log_popup){
				window.log_popup.close();
				
			}
			
			return;
		}
		
		
		if(!window.log_popup || !window.log_popup.isOpen()){
			window.log_popup=Popup.show("나의알림","<div class='noti-pop sort-wrap mb17' ><input type='checkbox' id='chk-item-all' class='chk-item-all' data-name='chk-noti'><label for='chk-item-all' class='ml14'><span class='icon-common'></span></label></div>" +
					"<button class='btn-inner-confirm btn-alim active' onclick='removeChecked(\"chk-noti\",\"/account/removenoti\",\"선택된 알림을 삭제 하겠습니까?\",dispNotiList)'>알림 비우기</button>"+
					"<div class='global-pop__contents-wrap' style='width:600px;height:500px''></div>");
		}
		
		
		var str="<div class='noti-pop sort-wrap'><input type='checkbox' id='chk-item-all' class='chk-item-all' data-name='chk-noti'><label for='chk-item-all'><span class='icon-common'></span></label><a href='javascript:;' class='active' onclick='removeChecked(\"chk-noti\",\"/account/removenoti\",\"선택된 알림을 삭제 하겠습니까?\",dispNotiList)'>알림 비우기</a></div>";
		str="";
		str+="<div class='myitem'><table class='data-table'><caption></caption><colgroup><col width='60px'><col width='620px'><col width='120px'></colgroup><tbody>";
		
		for(var i=0;i<data.list.length;i++){
			var noti=data.list[i];
			var link=noti.link ? "<a href='"+noti.link+"'>"+noti.summary+"</a>" : "<a>"+noti.summary+"</a>";
			str+="<tr><td><input type='checkbox' id='chk-item-"+noti.idx+"' name='chk-noti' value='"+noti.idx+"'><label for='chk-item-"+noti.idx+"'><span class='icon-common'></span></label></td>";
			str+="<td class='my-alim'>"+link+"</td>";
			str+="<td>"+noti.date+"</td></tr>"
				
		}
		str+="</tbody></table>";
		
		str+="<div class='paging'>"+data.pagination+"</div></div>";
		
		$(wrap).html(str);
		window.log_popup.relocate();
		if(data.count>0){
			if(data.count>9){
				$(".my-info .util-btn em").html("9+");
			}else{
				$(".my-info .util-btn em").html(data.count);
			}
			if(data.count>99){
				$(".btn-mysignal").html("알림 99+");
			}else{
				$(".btn-mysignal").html("알림 "+data.count);
			}
		}else{
			$(".my-info .util-btn em").remove();
			$(".btn-mysignal").html("알림 0").removeClass("active");
		}
		
	});
}



function dispDiaList(page){
	if(!page)page=1;
	var wrap=".global-pop__contents-wrap";
	var log_popup=window.log_popup;
	$.get("/account/dialist?page="+page, function(res){
		var temp=JSON.parse(res);
		var data=JSON.parse(temp.msg);
		var str="<ul class='tabs'><li class='tab-link current'><a href='javascript:;' onclick='dispDiaList(1)'>젤리 내역</a></li><li class='tab-link'><a href='javascript:;' onclick='dispExchangeList(1)'>환전 내역</a></li></ul>";
		str+="<div class='tab-content current'><table class='data-table'><caption></caption><colgroup><col width='15%'><col width='15%'><col width='50%'><col width='20%'></colgroup><tbody>";
		for(var i=0;i<data.list.length;i++){
			var dia=data.list[i];
			str+="<tr><td class='strong'>"+dia.type+"</td>";
			str+="<td>"+dia.date+"</td>";
			str+="<td>"+dia.type_name+"</td>";
			str+="<td>"+dia.dia.format()+"개</td></tr>";
				
		}
		str+="</tbody></table>";
		
		str+="<div class='paging'>"+data.pagination+"</div></div>";
		
		$(wrap).html(str);
		log_popup.relocate();
	});
}

function dispExchangeList(page){
	if(!page)page=1;
	var wrap=".global-pop__contents-wrap";
	var log_popup=window.log_popup;
	$.get("/account/exchangelist?page="+page, function(res){
		var temp=JSON.parse(res);
		var data=JSON.parse(temp.msg);
		var str="<ul class='tabs'><li class='tab-link '><a href='javascript:;' onclick='dispDiaList(1)'>젤리 내역</a></li><li class='tab-link current'><a href='javascript:;' onclick='dispExchangeList(1)'>환전 내역</a></li></ul>";
		str+="<div class='tab-content current'><table class='data-table'><caption></caption><colgroup><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'></colgroup><tbody>";
		for(var i=0;i<data.list.length;i++){
			var log=data.list[i];
			str+="<tr><td class='strong'>"+log.status+"</td>";
			str+="<td>"+log.date+"</td>";
			str+="<td>"+log.dia_count.format()+"개</td>";
			str+="<td>"+log.ratio+"%</td>";
			str+="<td class='strong'>"+log.amount.format()+"원</td>";
			str+="<td>"+log.bank+"</td>";
			str+="<td>"+log.account+"</td></tr>";
				
		}
		str+="</tbody></table>";
		
		str+="<div class='paging'>"+data.pagination+"</div></div>";
		
		$(wrap).html(str);
		log_popup.relocate();
	});
}




//채딩 목록 출력
function dispRoomList(data,wrap){
	//if(data.list.length){
		var str="";
		//var type=data.type=="m" ? "chat__multi" : "chat__single"
		for(var i=0;i<data.list.length;i++){
			var room=data.list[i];
			var type= room.type=="m" ? "chat__multi" : "chat__single";
			var singleClass= room.type=="s" ? "direct-chat":""
				
			var time=(room.updatedate && room.updatedate!="0") ? getDateStr(room.updatedate):"";
			var strStatus=room.status=="N" ? " - 중지 됨" : "";
			
			var userCount="";
			if(room.type=="m"){
				if(time){
					userCount=" / "+(parseInt(room.subscriber)+1).format()+"명";
				}else{
					userCount=(parseInt(room.subscriber)+1).format()+"명";
				}
			}
			
			str+=
				"<li class='chat__item "+type+" "+singleClass+"' id='chat_item_"+room.type+"_"+room.owner+"' data-owner='"+room.owner+"' data-nick='"+room.nick_name+"'>"+
        		"	<div class='chat__item-box'>"+
        		"		<div class='chat__pic'><a href='/home/"+room.nick_name+"'><img src='"+room.profile_image+"' class='orientation hide'></a></div>"+
        		"		<div class='chat__pic-box--bg'></div>"+
        		"		<div class='chat__info'><span class='chat__nick'>"+room.nick_name+strStatus+"</span><span class='chat__time'>"+time+userCount+"</span><span class='chat__content'>"+room.last_message+"</span></div>";
			if(room.type=="m"){
				str+="		<div class='chat__btn-wrap'><button class='util-btn "+type+"' data-owner='"+room.owner+"' data-status='"+room.status+"' data-my-room='"+room.my_room+"'><span class='icon-common'></span></button></div>";
			}else{
				str+="		<div class='chat__btn-wrap'><button class='util-btn "+type+"' data-room-idx='"+room.room_idx+"' data-mem-idx='"+room.owner+"' data-nick-name='"+room.nick_name+"'><span class='icon-common'></span></button></div>";
				if(room.unconfirmed>0){
					if(room.unconfirmed>99){
						str+="<div class='unread-count tx-center' id='uc_"+room.owner+"'>99+</div>";
					}else{
						str+="<div class='unread-count tx-center' id='uc_"+room.owner+"'>"+room.unconfirmed+"</div>";
					}
					
				}
			}
        		        		
        	str+="	</div>"+
        		"</li>";
        		
		}
		
		$(wrap).html(str);		
		
	//}
	showImage(".orientation.hide",true);
}



function dispShopDia(data,wrap){
	if(data.list.length){
		var str="";
		
		for(var i=0;i<data.list.length;i++){
			var goods=data.list[i];
			var count=goods.bonus_amount > 0 ? goods.amount.format() + "+"+goods.bonus_amount.format() : goods.amount.format();
			var price=goods.sale_price.format();
			str+=
				"<li class='product__item product__item--coin'><a href='javascript:;' class='btn_charge_dia' data-idx='"+goods.idx+"'><span class='product__pic'><img src='/assets/img/jelly_b"+goods.jelly+".png' ></span>" +
				"<span class='product__num'>"+count+"</span><span class='product__price'>"+price+"원</span></a></li>";
		}
		
		$(wrap).html(str);
	}
}




//후원 / 받은 내역
/*
function dispRecordSupport(data, form_wrap, data_wrap,what){
	var who="받은이";
	var titleType="금액";
	
	if(what=="rev"){
		who="보낸이";
		var titleType="젤리 수";
	
	}
	var record_form="<form id='record_form' action='/record/supportlist/"+what+"'><input type='hidden' name='page' value='"+data.page+"'>"+		
		"<div class='search-box'><input type='text' name='search' placeholder='닉네임' value='"+data.search+"'></div><button type='button' class='btn-chart-search' data-type='record_support_"+what+"_page' data-page='1'>검색</button></form>";
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='10%'><col width='20%'><col width='15%'><col width='20%'><col width='15%'><col width='20%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>"+who+"</td><td>유형</td><td>회차</td><td>"+titleType+"</td><td>날짜</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		var amount=log.amount.format()+"원";
		if(what=="rev"){
			who="보낸이";			
			amount=log.dia.format()+"개";
		}
		record_data+="<tr><td>"+log.list_num+"</td><td>"+log.nick_name+"</td><td>"+log.type+"</td><td>"+log.sub_type+"</td><td class='strong'>"+amount+"</td><td>"+log.date+"</td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
}
*/


//후원 / 받은 내역
function dispRecordSupportUsers(data, form_wrap, data_wrap){
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='10%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>후원대상</td><td>후원금액</td><td>총 후원금액</td><td>후원시작일</td><td>중지</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		
		record_data+="<tr><td>"+log.list_num+"</td><td>"+log.nick_name+"</td><td>"+log.amount.format()+"원</td><td>"+log.total_amount.format()+"원</td><td class='strong'>"+log.date+"</td>" ;
		if(log.status=="Y"){
			record_data+="<td id='stop_support_"+log.idx+"'><button type='button' onclick='stopSupport(\""+log.idx+"\")'>후원중단</button></td></tr>";
		}else{
			record_data+="<td class='strong'>중단 됨</td></tr>";
		}
				
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	
	$(data_wrap).html(record_data);
}



$(document).on("click",".active-anim",function(){
	if(!$(this).hasClass('animate')){
		$(this).addClass('animate');
	}else{
		$(this).removeClass('animate');
	}
});


$(document).on("mouseenter",".my-swiper-arrow-bg",function(){	
	$(this).addClass("enter");
});
$(document).on("mouseleave",".my-swiper-arrow-bg",function(){	
	$(this).removeClass("enter");
});
$(document).on("mouseenter",".swiper-button-next",function(){
	var id=$(this).data("id");
	$("#sw_btn_next_bg_"+id).addClass("enter");
});
$(document).on("mouseenter",".swiper-button-prev",function(){
	var id=$(this).data("id");
	$("#sw_btn_prev_bg_"+id).addClass("enter");
});

$(document).on("click",".my-swiper-arrow-bg.right",function(){	
	var id=$(this).data("id");
	$("#sw_btn_next_"+id).click();	
});

$(document).on("click",".my-swiper-arrow-bg.left",function(){	
	var id=$(this).data("id");
	$("#sw_btn_prev_"+id).click();	
});


