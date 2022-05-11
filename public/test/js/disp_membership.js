window.addEventListener("popstate",function(){
	var data = history.state;  
	if(data){
		if(data.tag){
			loadPage(data.tag,data.param,true);
		
		}else{
			
			history.back();
		}
	}
});


$(function(){
	/*$(window).on('popstate', function(event) {
		  var data = event.originalEvent.state;
		  if(data && data.tag){
			  loadPage(data.tag);
		  }else{
			  location.reload();
		  }
	});*/
	
	$(document).on("click",".util-btn.membership",function(){
		var $obj=$(this);
		var status=$obj.attr("data-status");
		var idx=$obj.attr("data-idx");
		
		var pop="";
		if(status=="N"){//판매중지
			pop=$("<div class='util-popup-wrap active' ><ul class='util-popup'>" +
					"<li class='util-popup__item'><a href='javascript:;' class='btn-status' data-idx='"+idx+"' data-status='Y'>판매시작</a></li>" +
					"<li class='util-popup__item'><a href='javascript:;' onclick='loadPage(\"#writeform/"+idx+"\")'>수정</a></li>" +					
					"<li class='util-popup__item'><a href='javascript:;' class='bnt-remove ok' data-idx='"+idx+"'>삭제</a></li></ul></div>");
		}else if(status=="Y"){
			pop=$("<div class='util-popup-wrap active' ><ul class='util-popup'>" +
					"<li class='util-popup__item'><a href='javascript:;' class='btn-status' data-idx='"+idx+"' data-status='N'>판매중지</a></li>" +
					"<li class='util-popup__item'><a href='javascript:Toast.show(\"판매중지 후 수정이 가능 합니다.\",{modal:true})'>수정</a></li>" +			
					"<li class='util-popup__item'><a href='javascript:Toast.show(\"판매중지 후 삭제가 가능 합니다.\",{modal:true})' class='bnt-remove' data-idx='"+idx+"'>삭제</a></li></ul></div>");
		}
		
		
		//$("body").append(pop);
		$obj.parent().append(pop);
		
		setFixedPopLocation($obj,pop);
		
		return false;
	});
	
	//삭제
	$(document).on("click",".bnt-remove.ok",function(){
		var p={};
		p[window.csrf_name]=window.csrf_val;
		var idx=$(this).attr("data-idx");
		Toast.show("삭제 하겠습니까?",{
			ok:function(){
				
				
				$.post("/membership/remove/"+idx,p,function(res){
					var data=JSON.parse(res);
					if(data.result=="Y"){
						Toast.show(data.msg,{modal:true,callback:function(){location.reload()}});
					}else{
						Toast.show(data.msg,{modal:true});
					}
				});
			}
		});
	});
	
	
	//멤버십 메세지 폼
	$(document).on("click",".btn-mbs-modify",function(){
		
		var str=
			"<div class='inputbox-modify' style='width:450px'><ul>"+	
			"<li><h3>공지제목</h3><div class='input-active'><input type='text' id='message_title' value='' placeholder='80자 이내로 작성' maxlength='80'></div></li>"+
			"<li><h3>공지 내용</h3><div class='input-active input-active--textarea'><textarea id='message_text' placeholder='1000자 이내로 내용을 작성해주세요.' maxlength=1000></textarea></div></li></ul></div>";
		var idx=$(this).attr("data-idx");
		
		window.report_popup=Popup.show("멤버십 공지 등록",str,function(){
			var p={};
			p[window.csrf_name]=window.csrf_val;
			p.idx=idx;
			p.notice=$.trim($("#message_text").val());
			p.title=$.trim($("#message_title").val());
			if(!p.title){
				Toast.show("공지 제목을 입력 하세요.",{modal:true});
				return;
			}
			if(!p.notice){
				Toast.show("공지 내용을 입력 하세요.",{modal:true});
				return;
			}
			
			$.post("/membernotice/write",p, function(res){
				var data=JSON.parse(res);
				if(data.result=="Y"){
					
					Toast.show(data.msg,{callback:function(){window.report_popup.close();}});				
				}else{
					Toast.show(data.msg,{modal:true});
				}
			});
			
		});
		window.report_popup.setOkButtonText("등록하기");
	});
	
	$(document).on("click",".btn-write-title",function(){
		var membership_title=$.trim($("#membership_title").val());
		
		
		var p={};
		p[window.csrf_name]=window.csrf_val;		
		p.membership_title=membership_title;
		
		$.post("/membership/writetitle",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				Toast.show("수정 되었습니다.",{callback:function(){
					$("#membership_title").val(data.msg);
				}});
				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	});
	
	
	$(document).on("click",".btn-status",function(){
	
		var idx=$(this).attr("data-idx");
		var status=$(this).attr("data-status");
		var p={};
		p[window.csrf_name]=window.csrf_val;		
		p.idx=idx;
		p.status=status;
		
		$.post("/membership/status",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				Toast.show(data.msg,{callback:function(){loadPage("#list")}});				
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	});
	
	//페이징
	$(document).on("click","a[data-type=membership_notice_page],button[data-type=membership_notice_page]",function(){
		var page=$(this).attr("data-page");		
		$("#notice_form input[name=page]").val(page);		
		var membership_idx=$("#notice_form select[name=type]").val();
		var page=$(this).attr("data-page");		
		if(membership_idx)membership_idx="/"+membership_idx;		
		var param= page ? "?page="+page : "?page=1";		
		loadPage("#notice"+membership_idx,param);
	});
	
	$(document).on("click","a[data-type=member_list_page],button[data-type=member_list_page]",function(){
		var page=$(this).attr("data-page");		
		$("#members_form input[name=page]").val(page);		
		var membership_idx=$("#members_form select[name=type]").val();
		var page=$(this).attr("data-page");		
		if(membership_idx)membership_idx="/"+membership_idx;		
		var param= page ? "?page="+page : "?page=1";		
		loadPage("#members"+membership_idx,param);
	});
	
	
	$(document).on("click",".notice-view",function(){		
		
		var idx=$(this).attr("data-idx");
		var hasClass=$("#notice_"+idx).hasClass("hide");
		$('.notice-tr').addClass("hide");
		if(hasClass){
			$("#notice_"+idx).removeClass("hide");
		}else{
			$("#notice_"+idx).addClass("hide");
		}
		
	});
	
	
});

function dispMembershipList(data,wrap){
	
	var str="";
	if(!data.has_membership){
		str+="<div class='none-mbs'><div class='none-mbs__title'>멤버십으로 매월 안정적인 수입을 !</div>";
		str+="<div class='none-mbs__text'>멤버십은 크리에이터가 매월 수익을&nbsp;창출할 수 있는<br>좋은 방법입니다. 또한 당신과 팬을 이어주고 더욱 깊은 소통을 가능하게&nbsp;하죠!<br>안정적인 수입을 얻고 팬과의 연결고리를 만드는 가장 쉬운 방법.<br>지금 시작해&nbsp;보세요!</div>";
		str+="<div class='mbs-add-box'><button class='btn-mbs-add' onclick='loadPage(\"#writeform\")'>첫 멤버십 추가하기+</button></div><div class='none-mbs__img'><img src='/assets/img/img_mbs.png' alt=''></div></div>";
		$(wrap).html(str);	
		return;
	}
	str+="<div class='writepost'>";
	
	str+="<div class='writepost__write-btn'><button class='btn-write-title btn-inner-confirm active'>멤버십 소개 등록하기</button></div>" +
		"<div class='writepost__title'><textarea name='membership_title' id='membership_title' placeholder='나의 멤버십을 소개하는 간단한 내용 입력 ' class='writepost__title-box' style='width:80%'>"+data.membership_title+"</textarea></div>";
	
	
	str+="<ul class='mbs-wrap'>";
	for(var i=0;i<data.list.length;i++){
		var membership=data.list[i];
		str+="<li class='mbs-item'><div class='mbs-box'>";
		if(membership.info.thumb){
			str+="<div class='mbx-box__thumb'><img src='"+membership.info.thumb+"' class='orientation hide'></div>";
		}else{
			str+="<div class='mbx-box__thumb'><img src='/assets/img/barcode.png' class='portrait'></div>";
		}
		if(membership.status=="Y"){
			str+="<h2>"+membership.name+"</h2><span class='mbs-state'>판매중</span>";
		}else if(membership.status=="N"){
			str+="<h2>"+membership.name+"</h2><span class='mbs-state stop'>판매중지</span>";
		}
			
		str+="<button class='util-btn membership' data-idx='"+membership.idx+"' data-status='"+membership.status+"'><span class='icon-common'></span></button>";
		str+="<div class='mbs-price'><p class='period'>매월</p><p class='price'><span>&#8361</span><em>"+membership.price.format()+"</em></p></div>";
		str+="<ul class='tips'>";		
		for(var k=0;k<membership.info.tips.length;k++){
			var tip=membership.info.tips[k];
			str+="<li>"+tip+"</li>";
			
		}
		str+="</ul>";
		str+="<button class='btn-mbs-modify' data-idx='"+membership.idx+"'>멤버십 공지 등록</button>";
		str+="</div></li>";
	}
	str+="</ul>";
	if(data.has_membership){
		str+="<div class='mbs-add-box'><button class='btn-mbs-add' onclick='loadPage(\"#writeform\")'>새 멤버십 추가 +</button></div>";
	}
	str+="</div>";	
	$(form_id).html("");
	$(wrap).html(str);	
	showImage(".orientation.hide",true);
}


function dispWriteForm(data, wrap){
	var btnText=data.idx ? "수정하기" : "등록하기";
	var price=data.price > 0 ? data.price.format() : "";alert("ddddddddddd");
	var str="";
	str+="<div class='writepost'>";
	//str+="<div class='writepost__write-btn'><button class='btn-inner-confirm active' onclick='addMembership()'>등록하기</button></div>";
	str+="<div class='write-mbs'>";
	
	str+="<div class='write-mbs__left'>";	
	str+="<dl class='write-mbs__form'><dt>멤버십 이름</dt><dd><input type='text' placeholder='멤버십 이름을 입력해주세요.(30자이하)' maxlength='30' value='"+data.name+"' onkeyup='showMembershipName(this)'></dd></dl>";
	str+="<dl class='write-mbs__form'><dt>멤버십 가격</dt><dd><span class='symbol-won'>&#8361</span><input type='text' placeholder='1,000원 이상으로 입력 하세요.' value='"+price+"' inputmode='numeric' class='mbs-cost' onkeyup='numberWithCommas(this)'></dd></dl>";
	str+="<dl class='write-mbs__form write-mbs__form--add' ><dt>멤버십 내용<button class='btn-benefit-add' onclick='addTip()'>내용 추가 +</button></dt></dl>" ;
	str+="<dl class='write-mbs__form write-mbs__form--add' id='tips_wrap'>" ;
	for(var i=0;i<data.info.tips.length;i++){
		str+="<dd><input "+(i==data.info.tips.length-1 ? "readonly":"")+" type='text' placeholder='멤버십 혜택을 입력해주세요.(100자이하)' value='"+data.info.tips[i]+"' class='tip-ele' data-sort='"+(i+1)+"' onkeyup='showTip(this,"+(i+1)+")' maxlength='100'>"+(i<data.info.tips.length-1 ? "<button class='btn-remove' onclick='removeTip("+(i+1)+")'></button>":"")+"</dd>";
	}
	str+="</dl>";
	str+="<dl class='write-mbs__form'><dt>멤버십 혜택 알림</dt><dd class='mbs-notice'><textarea type='text' placeholder='멤버십 회원만 공유할수 있는 알림 메시지를 작성해주세요\n( 1:1 대화를 통해 알림 메시지가 전달 됩니다. 500자이하)' id='noti' maxlength='500'>"+data.info.noti+"</textarea></dd></dl>";
		
	str+="</div>";
	
	str+="<div class='write-mbs__preview'>";
	str+="<h2>멤버십 미리보기</h2>";
	str+="<div class='mbs-box'>";
	str+="<h2 id='p_membership_name'>"+data.name+"</h2>";
	str+="<div class='mbs-price'><p class='period'>매월</p><p class='price'><span>&#8361</span><em id='p_membership_price'>"+data.price.format()+"</em></p></div>";
	str+="<ul class='tips' id='p_membership_tip'>";
	for(var i=0;i<data.info.tips.length;i++){
		if(data.info.tips[i]){
			str+="<li class='tip-item' id='tip_"+(i+1)+"'>"+data.info.tips[i]+"</li>";
		}
	}
	str+="</ul><button class='btn-account' type='button' >멤버십 가입</button>";
	str+="</div>";
	str+="<ul class='tips'><li>멤버십 내용이 맞는지 다시한번 확인해주세요.</li><li>해당 멤버십에 가입자가 있을경우 수정이 불가능합니다.</li></ul>";
	str+="<div class='checkbox'><input type='checkbox' id='agree-mbs' name='agree-mbs'><label for='agree-mbs' class='checkbox__item'><span class='icon-common'></span><em>멤버십 내용이 맞는지 확인 하였습니다.</em></label></div>";
	str+="</div>";
	str+="<div class='write-mbs__btn-set'><button class='btn-default' onclick='loadPage(\"#list\")'>목록</button><button class='btn-default' onclick='addMembership(\""+data.idx+"\")'>"+btnText+"</button></div>";
	str+="</div>";
		
	str+="</div>"
	str+="</div>";
	$(form_id).html("");
	$(wrap).html(str);
}

function dispNoticeList(data, form_wrap, data_wrap){
	var options="<option value=''>전체멤버십</option>";
	for(var i=0;i<data.select_list.length;i++){
		var selected="";
		if(data.select_list[i].idx==data.membership_idx){
			selected="selected";
		}
		options+="<option value='"+data.select_list[i].idx+"' "+selected+">"+data.select_list[i].name+"</option>";
	}
	var record_form="<form id='notice_form' action=''><input type='hidden' name='page' value='"+data.page+"'>"+
		"<div class='selector' style='width:200px'><select name='type'>"+options+"</select></div>"+
		"<button type='button' class='btn-chart-search' data-type='membership_notice_page' data-page='1'>검색</button></form>";
	
	var record_data="<table class='data-table membership-table'>";
	
	
		record_data+="<colgroup><col width='5%'><col ><col width='15%'><col width='15%'><col width='15%'><col width='5%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>제목</td><td>작성자</td><td>멤버십</td><td>등록일</td></thead>"
		"<tbody>";
	
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];		
		record_data+="<tr id='notice_data_"+log.idx+"'><td>"+log.list_num+"</td><td><a href='javascript:;' class='notice-view notice-title'  data-idx='"+log.idx+"'>"+
		log.title+"</a></td><td>"+log.nick_name+"</td><td>"+log.membership_name+"</td><td>"+log.str_regdate+"</td></tr>";
		record_data+="<tr id='notice_"+log.idx+"' class='hide notice-tr'><td colspan='5' class='align-left lh20 notice-content'>"+log.notice+"</td></tr>";
	}
	record_data+="</table>";
	
	record_data+="<div class='paging'>"+data.pagination+"</div>";
	
	
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
}



