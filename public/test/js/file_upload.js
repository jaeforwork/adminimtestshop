var FileUploader= function (options){	
	var button=options.button;
	var target=options.target;
	var uploadUrl=options.uploadUrl;	
	var progressCallback=options.progressCallback;
	var resultCallback=options.resultCallback;
	var uploadableChecker=options.uploadableChecker; // 추가 파일 등록 가능 여부 체크
	var csrf=options.csrf;
	var imageLimit=options.imageLimit ? options.imageLimit : {size:FileUploader.IMAGE_UPLOAD_LIMIT, msg:FileUploader.IMAGE_UPLOAD_LIMIT_STR};
	var videoLimit=options.videoLimit ? options.videoLimit : {size:FileUploader.MOV_UPLOAD_LIMIT, msg:FileUploader.MOV_UPLOAD_LIMIT_STR, time:FileUploader.MOV_UPLOAD_LIMIT_TIME};
	var audioLimit=options.audioLimit ? options.audioLimit : {size:FileUploader.MP3_UPLOAD_LIMIT, msg:FileUploader.MP3_UPLOAD_LIMIT_STR};
	var zipLimit=options.zipLimit ? options.zipLimit : {size:FileUploader.ZIP_UPLOAD_LIMIT, msg:FileUploader.ZIP_UPLOAD_LIMIT_STR};
	var extraPostData={};
	var targetElement;
	var targetButton;
	var progressing=false;
	this.init=function (){
		if(target){
			targetElement=document.querySelector(target);
			if(targetElement){
				targetElement.addEventListener("change",startUpload);
			}
		}
		
		if(button){
			targetButton=document.querySelector(button);
			if(targetButton){
				targetButton.addEventListener("click",function(){
					fireClick(targetElement);				
				});
			}
		}
		
		
	}
	this.setTarget=function(t){
		target=t;
		targetElement=document.querySelector(target);
		if(targetElement){
			targetElement.addEventListener("change",startUpload);
		}
	}
	this.setButton=function(b){
		button=b;
		targetButton=document.querySelector(button);
		if(targetButton){
			targetButton.addEventListener("click",function(){
				fireClick(targetElement);				
			});
		}
	}
	this.buttonClick=function(){
		if(targetButton){
			if (document.createEvent) {
		        var evt = document.createEvent('MouseEvents');
		        evt.initEvent('click', true, false);
		        targetButton.dispatchEvent(evt);    
		    } else if (document.createEventObject) {
		    	targetButton.fireEvent('onclick') ; 
		    } else if (typeof node.onclick == 'function') {
		    	targetButton.onclick(); 
		    }
		}
	}
	this.setExtra=function(ext){
		extraPostData=ext;
	}

	var fireClick=function (node){
	    if (document.createEvent) {
	        var evt = document.createEvent('MouseEvents');
	        evt.initEvent('click', true, false);
	        node.dispatchEvent(evt);    
	    } else if (document.createEventObject) {
	        node.fireEvent('onclick') ; 
	    } else if (typeof node.onclick == 'function') {
	        node.onclick(); 
	    }
	}
	
	

	var startUpload=function(event){
		if(progressing)return;
		progressing=true;
		
		
		var upfiles=event.target.files;
		
		if(upfiles == undefined || !upfiles.length){
			if(resultCallback){
				resultCallback({result:FileUploader.RESULT_TYPE_END, upload:{result:"N", msg:"선택 된 파일이 없습니다."}});
			}
			targetElement.value="";
			progressing=false;
			return;
		}

		var file=upfiles[0];
		
		var check=checkFile(file);
		if(check.result!="Y"){
			resultCallback({result:FileUploader.RESULT_TYPE_END, upload:{result:"N", msg:check.msg} });
			progressing=false;
			targetElement.value="";
			return;
		}
		
		if(uploadableChecker && !uploadableChecker(check)){
			progressing=false;
			targetElement.value="";
			return;
		}
		
		if(check.type=="mov" || check.type=="audio"){
			var checkVideo=0;
			var video = document.createElement('video');	
			video.addEventListener("loadedmetadata",function(){				
				checkVideo=1;
			});
			
			video.addEventListener("error",function(){
				checkVideo=2;
			});
			
			var URL = window.URL || window.webkitURL; 
			var fileURL = URL.createObjectURL(file);
			
			video.src=fileURL;
			
			var wait = setInterval(function() {		        
		        if (checkVideo>0) {
		            clearInterval(wait);	
		            
		            if(checkVideo==1){
		            	if(video.duration>videoLimit.time){
		            		resultCallback({result:FileUploader.RESULT_TYPE_END, upload:{result:"N", msg:videoLimit.time+"초 이하의 동영상을 선택하세요."}});
		            		progressing=false;
		            	}else{
		            		doUpload(file,check);
		            	}
		            }else{
		            	resultCallback({result:FileUploader.RESULT_TYPE_END, upload:{result:"N", msg:"동영상을 인식 할 수 없습니다."}});
		            	progressing=false;
		            }
		            
		        }
		        
		    }, 30);
			
			
		}else{
			doUpload(file,check);
		}
	}
	var doUpload=function(file,check){
		
		
		
		var filePath="";
		var fileReader=new FileReader();
		fileReader.readAsDataURL(file);
		
		fileReader.onloadend=function(evt){
			filePath=evt.target.result;
			resultCallback({result:FileUploader.RESULT_TYPE_START,path:filePath});
		}
				
		
		var xhr=false;
		var updata=new FormData();
	    updata.append( 'file', file );
	    updata.append("type",check.type);
	    updata.append("max_size",check.maxsize);
	    
	    if(extraPostData){
	    	for(key in extraPostData){
	    		updata.append(key,extraPostData[key]);
	    	}
	    }
	    if(csrf){
	    	updata.append(csrf.name, csrf.val);
	    }
		$.ajax({
			xhr: function() {
				
				xhr = new window.XMLHttpRequest();

				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable){
						//$("#progress_bar").removeClass("hide1");
						var percentComplete = evt.loaded / evt.total;					
						percentComplete = parseInt(percentComplete * 100);
						if(progressCallback){
							progressCallback(percentComplete);
						}
						
					}
				}, false);
				return xhr;
			},
			url: uploadUrl,
			xhrFields: {
			    withCredentials: true
			},
			type: "POST",
			data: updata,
			processData: false,  // file전송시 필수
			contentType: false,  // file전송시 필수
			crossDomain : true,		
			success: function(result) {
				
				xhr=false;
				var json=JSON.parse(result);
				var returnData={result:FileUploader.RESULT_TYPE_END,upload:json};
				
				if(resultCallback){	
					
					resultCallback(returnData);
				}
				progressing=false;
				targetElement.value="";
			},
			error: function(jqXHR,  textStatus,  errorThrown){			
				
				if(resultCallback){
					resultCallback({result:FileUploader.RESULT_TYPE_END,upload:{result:"N",msg:"전송이 실패 되었습니다."}});
				}
				xhr=false;
				progressing=false;
				targetElement.value="";
			}
		});
		
		
	}

	var checkFile=function(file){
		var size=file.size;
		var fileName=file.name;
		var li=fileName.lastIndexOf(".");
		var ext=fileName.substring(li+1).toLowerCase();
		
		var type=null;
		if(ext=="jpeg" || ext=="jpg" || ext=="png" || ext=="gif"){
			type="image";
		}else if(file.type=="image/jpeg" || file.type=="image/gif" || file.type=="image/png"){
			type="image";
		}else if(file.type.indexOf("video")!=-1){//(ext=="mp4" || ext=="avi" || ext=="mkv"){
			type="mov";
		}else if(ext=="audio/mpeg"){	
			type="audio";
		}else if(ext=="zip"){
			type="zip";
		}else if(ext=="mp3"){
			type="audio";
		}
		
		if(type==null){
			
			return {result:"N",msg:"지원하지 않는 포맷입니다."};
		}

		var max_size=0;
		if(type=="image"){
			if(size>imageLimit.size){
				return {result:"N",msg:imageLimit.msg+" 이하의 이미지를 등록하세요."};
			}
			max_size=imageLimit.size;
		}else if(type=="mov"){
			if(size>videoLimit.size){
				return {result:"N",msg:videoLimit.msg+" 이하의 동영상을 등록하세요."};
			}
			max_size=videoLimit.size;
		}else if(type=="audio"){
			if(size>FileUploader.MP3_UPLOAD_LIMIT){
				return {result:"N",msg:FileUploader.MP3_UPLOAD_LIMIT_STR+" 이하의 오디오를 등록하세요."};
			}
			max_size=FileUploader.MP3_UPLOAD_LIMIT;
		}else if(type=="zip"){
			if(size>FileUploader.ZIP_UPLOAD_LIMIT){
				return {result:"N",msg:FileUploader.ZIP_UPLOAD_LIMIT_STR+" 이하의 ZIP파일을 등록하세요."};
			}
			max_size=FileUploader.ZIP_UPLOAD_LIMIT;
		}
		
		
		return {result:"Y",type:type,maxsize:max_size};
	}
}
FileUploader.RESULT_TYPE_START="start";
FileUploader.RESULT_TYPE_END="end";
FileUploader.IMAGE_UPLOAD_LIMIT=2*1024*1024;
FileUploader.IMAGE_UPLOAD_LIMIT_STR="2MB";
FileUploader.MOV_UPLOAD_LIMIT=10*1024*1024;
FileUploader.MOV_UPLOAD_LIMIT_STR="10MB";
FileUploader.MOV_UPLOAD_LIMIT_TIME=60;//초
FileUploader.MP3_UPLOAD_LIMIT=10*1024*1024;
FileUploader.MP3_UPLOAD_LIMIT_STR="10MB";
FileUploader.ZIP_UPLOAD_LIMIT=250*1024*1024;
FileUploader.ZIP_UPLOAD_LIMIT_STR="250MB";
