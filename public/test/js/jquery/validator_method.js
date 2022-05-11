/**
 * 
 */



$(function(){
	$.validator.addMethod("checkY",function(value,element){		
		return this.optional(element) || value=="Y";
	});
	
	$.validator.addMethod("checkPhone",function(value,element){		
		return this.optional(element) || /^010[1-9]{1}[0-9]{3}(\d{4})$/.test(value) ;
	});

	$.validator.addMethod("checkPasswd",function(value,element){		
		return this.optional(element) || /^[^\s*]{6,}$/.test(value) ;
	});
		
	
	$.validator.addMethod("checkId",function(value,element){		
		return this.optional(element) || /^[a-z]{1}[a-z0-9_]{5,19}$/.test(value) ;
	});
	
	$.validator.addMethod("checkNick",function(value,element){		
		return this.optional(element) || /^[가-힣a-zA-z0-9_\.]{2,20}$/.test(value) ;
	});
	
	$.validator.addMethod("checkBoardName",function(value,element){		
		return this.optional(element) || /^[가-힣a-zA-z0-9\.]{2,12}$/.test(value) ;
	});
	
	$.validator.addMethod("checkBoardId",function(value,element){		
		return this.optional(element) || /^[a-z]{1}[a-z0-9_]{2,19}$/.test(value) ;
	});
	
	$.validator.addMethod("checkDupId",function(value,element){		
		 var postURL = "/member/checkid";
		    $.ajax({
		        cache:false,
		        async:false,
		        type: "POST",
		        data: "user_id=" + value+"&jjang0u_csrf_token="+$("#jjang0u_csrf_token").val(),
		        url: postURL,
		        success: function(msg) {
		            result = (msg=='TRUE') ? true : false;
		        },
		        fail :function(){
		        	
		        	result=false;
		        }
		    });
		    return result;


	});
});