function dispMemberList(data, form_wrap, data_wrap){
	var options="<option value=''>전체멤버십</option>";
	for(var i=0;i<data.select_list.length;i++){
		var selected="";
		if(data.select_list[i].idx==data.membership_idx){
			selected="selected";
		}
		options+="<option value='"+data.select_list[i].idx+"' "+selected+">"+data.select_list[i].name+"</option>";
	}
	var record_form="<form id='members_form' action=''><input type='hidden' name='page' value='"+data.page+"'>"+
		"<div class='selector' style='width:200px'><select name='type'>"+options+"</select></div>"+
		"<button type='button' class='btn-chart-search' data-type='member_list_page' data-page='1'>검색</button></form>";
	
	var record_data="<ul class='mbs-list'>";
	for(var i=0;i<data.list.length;i++){
		var record=data.list[i];
		var tips="";
		
		if(record.info.tips && record.info.tips.length){
			tips="<ul class='tips mbs-benefit'>";
			for(var a=0;a<record.info.tips.length;a++){
				tips+="<li>"+record.info.tips[a]+"</li>";
			}
			tips+="</ul>";
		}
		record_data+="<li class='celeb-list__item celeb-list__item--mbs'><button class='util-btn hide'><span class='icon-common'></span></button>";
		record_data+="<div class='celeb-list__pic-box'><a href='/home/"+record.nick_name+"'><img src='"+record.profile_image+"' class='orientation hide'></a></div>";
		record_data+="<div class='celeb-list__nick-box'><a href='/home/"+record.nick_name+"' class='celeb-list__nick'>"+record.nick_name+"</a><span class='mbs-rating'>"+record.name+"<em class='period'>"+record.startdate+" ~ "+record.enddate+"</em><em class='roundup'>("+record.pay_count+"회차)</em></span>" +
				tips+"</div>";
		record_data+="</li>";
		
		
	}
		record_data+="</ul>";
	
	
	
	record_data+="<div class='paging'>"+data.pagination+"</div>";
	
	
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
	
	showImage(".orientation.hide",true);
}

