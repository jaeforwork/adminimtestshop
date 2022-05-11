
window.addEventListener("popstate",function(){
	var data = history.state;
	//console.log(data);
	  
	  if(data){
		  if(data.tag){
			  //console.log("history tag ");
			  //console.log("popstate "+data.tag+data.param);
			  loadPage(data.tag,data.param,true);
			  
		  }
	  }else{
		  
		  history.back();
	  }
});





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




function load_token(type,param,skipHistory){
	
	$.get("/record/token/"+type+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#token/"+type,"param":param};
			history.pushState(stateObj, "hoo", "/record#token/"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var membershipData=JSON.parse(data.msg);
		dispToken(membershipData,form_wrap_id,wrap_id,type);
		$(".my10 > a").removeClass("active");
		$("#my10_"+type).addClass("active");
	});
	
}
function load_pay(type,param,skipHistory){
	
	$.get("/record/pay"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#pay","param":param};			
			history.pushState(stateObj, "hoo", "/record#pay"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var paymentData=JSON.parse(data.msg);		
		dispPayment(paymentData, form_wrap_id, wrap_id);
		
		$(".my10 > a").removeClass("active");
		$("#my10_pay").addClass("active");
	});
	
}


//환전 내역
function load_exchange(type,param,skipHistory){	
	
	
	$.get("/record/exchangelist"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#exchange","param":param};			
			history.pushState(stateObj, "hoo", "/record#exchange"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var paymentData=JSON.parse(data.msg);		
		dispRecordExchange(paymentData, form_wrap_id, wrap_id);
		
		
		$(".my10 > a").removeClass("active");
		$("#my10_exchange").addClass("active");
	});	
	
}


//가입한 멤버십
function load_membership(type,param,skipHistory){	
	
	
	$.get("/record/membership"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#membership","param":param};			
			history.pushState(stateObj, "hoo", "/record#membership"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var paymentData=JSON.parse(data.msg);		
		dispMembership(paymentData,  wrap_id);
		
		
		$(".my10 > a").removeClass("active");
		$("#my10_membership").addClass("active");
	});	
	
}


function load_memberdeny(type,param,skipHistory){	
	
	
	$.get("/record/memberdeny"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#memberdeny","param":param};			
			history.pushState(stateObj, "hoo", "/record#memberdeny"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var denyData=JSON.parse(data.msg);		
		dispMemberdeny(denyData,  wrap_id);
		
		
		$(".my10 > a").removeClass("active");
		$("#my10_memberdeny").addClass("active");
	});	
	
}



function load_blind(type,param,skipHistory){	
	
	
	$.get("/record/blind"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#blind","param":param};			
			history.pushState(stateObj, "hoo", "/record#blind"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var denyData=JSON.parse(data.msg);		
		dispBlind(denyData,  wrap_id);
		
		
		$(".my10 > a").removeClass("active");
		$("#my10_blind").addClass("active");
	});	
	
}

