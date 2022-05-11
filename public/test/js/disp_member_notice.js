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
	
	
	//페이징
	$(document).on("click","a[data-type=membership_notice_page],button[data-type=membership_notice_page]",function(){
		var page=$(this).attr("data-page");		
		$("#notice_form input[name=page]").val(page);
		
		var membership_idx=$("#notice_form select[name=type]").val();
		var page=$(this).attr("data-page");		
		if(membership_idx)membership_idx="/"+membership_idx;
		
		var param= page ? "?page="+page : "?page=1";
		
		
		loadPage("#list"+membership_idx,param);
	});
	
	
});







function dispNoticeList(data, form_wrap, data_wrap, has_btn_back){
	var options="<option value=''>전체멤버십</option>";
	for(var i=0;i<data.select_list.length;i++){
		var selected="";
		if(data.select_list[i].idx==data.membership_idx){
			selected="selected";
		}
		options+="<option value='"+data.select_list[i].idx+"' "+selected+">"+data.select_list[i].name+" - "+data.select_list[i].nick_name+"</option>";
	}
	var record_form="<form id='notice_form' action=''><input type='hidden' name='page' value='"+data.page+"'>"+
		"<div class='selector' style='width:200px'><select name='type'>"+options+"</select></div>"+
		"<button type='button' class='btn-chart-search' data-type='membership_notice_page' data-page='1'>검색</button></form>";
	
	var record_data="<table class='data-table membership-table'>";
	
	
		record_data+="<colgroup><col width='5%'><col ><col width='15%'><col width='20%'><col width='15%'></colgroup>"+
		"<thead><tr class='strong'><td>번호</td><td>제목</td><td>작성자</td><td>멤버십</td><td>등록일</td></thead>"
		"<tbody>";
	
	for(var i=0;i<data.list.length;i++){
		var log=data.list[i];		
		record_data+="<tr id='notice_data_"+log.idx+"'><td>"+log.list_num+"</td><td><a href='javascript:;' class='notice-view notice-title' data-idx='"+log.idx+"'>"+log.title+"</a></td><td>"+log.nick_name+"</td><td>"+log.membership_name+"</td><td >"+log.str_regdate+"</td></tr>";
		record_data+="<tr id='notice_"+log.idx+"' class='hide notice-tr'><td colspan='5' class='align-left lh20 notice-content'>"+log.notice+"</td></tr>";
	}
	record_data+="</table>";
	if(has_btn_back){
		record_data+="<div class='mt20'><button type='button' class='btn-chart-search' onclick='loadPage(\"#list\");'>목록</button></div>";
	}else{
		record_data+="<div class='paging'>"+data.pagination+"</div>";
	}
	
	$(form_wrap).html(record_form);
	$(data_wrap).html(record_data);
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

function load_list(type,param,skipHistory){
	if(!type)type=="";
	else type="/"+type;
	$.get("/membernotice/list"+type+param,function(res){
		if(!skipHistory){
			var stateObj={"tag":"#list"+type,"param":param};
			history.pushState(stateObj, "hoo", "/membernotice#list"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var membershipData=JSON.parse(data.msg);
		dispNoticeList(membershipData,form_id,wrap_id);
	});
}

function load_view(type,param,skipHistory){
	if(!type)type=="";
	else type="/"+type;
	$.get("/membernotice/view"+type+param,function(res){
		if(!skipHistory){
			var stateObj={"tag":"#view"+type,"param":param};
			history.pushState(stateObj, "hoo", "/membernotice#view"+type+param);
		}
		var data=JSON.parse(res);
		if(data.result!="Y")return;
		var membershipData=JSON.parse(data.msg);
		dispNoticeList(membershipData,form_id,wrap_id,true);
	});
}