function loadPage(tag,param,skipHistory){	
	
	if(!tag){
		return;
	}
	if(!param)param="";
	
	var temp=tag.substring(1);//.split("/");
	var tagList="";
	var _index=temp.indexOf("?");
	if(_index>=0){//#list?page=2
		var _t=temp.substring(0, _index); //tag
		
		tagList=_t.split("/");
		if(!param){
			param=temp.substring(_index);
		}
	}else{//#list
		tagList=temp.split("/");
	}
	
	if(tagList.length==1){		
		
		eval("load_"+tagList[0]+"('','"+param+"',"+skipHistory+")");
		
	}else if(tagList.length==2){
		
		eval("load_"+tagList[0]+"('"+tagList[1]+"','"+param+"',"+skipHistory+")");
		
	}	
	
}

function tabProc(tab){
	$(".sub-tab li").removeClass("current");
	$(".sub-tab li[data-tab="+tab+"]").addClass("current");
}

function load_list(type,param,skipHistory){
	if(!type)type=="";
	else type="/"+type;
	$.get("/membership/list"+type,function(res){
		
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		if(!skipHistory){
			var stateObj={"tag":"#list"+type,"param":param};
			history.pushState(stateObj, "hoo", "/membership#list"+type+param);
		}
		var membershipData=JSON.parse(data.msg);
		dispMembershipList(membershipData,wrap_id);
		tabProc("list");
		
	});	
}