function load_heartsummary(type,param,skipHistory){	
	
	
	$.get("/record/heartsummary"+param,function(res){
		
		if(!skipHistory){
			var stateObj={"tag":"#heartsummary","param":param};			
			history.pushState(stateObj, "hoo", "/record#heartsummary"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var denyData=JSON.parse(data.msg);		
		dispHeartsummary(denyData,  wrap_id);
		
		
		$(".my10 > a").removeClass("active");
		$("#my10_heartsummary").addClass("active");
	});	
	
}


//Record 젤리 사용 내역 paging
$(document).on("click","a[data-type=record_dia_send_page],button[data-type=record_dia_send_page]",function(){
	var page=$(this).attr("data-page");		
	var type=$("#record_form select[name=type]").val();	
	var search=$("#record_form input[name=search]").val();	
	
	
	loadPage("#token/send","?page="+page+"&type="+type+"&search="+search);
});

//Record 젤리 받은 내역 paging
$(document).on("click","a[data-type=record_dia_rev_page],button[data-type=record_dia_rev_page]",function(){
	var page=$(this).attr("data-page");		
	var type=$("#record_form select[name=type]").val();	
	var search=$("#record_form input[name=search]").val();	
	
	
	loadPage("#token/rev","?page="+page+"&type="+type+"&search="+search);
});

//Record 결제 내역 paging
$(document).on("click","a[data-type=record_payment],button[data-type=record_payment]",function(){
	var page=$(this).attr("data-page");		
	$("#record_form input[name=page]").val(page);
	//recordPayment("#record_form");
	
	var page=$(this).attr("data-page");		
	var sdate=$("#record_form input[name=sdate]").val();	
	var edate=$("#record_form input[name=edate]").val();	
	
	var param= page ? "?page="+page : "?page=1";
	param= param +(sdate ? "&sdate="+sdate : "") + (edate ? "&edate="+edate : "");
	
	loadPage("#pay",param);
});

//Record 환전 내역 paging
$(document).on("click","a[data-type=record_exchange],button[data-type=record_exchange]",function(){
	var page=$(this).attr("data-page");	
	$("#record_form input[name=page]").val(page);
	var param="";
	$("#record_form input, #record_form select").each(function(){
		var $p=$(this);
		if($p.val()!=""){			
			if(param==""){
				param="?"+$p.attr("name")+"="+$p.val();
			}else{
				param +="&"+$p.attr("name")+"="+$p.val();
			}
		}
	});
	
	
	loadPage("#exchange",param);
});


//Record 가입멤버십 paging
$(document).on("click","a[data-type=membership_join_page],button[data-type=membership_join_page]",function(){
	var page=$(this).attr("data-page");	
	$("#record_form input[name=page]").val(page);
	var param="";
	$("#record_form input, #record_form select").each(function(){
		var $p=$(this);
		if($p.val()!=""){			
			if(param==""){
				param="?"+$p.attr("name")+"="+$p.val();
			}else{
				param +="&"+$p.attr("name")+"="+$p.val();
			}
		}
	});
	
	
	loadPage("#membership",param);
});

/*
$(document).on("click","a[data-type=membership_join_page]",function(){
	var page=$(this).attr("data-page");
	
	var param="?page="+page;	
	loadPage("#membership",param);
});*/


$(document).on("click","a[data-type=deny_page]",function(){
	var page=$(this).attr("data-page");
	
	var param="?page="+page;	
	loadPage("#memberdeny",param);
});

$(document).on("click",".btn-membership-cancel",function(){
	var idx=$(this).attr("data-idx");
	Toast.show("멤버십 연장을 중지 하겠습니까?<br>연장 중지를 해도 만료 기간까지는 멤버십이 유지 됩니다.",{ok:function(){
		
		var p={};
		p.idx=idx;
		p[window.csrf_name]=window.csrf_val;
		$.post("/record/cancelmembership",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{callback:function(){
					var page=$(".paging .active").attr("data-page");
					loadPage("#membership","?page="+page);
				}});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
	
	
});


$(document).on("click",".btn-deny-cancel",function(){
	var idx=$(this).attr("data-user-idx");
	Toast.show("차단을 해제 하겠습니까?",{ok:function(){
		
		var p={};
		p.user_idx=idx;
		p[window.csrf_name]=window.csrf_val;
		$.post("/record/canceldeny",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{callback:function(){
					
					loadPage("#memberdeny","");
				}});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
	
	
});

$(document).on("click",".btn-blind-cancel",function(){
	var idx=$(this).attr("data-user-idx");
	Toast.show("블라인드 해제 하겠습니까?",{ok:function(){
		
		var p={};
		p.user_idx=idx;
		p[window.csrf_name]=window.csrf_val;
		$.post("/record/cancelblind",p,function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){
				Toast.show(data.msg,{callback:function(){
					
					loadPage("#blind","");
				}});
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
	
	
});

//젤리 사용/받은 내역 표시 
function dispToken(data, form_wrap, data_wrap,what){
	var who="받은이";
	if(what=="rev"){
		who="보낸이";
		$("#record_title").html("젤리 받은 내역");
	}else{
		$("#record_title").html("젤리 사용 내역");
	}
	var selectStr="<select name='type'><option value=''>유형선택</option>";
	for(var i=0;i<data.type_list.length;i++){
		if(data.type==data.type_list[i]){
			selectStr+="<option value='"+data.type_list[i]+"' selected>"+data.type_list[i]+"</option>";
		}else{
			selectStr+="<option value='"+data.type_list[i]+"'>"+data.type_list[i]+"</option>";
		}
	}
	selectStr+="</select>";
	var record_form="<form id='record_form' ><input type='hidden' name='page' value='"+data.page+"'>"+
		"<div class='selector'>"+selectStr+"</div>"+
		"<div class='search-box'><input type='text' name='search' placeholder='닉네임' value='"+data.search+"'></div><button type='button' class='btn-chart-search' data-type='record_dia_"+what+"_page' data-page='1'>검색</button></form>";
	
	var record_data="<table class='data-table'>";
	
	if(what=="rev"){
		record_data+="<colgroup><col width='10%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'><col width='15%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>"+who+"</td><td>유형</td><td>유형상세</td><td>젤리 수</td><td>적립 젤리 수</td><td>날짜</td></thead>"
		"<tbody>";
	}else{
		record_data+="<colgroup><col width='10%'><col width='20%'><col width='15%'><col width='20%'><col width='15%'><col width='15%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>"+who+"</td><td>유형</td><td>유형상세</td><td>젤리 수</td><td>날짜</td></thead>"
		"<tbody>";
	}
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		if(!log.nick_name)log.nick_name="";
		if(what=="rev"){
			record_data+="<tr><td>"+log.list_num+"</td><td>"+log.nick_name+"</td><td>"+log.type+"</td><td>"+log.sub_type+"</td><td class='strong'>"+log.dia.format()+"개</td><td class='strong'>"+log.dia_rev.format()+"개</td><td>"+log.date+"</td></tr>";
		}else{
			record_data+="<tr><td>"+log.list_num+"</td><td>"+log.nick_name+"</td><td>"+log.type+"</td><td>"+log.sub_type+"</td><td class='strong'>"+log.dia.format()+"개</td><td>"+log.date+"</td></tr>";
		}
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
}

//결제 내역
function dispPayment(data, form_wrap, data_wrap){
	$("#record_title").html("결제내역");
	var record_form="<form id='record_form' ><input type='hidden' name='page' value='"+data.page+"'>"+		
		"<div class='search-box' style='width:100px'><input type='text' name='sdate' id='sdate' placeholder='시작일' value='"+data.sdate+"'></div><div class='from-to'> ~ </div><div class='search-box' style='width:100px'><input type='text' id='edate' name='edate' placeholder='종료일' value='"+data.edate+"'></div><button type='button' class='btn-chart-search' data-type='record_payment' data-page='1'>검색</button></form>";
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='4%'><col width='8%'><col width='10%'><col width='7%'><col width='7%'><col width='5%'><col width='5%'><col width='5%'><col width='5%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>상품명</td><td>결제수단</td><td>젤리 수</td><td>결제금액</td><td>등록일</td><td>처리일</td><td>상태</td><td></td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		var cnt="";
		if(log.type=="토큰"){
			cnt=log.dia.format()+"개";
		}else{
			cnt=log.type;//"후원";
		}
		var status=log.status=="Y"? "결제완료" : (log.status=="N" ? "결제취소": (log.status=="C" ? "입금취소": "입금대기")); 
		var statusClass=log.status=="R" ? "class='strong'" : "";
		record_data+="<tr><td>"+log.list_num+"</td><td>"+log.goods_name+"</td><td>"+log.method_name+"</td><td>"+cnt+"</td><td>"+log.price.format()+"원</td><td>"+log.date+"</td>" +
				"<td>"+log.pdate+"</td><td "+statusClass+">"+status+"</td><td><button type='button' data-idx='"+log.idx+"' class='btn-paydetail'>상세정보</button></td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
	
	$("#sdate").datepicker(clareCalendar);
	$("#edate").datepicker(clareCalendar);
	$("img.ui-datepicker-trigger").attr("style","display:none; cursor:pointer;"); //이미지버튼 style적용
	$("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김 
}





function dispRecordExchange(data, form_wrap, data_wrap){
	$("#record_title").html("환전내역");
	var record_form="<form id='record_form' ><input type='hidden' name='page' value='"+data.page+"'>"+
		"<div class='selector'><select name='status'><option value=''>유형선택</option><option value='대기' "+(data.status=="대기" ? "selected":"")+">대기</option><option value='승인' "+(data.status=="승인" ? "selected":"")+">승인</option><option value='취소' "+(data.status=="취소" ? "selected":"")+">취소</option><option value='거부' "+(data.status=="거부" ? "selected":"")+">거부</option></select></div>"+
		"<div class='from-to'> </div><div class='search-box' style='width:100px'><input type='text' name='sdate' id='sdate' placeholder='시작일' value='"+data.sdate+"'></div><div class='from-to'> ~ </div><div class='search-box' style='width:100px'><input type='text' id='edate' name='edate' placeholder='종료일' value='"+data.edate+"'></div><button type='button' class='btn-chart-search' data-type='record_exchange' data-page='1'>검색</button></form>";
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='5%'><col width='10%'><col width='16%'><col width='9%'><col width='9%'><col width='9%'><col width='7%'><col width='7%'><col width='9%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>은행</td><td>계좌번호</td><td>젤리 수</td><td>요청금액</td><td>환전액</td><td>원천징수</td><td>날짜</td><td>상태</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		var btn="<a href='javascript:;' onclick='exchangeDetail(\""+log.idx+"\")'>상세</a> / ";
		if(log.status=="대기"){
			btn+="<a href='javascript:;' class='btn-exchange-cancel' onclick=\"cancelExchange('"+log.idx+"',this)\">대기</a>"
		}else{
			btn+=log.status;
		}
		record_data+="<tr><td>"+log.list_num+"</td><td>"+log.bank+"</td><td>"+log.account+" - "+log.account_name+"</td><td>"+
		log.dia.format()+"개</td><td class='strong'>"+log.amount.format()+"원</td><td class='strong'>"+log.amount_ex.format()+"원</td><td class='strong'>"+log.fee+"%</td><td>"+log.date+"</td><td id='ex_status_"+log.idx+"'>"+btn+"</td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
	
	$("#sdate").datepicker(clareCalendar);
	$("#edate").datepicker(clareCalendar);
	$("img.ui-datepicker-trigger").attr("style","display:none; cursor:pointer;"); //이미지버튼 style적용
	$("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김 
}



function dispMembership(data, data_wrap){
	
	$("#record_title").html("가입 멤버십");
	
	var record_form="<form id='record_form'><input type='hidden' name='page' value='"+data.page+"'>"+
	"<div class='selector' style='width:150px'><select name='status'><option value='C' "+(data.status=="C" ? "selected":"")+">멤버십가입중</option><option value='E' "+(data.status=="E" ? "selected":"")+">멤버십가입종료</option></select></div>"+
	"<button type='button' class='btn-chart-search' data-type='membership_join_page' data-page='1'>검색</button></form>";
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='25%'><col width='5%'><col width='7%'><col width='5%'><col width='25%'><col width='10%'></colgroup>"+
		"<thead><tr class='strong'><td>멤버십</td><td>가입일</td><td>기간</td><td>회차</td><td>혜택</td><td>상태</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];
		var btn="";
		if(data.status=="C"){
			if(log.status=="Y"){
				btn="<a href='javascript:;' class='btn-membership-cancel' data-idx='"+log.idx+"'>정상</button>"
			}else{
				btn="멤버십연장중지";
			}
		}else{
			btn="멤버십종료";
		}
		
		var tips="";
		
		if(log.info.tips && log.info.tips.length){
			tips="<ul class='tips mbs-benefit'>";
			for(var a=0;a<log.info.tips.length;a++){
				tips+="<li>"+log.info.tips[a]+"</li>";
				
			}
			tips+="</ul>";
		}
		
		
		record_data+="<tr class='membership-row'><td>"+log.name+"[<a href='/home/"+log.nick_name+"'>"+log.nick_name+"</a>]<div>금액 : "+log.amount.format()+"원</div></td><td>"+log.str_regdate+"</td><td>"+log.str_startdate+"<br>~<br>"+log.str_enddate+"</td><td>"+
		log.pay_count+"회차</td><td style='text-align:left;padding-left:10px'>"+tips+"</td><td>"+btn+"</td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	
	
	
	
	
	$(form_wrap_id).html(record_form);
	$(data_wrap).html(record_data);
	
	showImage(".orientation.hide",true);
}



function dispMemberdeny(data, data_wrap){
	
	$("#record_title").html("차단회원");
	
	
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='3%'><col width='20%'><col width='10%'><col width='10%'><col width='10%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>닉네임</td><td>차단방법</td><td>날짜</td><td>해제</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		
		var log=data.list[i];
		
		record_data+="<tr><td>"+log.list_num+"</td><td><div class='record__deny-picbox'><img src='"+log.profile_image+"' class='orientation hide'></div>"+log.nick_name+"</td><td>"+log.info+"에서 차단</td><td>"+
		log.regdate+"</td><td><button type='button' class='btn-deny-cancel' data-user-idx='"+log.user_idx+"'>차단해제</button></td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";	
		
	$(form_wrap_id).html("");
	$(data_wrap).html(record_data);
	
	showImage(".orientation.hide",true);
}


function dispBlind(data, data_wrap){
	
	$("#record_title").html("블라인드");
	
	
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='3%'><col width='30%'><col width='10%'><col width='10%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>닉네임</td><td>날짜</td><td>해제</td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		
		var log=data.list[i];
		
		record_data+="<tr><td>"+log.list_num+"</td><td><div class='record__deny-picbox'><img src='"+log.profile_image+"' class='orientation hide'></div>"+log.nick_name+"</td><td>"+
		log.regdate+"</td><td><button type='button' class='btn-blind-cancel' data-user-idx='"+log.user_idx+"'>블라인드해제</button></td></tr>";
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";	
		
	$(form_wrap_id).html("");
	$(data_wrap).html(record_data);
	
	showImage(".orientation.hide",true);
}



function dispHeartsummary(data, data_wrap){
	
	$("#record_title").html("받은하트");
	
	
	
	var record_data=
		"<table class='data-table'>"+
		"<colgroup><col width='6%'><col width='40'><col width='20%'><col ><col ></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td></td><td>닉네임</td><td>하트</td><td></td></thead>"
		"<tbody>";
	for(var i=0;i<data.list.length;i++){
		
		var log=data.list[i];
		
		record_data+="<tr><td>"+log.list_num+"</td><td><div class='record__deny-picbox'><img src='"+log.profile_image+"' class='orientation hide' onclick='location.href=\"/home/"+log.nick_name+"\"'></div></td>" +
				"<td style='text-align:left;margin-left:4px'><a href='/home/"+log.nick_name+"'>"+log.nick_name+"</a></td><td><button type='button' class='global__heart-btn active'><span class='icon-common'></span></button> "+log.heart.format()+"개</td><td></td></tr>";
		
	}
	record_data+="</table><div class='paging'>"+data.pagination+"</div>";
	
	
	
	$(form_wrap_id).html("");
	$(data_wrap).html(record_data);
	
	showImage(".orientation.hide",true);
}

//환전 취소
function cancelExchange(idx,btn){
	Toast.show("환전 취소 하겠습니까?", { ok:function(){
		
		var p={};
		p[window.csrf_name]=window.csrf_val;
		p.idx=idx;
		
		$.post("/record/cancelexchange",p, function(res){
			var data=JSON.parse(res);
			if(data.result=="Y"){				
				Toast.show(data.msg);
				$(btn).remove();
				$("#ex_status_"+idx).html("취소");
			}else{
				Toast.show(data.msg,{modal:true});
			}
		});
	}});
}



function exchangeDetail(idx){
	//결제 내역 상세
	
	var idx=idx;
	var p={};
	p[window.csrf_name]=window.csrf_val;
	p.idx=idx;
	$.post("/record/exchangedetail",p, function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			var log=JSON.parse(data.msg);
			var str="<table class='payment-table' ><colgroup><col width='30%'><col width='70%'></colgroup>";
				str+="<tr><th>신청자</th><td>"+log.nick_name+"</td></tr>"
					+"<tr><th>환전상태</th><td>"+log.status +"</td></tr>"
					+"<tr><th>젤리수</th><td>"+log.dia.format()+"개</td></tr>"
					+"<tr><th>신청금액</th><td>"+log.amount.format()+"원</td></tr>"
					+"<tr><th>환전금액</th><td>"+log.amount_ex.format()+"원</td></tr>"				
					+"<tr><th>은행</th><td>"+log.bank+"</td></tr>"			
					+"<tr><th>계좌번호</th><td>"+log.account+"</td></tr>"
					+"<tr><th>예금주</th><td>"+log.account_name+"</td></tr>"
					+"<tr><th>신분증사본</th><td>"+(log.idcard_image ? "<a href='"+log.idcard_image+"' target='_new'><img src='"+log.idcard_image+"' class='exchange-info-img'></a>":"")+"</td></tr>"
					+"<tr><th>통장사본</th><td>"+(log.bank_account_image ? "<a href='"+log.bank_account_image+"' target='_new'><img src='"+log.bank_account_image+"' class='exchange-info-img'></a>":"")+"</td></tr>"
										
					+"<tr><th>신청일자</th><td>"+log.strregdate+"</td></tr>";
					
					
				if(log.status !="대기"){
					str+="<tr><th>처리일자</th><td>"+log.strcompletedate+" ["+log.status+"됨]</td></tr>";
					
				}else{
					str+="<tr><th>완료처리</th><td><a href='javascript:;allow("+log.idx+")' style='color:#3366ff;font-weight:bold;margin-right:15px;'>승인</a><a href='javascript:;showReject("+log.idx+")' style='color:#ff6633;font-weight:bold;margin-right:15px;'>거부</a></td></tr>";
					str+="<tr id='reject_"+log.idx+"' style='display:none'><th>거절사유</th><td><textarea id='message' placeholer='거부사유작성. 200자 이내' maxlength=200 style='width:70%;height:50px'></textarea><div style='margin-top:10px'><button type='button' class='select-btn1 btn-blue' onclick='reject("+log.idx+")'>완료</button></div></td></tr>";					
				}
				if(log.reject_msg){
					str+="<tr><th>거부사유</th><td>"+log.reject_msg+"</td></tr>";
				}	
				str+="</table>";
				
				var html="<div style='width:500px; max-height:calc(100vh - 200px);overflow:auto;'>"+str+"</div>";
				window.paydetail_popup=Popup.show("환전 상세 정보",html);
		}else{
			Toast.show(data.msg,{modal:true});
		}
	});	
}
