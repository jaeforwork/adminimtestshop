/**
 * 
 */


function aes256(data, key, iv){
	var key = CryptoJS.enc.Utf8.parse(key);
  	var iv = CryptoJS.enc.Utf8.parse(iv);
  	var encrypted = CryptoJS.AES.encrypt(data, key, {	iv:iv}).toString();
  	var e64 = CryptoJS.enc.Base64.parse(encrypted);
  	var eHex = e64.toString(CryptoJS.enc.Hex);
  	
  	return eHex;
}
var loginProc={	
	loadVaild: function(form, callback){
		$(function(){
			
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
		        submitHandler: function() {
		        	var button=$(form+" button[type=submit]");
		        	
		        	button.prop("disabled",true);
		        	var param1={};		      	
			      	param1[window.csrf_name]=window.csrf_val;
			      	$.post("/account/loadsecure",param1,function(res){
			      		var data=null;
			      		try{
			      			data=JSON.parse(res);
			      		}catch(Exception){
			      			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
			        		
			      			button.prop("disabled",false);
			        		return;
			      		}
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			
			      			var email=$.trim($(form+" input[name=email]").val());
					      	var pwd=$.trim($(form+" input[name=password]").val());					      	
					      	var return_url=$.trim($(form+" input[name=return_url]").val());	
					      	
					      	
					      	
					      	var param_temp={};					      	
					      	param_temp["email"]=email;
					      	param_temp["password"]=pwd;
					      	param_temp["return_url"]=return_url;
					      	
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;				      	
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      
					      	
					      	
					      	$.post("/account/login",param,function(res){
					      		try{
					      			button.prop("disabled",false);
					      			
					      			
					        		var data=JSON.parse(res);
					        		callback(data);
					      		}catch(e){
					      			
					      			button.prop("disabled",false);
					      		}
				        		
				        	}).fail(function(){
				        		Toast.show("로그인 오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
			      	
			      	
			      	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		        	email: {
		                required : true
		            },
		            password: {
		                required : true
		            }
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	email: {	
		        		required:"아이디를 입력하세요."		        		
		            },
		            password: {
		            	required: "비밀번호를 입력하세요."
		            }        
		            
		          
		        }, errorPlacement: function(error, element) {
		        	
		        }, invalidHandler: function(form, validator) {
		        	var errors = validator.numberOfInvalids();
	        		if (errors) {
		            	 //$("#errorMsg").css("display","block").text(validator.errorList[0].message);			                 
		                 //validator.errorList[0].element.focus();
		                var element=validator.errorList[0].element;
	        			$(element).parent().next().html(validator.errorList[0].message);
	        			
		             }
		        },success: function(label,element) {
					// set &nbsp; as text for IE					
		        	
		        	$(element).parent().next().html("");
					
					
				}
	
		    })
		});
	},
	
	
	
	snsLoadVaild: function(form,callback){
		$(function(){
			
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
		        submitHandler: function() {
		        	
		        	var button=$(form+" button[type=submit]");
		        	button.prop("disabled",true);
		        	
		        	var sns=$.trim($(form+" input[name=sns]").val());
			      	var access_token=$.trim($(form+" input[name=access_token]").val());					      	
			      	var return_url=$.trim($(form+" input[name=return_url]").val());	
			      	
			      	
			      	
			      	if(sns=="" || access_token==""){
			      		return;
			      	}
			      	
			      	
			      	
		        	var param1={};		      	
			      	param1[window.csrf_name]=window.csrf_val;
			      	$.post("/account/loadsecure",param1,function(res){
			      		var data=null;
			      		try{
			      			data=JSON.parse(res);
			      		}catch(Exception){
			      			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
			        		
			      			button.prop("disabled",false);
			        		return;
			      		}
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			
					      	
					      	var param_temp={};					      	
					      	param_temp["sns"]=sns;
					      	param_temp["access_token"]=access_token;
					      	param_temp["return_url"]=return_url;
					      	
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;					      	
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      
					      	
					      	
					      	$.post("/account/loginsns",param,function(res){
					      		try{
					        		var data=JSON.parse(res);
					        		if(data.result=="Y" || data.result=="J"){
					        			if(callback){					        				
					        				callback(data);
					        			}
					        								        			
					        		}else{					        		
					        			Toast.show(data.msg,{modal:true});      			
					        			
					        		}
					        		button.prop("disabled",false);
					      		}catch(e){
					      			
					      			button.prop("disabled",false);
					      		}
				        		
				        	}).fail(function(){
				        		Toast.show("로그인 오류. 관리자에게 문의 하세요.",{modal:true});				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
			      	
			      	
			      	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		        	
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {		        	
		            
		          
		        }, errorPlacement: function(error, element) {
		        	
		        }, invalidHandler: function(form, validator) {
		        	
		        },success: function(label,element) {					
					
				}
	
		    })
		});
	},
	
};


var joinProc={	
	joinTempVaild: function(form,callback){
		
		$(function(){			
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: ".ignore",
		        submitHandler: function() {
		        	
		        	var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        			
		        	
		        	var email=$.trim($(form+" input[name=email]").val());
			      	var pwd=$.trim($(form+" input[name=passwd]").val());
			      	var pwd2=$.trim($(form+" input[name=passwd2]").val());
			      	var nick_name=$.trim($(form+" input[name=nick_name]").val());
			      	var auth=$.trim($(form+" input[name=auth]").val());
			      	var authkey=$.trim($(form+" input[name=authkey]").val());
			      	var authnum=$.trim($(form+" input[name=authnum]").val());
			      	
			      	if(authkey=="" || auth!="Y"){
			      		Toast.show("이메일 인증 후 가입 할 수 있습니다.",{modal:true});
			      		button.prop("disabled",false);
			      		return false;
			      	}
			      	if(authnum==""){
			      		Toast.show("인증번호를 입력 하세요.",{modal:true});
			      		button.prop("disabled",false);
			      		return false;
			      	}
			      	
			      	
		        	var load_param={};
		        	load_param[window.csrf_name]=window.csrf_val;
		        	$.post("/account/loadsecure",load_param,function(res){
		        		var data=null;
			      		try{
			      			data=JSON.parse(res);
			      		}catch(e){
			      			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
			        		
			      			button.prop("disabled",false);
			        		return;
			      		}
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      								      	
					      	var param_temp={};					      	
					      	param_temp.email=email;
					      	param_temp.passwd=pwd;
					      	param_temp.passwd2=pwd2;
					      	param_temp.nick_name=nick_name;			
					      	      						   
					      	param_temp.authnum=authnum;
					      	param_temp.authkey=authkey;
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      
					      	$.post("/account/join",param,function(data){
				        		try{
					        		var json=JSON.parse(data);
					        		
					        		if(json.result=="Y"){
					        			if(callback){
					        				callback(json);
					        			}
					        		}else{
					        			Toast.show(json.msg,{modal:true});
					        			button.prop("disabled",false);
					        		}
				        		}catch(e){
				        			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
					        		
				        			button.prop("disabled",false);
				        		}
				        		
				        		
				        	}).fail(function(){
				        		Toast.show("오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		        	email:{
		            	required : true,
		            	email : true
		            },
		            passwd: {
		                required : true,
		                checkPasswd:true,		                
		            },
		            passwd2: {
		            	required:true,
		                equalTo:"#passwd"
		            },
		            nick_name:{
		            	required : true,
		            	checkNick: true
		            },
		            
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	
		            passwd: {
		            	//required: "비밀번호를 입력하세요."	,
		            	checkPasswd: "비밀번호는 띄어쓰기 이외의 6자이상 입니다."
		            },
		            passwd2:{
		            	//required: "비밀번호 확인을 입력하세요."	,
		            	equalTo:"비밀번호가 일치 하지 않습니다."
		            },
		            nick_name:{
		            	//required : "닉네임을 입력하세요.",
		            	checkNick: "닉네임은 한글,영문,숫자,.,_ 2~20자 입니다."
		            },
		            email:{
		            	//required : "이메일을 입력하세요.",
		            	email : "이메일 형식이 맞지 않습니다."
		            	
		            }
		          
		        }		        
		        , errorPlacement: function(error, element) {	
		        	
        			$(element).parent().next().html(error[0].innerHTML);
        			if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").removeClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","none");
        			}
        			$(element).attr("data-success","N");
        			
		        }, invalidHandler: function(form, validator) {
		        	var element=validator.errorList[0].element;
        			$(element).parent().next().html(validator.errorList[0].message);
        			if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").removeClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","none");
        			}
        			$(element).attr("data-success","N");
		        },success: function(label,element) {
		        	// set &nbsp; as text for IE		        	
		        	$(element).parent().next().html("");
		        	
		        	if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").addClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","inline");
        			}
		        	$(element).attr("data-success","Y");
				}
	
		    })
		});
		
	},
	
	resendMail: function(form){
		
		$(function(){			
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
								
		        submitHandler: function() {
		        	
		        	var button=$(form+" button[type=submit]");		        	
		        	if(!buttonLoading(button)){
			      		  return false;
			      	}
		        			        	
		        	
		        	$.post("/account/loadsecure",{ckCsrfToken:window.csrf_val},function(res){
			      		var data=JSON.parse(res);
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			  			
			      			
					      	var email_address=$.trim($(form+" input[name=email_address]").val());
					      						      	
					      	
					      	var param_temp={};					      	
					      	
					      	param_temp.email_address=email_address;
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      	
					      
					      	$.post("/account/resendmail",param,function(data){
				        		
				        		var json=JSON.parse(data);
				        		removeButtonLoading(button);
				        		if(json.result=="Y"){
				        			Toast.show(json.msg,{modal:true,callback:function(){location.href="/";}});
				        		}else{
				        			Toast.show(json.msg,{modal:true});
				        			removeButtonLoading(button);
				        		}
				        		
				        		
				        	}).fail(function(){
				        		Toast.show("오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		removeButtonLoading(button);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			removeButtonLoading(button);
			      		}
			      	}).fail(function(){
			      		removeButtonLoading(button);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		        	
		            email_address:{
		            	required : true,
		            	email : true
		            }
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {		        	
		            email_address:{
		            	required : "이메일을 입력하세요.",
		            	email : "이메일 형식이 맞지 않습니다."		            	
		            }		            
		          
		        }, errorPlacement: function(error, element) {
		        	error.appendTo( element.parent().next("p") );
		        }, invalidHandler: function(form, validator) {
		        	
		        },success: function(label,element) {
					
				}
	
		    })
		});
		
	}
	
	
};



var snsJoinProc={	
	snsJoinTempVaild: function(form,callback){
		
		$(function(){
			
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: ".ignore",
								
		        submitHandler: function() {
		        	
		        	var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        	
		        	var access_token=$.trim($(form+" input[name=access_token]").val());
	      			var user_type=$.trim($(form+" input[name=user_type]").val());
			      	var nick_name=$.trim($(form+" input[name=nick_name]").val());
			      	
			      	var agree_terms=$.trim($(form+" input[name=agree_terms]").val());	
			      	var agree_privacy=$.trim($(form+" input[name=agree_privacy]").val());	
			      	if(agree_terms!="Y"){
			      		Toast.show("이용약관에 동의 해야 합니다.",{modal:true});
			      		button.prop("disabled",false);
			      		return;
			      	}
			      	if(agree_privacy!="Y"){
			      		Toast.show("개인정보 수집 및 이용에 동의 해야 합니다.",{modal:true});
			      		button.prop("disabled",false);
			      		return;
			      	}
			      	
			      	if(nick_name==""){
			      		Toast.show("닉네임을 입력하세요.",{modal:true});
			      		button.prop("disabled",false);
			      		return;
			      	}
			      	
			      	if(access_token=="" || user_type==""){
			      		Toast.show("SNS 로그인 후 정보가 없습니다.",{modal:true});
			      		button.prop("disabled",false);
			      		return;
			      	}
			      	
		        	var load_param={};
		        	load_param[window.csrf_name]=window.csrf_val;
		        	$.post("/account/loadsecure",load_param,function(res){
		        		var data=null;
			      		try{
			      			data=JSON.parse(res);
			      		}catch(e){
			      			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
			        		
			      			button.prop("disabled",false);
			        		return;
			      		}
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			
					      	
					      	var param_temp={};
					      	
					      	param_temp.access_token=access_token;
					      	param_temp.user_type=user_type;
					      	param_temp.nick_name=nick_name;
					      	
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      
					      	$.post("/account/joinsns",param,function(data){
				        		try{
					        		var json=JSON.parse(data);
					        		button.prop("disabled",false);
					        		if(json.result=="Y"){		        			
					        			callback(json);
					        		}else{
					        			Toast.show(json.msg,{modal:true});
					        			
					        		}
				        		}catch(e){
				        			Toast.show("서버 오류. 관리자에게 문의하세요.",{modal:true});
					        		
				        			button.prop("disabled",false);
				        		}
				        		
				        		
				        	}).fail(function(){
				        		Toast.show("오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		        	
		            nick_name:{
		            	required : true,
		            	checkNick: true
		            }
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	
		            nick_name:{
		            	//required : "닉네임은 한글,영문,숫자 2~10자 입니다.",
		            	checkNick: "닉네임은 한글,영문,숫자,.,_ 2~20자 입니다."
		            }
		            
		          
		        }, errorPlacement: function(error, element) {	
		        	
        			$(element).parent().next().html(error[0].innerHTML);
        			if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").removeClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","none");
        			}
        			
		        }, invalidHandler: function(form, validator) {
		        	var element=validator.errorList[0].element;
        			$(element).parent().next().html(validator.errorList[0].message);
        			if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").removeClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","none");
        			}
		        },success: function(label,element) {
		        	// set &nbsp; as text for IE		        	
		        	$(element).parent().next().html("");
		        	
		        	if($(element).next(".icon-common").hasClass("check")){
        				$(element).next(".icon-common").addClass("on");
        			}else{
        				$(element).next(".icon-common").css("display","inline");
        			}
				}
	
		    })
		});
		
	}
	
	
	
};

var findProc={	
	
	password:function(form){
		$(function(){			
			$(form).validate({
				//validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
								
				submitHandler: function() {
					var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        	var email=$.trim($(form+" input[name=email]").val());
					var p={};
					p[window.csrf_name]=window.csrf_val;
					$.post("/account/loadsecure",p,function(res){
						var data=JSON.parse(res);
						if(data.result=="Y"){
							var sd=JSON.parse(data.msg);
														
							var param_temp={};					
							param_temp.email=email;
							
							var param={};
							param[window.csrf_name]=window.csrf_val;
							param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
						  
							$.post("/account/findpwd",param,function(data){
								try{
									var json=JSON.parse(data);
									button.prop("disabled",false);
									if(json.result=="Y"){
										Toast.show(json.msg,{modal:true,callback:function(){location.href='/';}});
									}else{
										Toast.show(json.msg,{modal:true});
									}
								}catch(E){
									Toast.show("이메일 전송 오류. 관리자에게 문의하세요.",{modal:true});									
									button.prop("disabled",false);
								}			
								
							}).fail(function(){
								Toast.show("이메일 전송 오류. 관리자에게 문의하세요.",{modal:true});
								
								button.prop("disabled",false);
							});        	
							
							
						}else{
							Toast.show(data.msg,{modal:true});
							button.prop("disabled",false);
						}
					}).fail(function(){
						button.prop("disabled",false);
						Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
					});
					
					return false;		            
				},
				//규칙
				rules: {
					email:{
						required : true
					}
					
				},
				//규칙체크 실패시 출력될 메시지
				messages : {					
					email:{
						required : "이메일 아이디를 입력하세요."
					}
					
				  
				}, errorPlacement: function(error, element) {	
		        	
        			$(element).parent().next().html(error[0].innerHTML);
        			
		        }, invalidHandler: function(form, validator) {
		        	var element=validator.errorList[0].element;
        			$(element).parent().next().html(validator.errorList[0].message);
        			
		        },success: function(label,element) {
		        	// set &nbsp; as text for IE		        	
		        	$(element).parent().next().html("");
		        	
				}

			})
		});
	}
};


var modProc={
	modpwd:function(form,callback){
		$(function(){
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
				//onkeyup:false,
		        submitHandler: function() {
		        	
		        	
		        	var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        	
		        	var s_param={};
			      	s_param[window.csrf_name]=window.csrf_val;
			      	$.post($(form).attr("secure"),s_param,function(res){
			      		var data=JSON.parse(res);
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      						      			
			      			
					      	var pwd=$.trim($(form+" input[name=passwd]").val());	
					      	var pwd_new=$.trim($(form+" input[name=new_passwd]").val());
					      	var pwd_new2=$.trim($(form+" input[name=new_passwd2]").val());
					      	
					      	
					      	var param_temp={};					      	
						      
					      	param_temp.passwd=pwd;					      	
					      	param_temp.new_passwd=pwd_new;
					      	param_temp.new_passwd2=pwd_new2;
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      	
					      	
					      
					      	$.post($(form).attr("action"),param,function(res){
				        		var data2=JSON.parse(res);
				        		if(data2.result=="Y"){
				        			Toast.show(data2.msg,{timeOut:1000,callback:function(){
				        				location.reload();
				        			}});
				        			
				        		}else{
				        			Toast.show(data2.msg,{timeOut:1000});       			
				        			
				        		}
				        		button.prop("disabled",false);
				        		
				        	}).fail(function(){
				        		Toast.show("오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		            passwd: {
		            	required : true
		            },
		           
		            new_passwd:{
		            	required : true,
		            	checkPasswd : true
		            },
		            new_passwd2:{
		            	equalTo:"#new_passwd"
		            }
		            
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	
		            passwd: {
		            	required: "비밀번호를 입력하세요."		            		
		            },		           
		            new_passwd: {
		            	required : "새 비밀번호를 입력하세요.",
		            	checkPasswd: "비밀번호 공백을 제외 한 는 6자리 이상의 문자 입니다."	            
		            },
		            new_passwd2:{
		            	equalTo : "비밀번호 확인이 일치 하지 않습니다."
		            	
		            }
		            
		        }, errorPlacement: function(error, element) {	
		        	
        			$(element).parent().next().html(error[0].innerHTML);
        			
        			
		        }, invalidHandler: function(form, validator) {
		        	var element=validator.errorList[0].element;
        			$(element).parent().next().html(validator.errorList[0].message);
        			
		        },success: function(label,element) {
		        	// set &nbsp; as text for IE		        	
		        	$(element).parent().next().html("");
		        	
				}
	
		    });
		});
	},
	
	
	modfindpwd:function(form,callback){
		$(function(){
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
				//onkeyup:false,
		        submitHandler: function() {
		        	
		        	
		        	var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        	
		        	var authval=$.trim($(form+" input[name=authval]").val());
			      	var pwd_new=$.trim($(form+" input[name=new_passwd]").val());
			      	var pwd_new2=$.trim($(form+" input[name=new_passwd2]").val());
		        	var s_param={};
			      	s_param[window.csrf_name]=window.csrf_val;
			      	$.post($(form).attr("secure"),s_param,function(res){
			      		var data=JSON.parse(res);
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			
					      	
					      	var param_temp={};					      	
						      
					      				      	
					      	param_temp.new_passwd=pwd_new;
					      	param_temp.new_passwd2=pwd_new2;
					      	param_temp.authval=authval;
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      	
					      	
					      
					      	$.post($(form).attr("action"),param,function(res){
					      		button.prop("disabled",false);
					      		try{
					        		var data2=JSON.parse(res);
					        		if(data2.result=="Y"){
					        			if(callback){
					        				Toast.show(data2.msg,{timeOut:1000,callback:function(){
					        					callback(data2);
						        			}});
					        			}else{
						        			Toast.show(data2.msg,{timeOut:1000,callback:function(){
						        				location.reload();
						        			}});
					        			}
					        		}else{
					        			Toast.show(data2.msg,{timeOut:1000});       			
					        			
					        		}
					      		}catch(E){}
				        		
				        		
				        	}).fail(function(){
				        		Toast.show("오류. 관리자에게 문의 하세요.",{modal:true});
				        		
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		            
		            new_passwd:{
		            	required : true,
		            	checkPasswd : true
		            },
		            new_passwd2:{
		            	equalTo:"#new_passwd"
		            }
		            
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	
		                      
		            new_passwd: {
		            	required : "새 비밀번호를 입력하세요.",
		            	checkPasswd: "비밀번호 공백을 제외 한 는 6자리 이상의 문자 입니다."	            
		            },
		            new_passwd2:{
		            	equalTo : "비밀번호 확인이 일치 하지 않습니다."
		            	
		            }
		            
		        }
		        , errorPlacement: function(error, element) {	
		        	
        			$(element).parent().next().html(error[0].innerHTML);
        			
		        }, invalidHandler: function(form, validator) {
		        	var element=validator.errorList[0].element;
        			$(element).parent().next().html(validator.errorList[0].message);
        			
		        },success: function(label,element) {
		        	// set &nbsp; as text for IE		        	
		        	$(element).parent().next().html("");
		        	
				}
	
		    });
		});
	},
	
	
	
	leave:function(form){
		$(function(){
			$(form).validate({
		        //validation이 끝난 이후의 submit 직전 추가 작업할 부분
				ignore: [],
				//onkeyup:false,
				
		        submitHandler: function() {
		        	var button=$(form+" button[type=submit]");		        	
		        	button.prop("disabled",true);
		        	
		        	var agreewithdraw=$(form+" input[name=agree_withdraw]").prop("checked");
			      	var passwd=$.trim($(form+" input[name=passwd]").val());
			      	if(!agreewithdraw){
			      		button.prop("disabled",false);
			      		Toast.show("회원탈퇴에 동의해야 합니다.",{modal:true});
			      		return;
			      	}
		        	var s_param={};
			      	s_param[window.csrf_name]=window.csrf_val;
			      	$.post($(form).attr("secure"),s_param,function(res){
			      		var data=JSON.parse(res);
			      		if(data.result=="Y"){
			      			var sd=JSON.parse(data.msg);
			      			
					      	var param_temp={};						      
					      	param_temp.passwd=passwd;					      
					      	
					      	var param={};
					      	param[window.csrf_name]=window.csrf_val;
					      	param.p=aes256(JSON.stringify(param_temp), sd.s_k, sd.s_i);
					      
					      	$.post("/account/leave",param,function(res){
					      		button.prop("disabled",false);
					      		try{
					        		var data2=JSON.parse(res);
					        		
					        		if(data2.result=="Y"){
					        			Toast.show("탈퇴 되었습니다.",{modal:true,callback:function(){
						        				if(parent){
						        					parent.location.href="/";
						        				}else{
						        					location.href="/";
						        				}
					        				
					        				}
					        			});
					        		}else{
					        			Toast.show(data2.msg,{modal:true,timeOut:1000});       			
					        			
					        		}
					        		
					      		}catch(E){}
				        	}).fail(function(){
				        		Toast.show("로그인 오류. 관리자에게 문의 하세요.",{modal:true});
				        		button.prop("disabled",false);
				        	});        	
					      	
					      	
			      		}else{
			      			Toast.show(data.msg,{modal:true});
			      			button.prop("disabled",false);
			      		}
			      	}).fail(function(){
			      		button.prop("disabled",false);
			      		Toast.show("서버오류. 관리자에게 문의하세요.",{modal:true});
			      	});
		        	
		        	
		            return false;		            
		        },
		        //규칙
		        rules: {		        	
		            passwd: {
		            	required : true
		            }		            
		            
		        },
		        //규칙체크 실패시 출력될 메시지
		        messages : {
		        	
		        	passwd: {
		            	required: "비밀번호를 입력하세요."
		            		
		            }
		            
		        }, errorPlacement: function(error, element) {		        	
		        	
		        }, invalidHandler: function(form, validator) {
		        	var errors = validator.numberOfInvalids();
	        		if (errors) {		            	 
		            	 var element=validator.errorList[0].element;		                 
		                 $(element).parent().next().html(validator.errorList[0].message);
		                 $(element).parent().addClass("alert");
		             }
		        },success: function(label,element) {									
		        	$(element).parent().next().html("");
	                $(element).parent().removeClass("alert");
					
				}
	
		    });
		});
	}
};