function load_writeform(idx,param,skipHistory){
	
	if(!idx)idx="";
	else idx="/"+idx;
	
	if(!param)param="";
	
	$.get("/membership/writeform"+idx,function(res){
		if(!skipHistory){
			var stateObj={"tag":"#writeform"+idx,"param":param};
			history.pushState(stateObj, "hoo", "/membership#writeform"+idx+param);
		}
		
		
		
		var data=JSON.parse(res);
		if(data.result!="Y"){
			var failData=JSON.parse(data.msg);
			Toast.show(failData.msg,
				{
					modal:true,
					callback:function(){loadPage(failData.tag);} 
				}
			);
			return;
		}
		
		var membershipData=JSON.parse(data.msg);
		//dispWriteForm(membershipData.membership, wrap_id);
		
		$(form_id).html("");
		$(wrap_id).html(membershipData.view);
		tabProc("list");
				
	});
	
}



function load_notice(type,param,skipHistory){
	if(!type)type=="";
	else type="/"+type;
	$.get("/membership/notice"+type+param,function(res){
		
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		
		if(!skipHistory){
			var stateObj={"tag":"#notice"+type,"param":param};
			history.pushState(stateObj, "hoo", "/membership#notice"+type+param);
		}
		
		var membershipData=JSON.parse(data.msg);
		dispNoticeList(membershipData,form_id,wrap_id);
		tabProc("notice");
	});
}


function load_members(type,param,skipHistory){
	if(!type)type=="";
	else type="/"+type;
	$.get("/membership/members"+type,function(res){
		
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		if(!skipHistory){
			var stateObj={"tag":"#members"+type,"param":param};
			history.pushState(stateObj, "hoo", "/membership#members"+type+param);
		}
		var membershipData=JSON.parse(data.msg);
		dispMemberList(membershipData,form_id,wrap_id);
		tabProc("members");
		
	});	
}


function numberWithCommas(obj){
	var val=obj.value.replace(/\,/g,"");
	if(!val || val=="0"){
		obj.value="";
	}else{
		obj.value=val.format();
	}
	$("#p_membership_price").html(obj.value);
}

function showMembershipName(obj){
	$("#p_membership_name").html(obj.value);
}

function addTip(){
	var sort=2;
	$('.tip-ele').each(function(){
		var tsort=parseInt($(this).attr("data-sort"));
		if(tsort>sort)sort=tsort;
	});
	sort=parseInt(sort)+1;
	$("#tips_wrap").prepend("<dd><input type='text' placeholder='멤버십 혜택을 입력해주세요.(100자이하)' class='tip-ele' data-sort='"+sort+"' onkeyup='showTip(this,"+sort+")' maxlength='100'><button class='btn-remove' onclick='removeTip("+sort+")'></button></dd>");
	$("#p_membership_tip").prepend("<li class='tip-item' id='tip_"+sort+"'></li>");
}
function showTip(obj,sort){
	var text=$.trim(obj.value);
	if(!text){
		obj.value="";
		$("#tip_"+sort).html("");
	}else{
		var $tip=$("#tip_"+sort);
		if(!$tip.length){
			$("#p_membership_tip").append("<li class='tip-item' id='tip_"+sort+"'>"+obj.value+"</li>");
		}else{
			$tip.html(text);
		}
	}
	
}
function removeTip(sort){	
	$(".tip-ele[data-sort="+sort+"]").parent().remove();
	$("#tip_"+sort).remove();
}


function addMembership(idx){
	var p={};
	p[window.csrf_name]=window.csrf_val;
	
	p.name=$.trim($("#p_membership_name").html());
	p.price=$.trim($("#p_membership_price").html().replace(/\,/g,""));
	p.tips=[];
	$('.tip-item').each(function(){
		var t=$.trim($(this).html());
		
		if(t){
			p.tips.push(t);
		}
	});
	p.noti=$.trim($("#noti").val());
	
	var file_info=$("#memship_thumb");
	
	if(file_info.length){
		p.file_idx=file_info.data("file-idx");
	}
	
	if(!p.name){
		Toast.show("멤버십 이름을 입력 하세요.",{modal:true});
		return;
	}
	if(!p.price){
		Toast.show("멤버십 가격을 입력 하세요.",{modal:true});
		return;
	}
	if(p.price<1000 ){
		Toast.show("멤버십 가격은 1,000원 이상으로 입력 하세요.",{modal:true});
		return;
	}
	if(!p.tips.length || p.tips.length<2){
		Toast.show("멤버십 내용을 추가 해주세요.",{modal:true});
		return;
	}
	
	if(!$("#agree-mbs").prop("checked")){
		Toast.show("멤버십 내용 확인에 체크 해야 합니다.",{modal:true});
		return;
	}
	
	Toast.show(idx ? "수정 하겠습니까?":"등록 하겠습니까?",{
		ok:function(){
			$.post("/membership/write/"+idx,p,function(res){
				var data=JSON.parse(res);
				if(data.result=="Y"){
					Toast.show(data.msg,{modal:true,callback:function(){location.reload()}});
				}else{
					Toast.show(data.msg,{modal:true});
				}
			});
		}
	});
	
}
