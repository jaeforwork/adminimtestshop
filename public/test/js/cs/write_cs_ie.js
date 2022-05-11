/**
 * 
 */
$(function(){
	CKEDITOR.timestamp ="113";
	
	editor=CKEDITOR.replace( 'content',
		{
		timestamp :"113",	
		toolbar : [
			
			
			
			//{ name: 'insert', items: [ 'Image', 'Table', 'SpecialChar', 'Youtube','Flash'] },
			//{ name: 'insert', items: [ 'Youtube'] },
			//{ name: 'basicstyles',  items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
			//{ name: 'basicstyles',  items: [ 'Bold' ] },
			//{ name: 'paragraph',  items: [   'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
			//{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },			
			//{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
			//{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
			//{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
			//{ name: 'others', items: [ '-' ] },
			//{ name: 'document',  items: [ 'Source' ] },
			//{ name: 'clipboard',items: [  'Undo', 'Redo' ] }
		],
			removePlugins : 'editing,forms,print,language,a11yhelp,htmlwriter,forms,save,videodetector,smiley,image,pastetext,pastetext-rt',	
		
			removeButtons : 'Image,Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,Checkbox,NumberedList,BulletedList,Indent,Outdent,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,JustifyBlock,Anchor,HorizontalRule,PageBreak,Styles,Format,ShowBlocks,About',
			
			defaultLanguage : 'ko',
			extraPlugins : 'youtube,onchange,confighelper,image2,fakeobjects',
			enterMode : CKEDITOR.ENTER_P,
			shiftEnterMode : CKEDITOR.ENTER_BR,
			height:600,
			resize_minHeight:400,
			resize_maxHeight:3000,
			width:642,
			filebrowserImageUploadUrl: '/file/imageuploadcsie',
			allowedContent:true,
			minimumChangeMilliseconds : 1000,
			removeDialogTabs : 'image:advanced;link:advanced',
			extraAllowedContent : 'img(-jjang0u-content);iframe{max-width};embed[!src,!width,!height,!type]{max-width}',
			imageMaxWidth:600,
			
			dialogFieldsDefaultValues :
			{
			    image:
			        {
			            advanced:
			                {			                    
			                    txtGenTitle : '내용을 입력하세요.'
			                }
			        }
			},
			
			image2_disableResizer: true,
			forcePasteAsPlainText: true,
			basicEntities : false,
			htmlEncodeOutput : false,
			entities : false
		}
	);
	
		
	
	$("#fileimage").change(function(e){
		//showObj(e.target);
		var upfiles=e.target.files;
		if(upfiles == undefined || !upfiles.length){
			return;
		}
		
		var dataAccept=$(this).attr("data-accept");
		var p=new uploadProgress(upfiles, "/file/imageuploadcsie?mkey="+window.mkey+"&bid="+window.board+"&responseType=json&stamp="+($("#stamp").val()),function(){
			setTimeout(function(){$(progress).fadeOut(1000,function(){$(this).remove();});}, 2000);
			$("#fileimage").val("");
			//if(autoSaveTimeOut)autoSaveTimeOut=-60;
		});
		if(!p.isValidFile(window.max_file_size*1024,window.max_file_size+"KB", dataAccept.split(","))){
			return;
		}
		p.initCallback("image");
		p.startUpload();
	});
	

	
	$("#btn_images").click(function(){
		$("#fileimage").trigger("click");
	});
	
	
	
});

var uploadProgress=function(upfiles, _upload_url,callback_complete){
	var offset=$(".board-contents").offset();
	var width=$(".board-contents").width();
	var top=offset.top+61;
	var left=offset.left+(642-304)/2 +11;
	var uploadPercent=0;
	var complete=false;
	var uploadIndex=0;
	var uploadUrl=_upload_url;
	var progress=$("<div id='progress' style='position:absolute; z-index:8888; left:"+left+"px; top:"+top+"px; width:302px;line-height:27px;border:1px solid #1B7BAC; background:#399FD2;border-radius:3px; color:white; text-align:center;'>"
			+"<div id='progress_percent' style='position:relative; top:0; left:0;width:0%;height:25px;background:#1B7BAC'></div>"
			+"<div id='progress_text' style='position:absolute; top:0; left:0;width:302px;'>&nbsp;</div>"
			+"</div>");
	
	
	var _progress=function (p){					
		uploadPercent=(100*uploadIndex)+p;
		if(upfiles.length==1){
			if(p!=100){
				$("#progress_text").text("업로드 중입니다.("+p+"%)");
			}else{
				$("#progress_text").text("파일이 성공적으로 업로드되었습니다.");
				complete=true;
			}
			$("#progress_percent").css("width",p+"%");
		}else{
			var v=parseInt(uploadPercent/upfiles.length);
			
			if(v!=100){
				$("#progress_text").text(upfiles.length+"번째 파일 중 "+(uploadIndex+1)+"번째 파일 업로드 중입니다.("+v+"%)");
			}else{
				$("#progress_text").text("파일이 성공적으로 업로드되었습니다.");
				complete=true;
			}
			$("#progress_percent").css("width",v+"%");
			
		}
		if(complete){
			progress.css("border","1px solid #5FA367").css("background","#79B979");
			$("#progress_percent").css("width","0");
		}
	};
	
	var _image_callback=function(success,imagePath){
		if(!success){
			complete=true;
			_start();
			return;
		}
		
		
		
		
		editor.insertHtml("<img src=\""+imagePath+"\" class=\"-jjang0u-content\"><br>");
		imageResizeAll(editor);
		uploadIndex++;
		if(uploadIndex==upfiles.length){
			complete=true;						
		}
		_start();
		
	};
	var _mp3_callback=function(success, url){
		if(!success){
			complete=true;
			_start();
			return;
		}
					
		editor.insertHtml("<audio src=\""+url+"\" preload=\"metadata\" loop=\"loop\" controls=\"\" class=\"-jjang0u-content\" autoplay></audio><br>");
		
		uploadIndex++;
		if(uploadIndex==upfiles.length){
			complete=true;						
		}
		_start();
	};
	var _start=function(){
		if(complete){
			callback_complete();
			return;
		}
		uploadfile(
				uploadUrl,
				upfiles[uploadIndex],_progress,_callback);
	}
	
	
	var uploadfile=function(url, upfile,progress,callback){
		
		if(!upfile){
			Toast.show("업로드 할 파일이 없습니다.");
			return;
		}
		var xhr=false;
		var updata=new FormData();
	    updata.append( 'upload', upfile );
	    updata.append(window.csrf_name, window.csrf_val);
		$.ajax({
			xhr: function() {
				
				xhr = new window.XMLHttpRequest();

				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable){
						//$("#progress_bar").removeClass("hide1");
						var percentComplete = evt.loaded / evt.total;					
						percentComplete = parseInt(percentComplete * 100);
						progress(percentComplete);
						if(percentComplete === 100) {						
						}
					}
				}, false);
				return xhr;
			},
			url: url,
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
				if(json.uploaded==1){
					callback(true, json.url);
				}else{
					callback(false);					
					Toast.show(json.error.message,{modal:true});
					
				}
			},
			error: function(jqXHR,  textStatus,  errorThrown){			
				callback(false);
				Toast.show("전송이 실패 되었습니다",{modal:true});
				
				xhr=false;
			}
		});
	}



	var checkFile=function (upfile,limitSize, limitSizeStr, allowFile){
		var size=upfile.size;
		var fileName=upfile.name;
		if(size>limitSize){
			
			Toast.show(limitSizeStr+" 이하의 파일만 첨부가 가능합니다.",{timeOut:2000});
			return {result:"N"};
		}
		
		var li=fileName.lastIndexOf(".");
		var ext=fileName.substring(li+1).toLowerCase();
		if(allowFile.indexOf(ext) < 0 ){
			var str="";
			for(var i=0;i<allowFile.length;i++){
				str+=allowFile[i];
				if(i<allowFile.length-1){
					str+=" ,";
				}
			}			
			Toast.show(str+" 파일만 업로드가 가능합니다.",{timeOut:2000});
			return {result:"N"};
		}
		return {result:"Y",size:size,fileName:fileName.substring(0,li),ext:ext};
	}
	
	this.initCallback=function(type){
		if(type=="image"){
			_callback=_image_callback;
		}else if(type="etc"){
			_callback=_mp3_callback;
		}
	}
	this.isValidFile=function(size,sizeStr, ext){
		for(var i=0;i<upfiles.length;i++){
			var checkResult=checkFile(upfiles[i],size, sizeStr, ext);
			if(checkResult.result=="N"){
		    	return false;
		    }
		}
		return true;
	}
	this.startUpload=function(){
		$("body").append(progress);
		_start();
	}
}








CKEDITOR.on("instanceReady", function(event){
	
	
//	$("#cke_1_top").css("display","none");
//	$("#cke_1_bottom").css("display","none");
	editor.on("fileuploaddata",function(evt){ // drag and drop
		imageResizeAll(editor);
		
	});
	editor.on( 'fileUploadRequest', function( evt ) {		
		evt.data.fileLoader.xhr.open("POST",editor.config.filebrowserImageUploadUrl+"&responseType=json&stamp="+($("#stamp").val()),true);
	} );
	
	$("#cke_1_top").css("display","none");
	imageResizeAll(editor);
	
	
});
function imageResizeAll(editor){
	var parent = editor.document;
	if(parent && "find" in parent && typeof(parent.find) == "function") {
		var img = parent.find("img");
		var len = img.count();
		
		
		for(var index = 0; index < img.count(); index++) {
			
			
			var item = img.getItem(index);
			var css=item.$.className;
			
			if(css.indexOf("-jjang0u-content")>=0){
				imageResize(item.$);				
			}
		}
		
	}
}

function imageResize(img){
	if(img.naturalWidth){
		setSize(img);
	}else{
		//$(img).attr("onload","parent.setSize(this)");
		var wait = setInterval(function(){
			
			if(img.naturalWidth && img.naturalHeight){
				clearInterval(wait);
				setSize(img);
			}
	       
	    }, 5);
	}
}
function setSize(img){
	var w=0;
	var h=0;
	var $img=$(img);
	if($img.attr("width")){
		w=img.width;
		h=img.height;
	}
	if(img.naturalWidth){		
		//	w=img.naturalWidth;
		//	h=img.naturalHeight;
		$img.attr("org-width",img.naturalWidth).attr("org-height",img.naturalHeight);
	}
	
	if(w){
		$img.attr("width",w).attr("height",h);		
	}else{
		if(img.naturalWidth>600){
			
		}
	}
	$img.removeAttr("onload");
	$img.removeAttr("style");
	//$img.css("max-width","600px");
	$img.addClass="-jjang0u-content";
	
}

function imageResizeUpload(editor){
	var parent = editor.document;
	if(parent && "find" in parent && typeof(parent.find) == "function") {
		var img = parent.find("img");
		var len = img.count();
		
		
		for(var index = 0; index < img.count(); index++) {			
			var item = img.getItem(index);
			var css=item.$.className;
			
			if(css.indexOf("-jjang0u-content")<0){
				imageResize(item.$);				
			}
		}				
	}
}
CKEDITOR.on('dialogDefinition', function(ev) {
	// Take the dialog name and its definition from the event data
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;
	var editor = ev.editor;
	if (dialogName == 'image2'){
		dialogDefinition.onOk = function(e) {
			
			var img=e.sender.widget.element.$;
			if(img.width && img.width>0){				
				setSize(img);
			}else{
				$(".cke_notification_message").remove();
				$(".cke_notification_message").each(function(index){
					if(index==0){
						$(this).remove();
					}
				});
			}
			
       };
    }
});



function writeContent(frm, bid){
	editor.updateElement();
	
	var button = $("#" + frm + " button[type=submit]");
	if (!buttonLoading(button,true)) {
		return false;
	}
	
	var errorObj=null;
	
	$("#"+frm+" input, #"+frm+" select , #"+frm+" textarea").each(function(){
		if(!errorObj){
			var obj=$(this);
			var value=$.trim(obj.val());
			if(value=="" && obj.attr("data-required")=="true"){
				errorObj=obj;
			}
			
		}
	});
	
	if(errorObj){
		Toast.show(errorObj.attr("data-alert"), {
			modal : true
		});
		
		removeButtonLoading(button);
		
		return false;
	}
	
	var post_url=$("#"+frm).attr("action");
	
	$.post(post_url,$("#"+frm).serialize(),function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			Toast.show("등록 되었습니다.",{timeOut:1000, callback:function(){
				
				location.href=$("#"+frm+" input[name=back_url]").val().replace("NUM",data.msg);
				  
			}});
		}else{
			removeButtonLoading(button);
			Toast.show(data.msg,{modal:true});
		}
		  
		  
	}).fail(function(){
		removeButtonLoading(button);
		Toast.show("오류가 발생하였습니다.",{modal:true});
	});
	return false;
}


function modContent(frm, bid, cidx){
	editor.updateElement();
	var button = $("#" + frm + " button[type=submit]");
	if (!buttonLoading(button,true)) {
		return false;
	}
	
	var post_url=$("#"+frm).attr("action");
	$.post(post_url,$("#"+frm).serialize(),function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			Toast.show(data.msg,{timeOut:1000, callback:function(){
				
				
				location.href=$("#"+frm+" input[name=back_url]").val();
			}});
		}else{
			removeButtonLoading(button);
			Toast.show(data.msg,{modal:true});
		}
	}).fail(function(){
		removeButtonLoading(button);
		Toast.show("오류가 발생하였습니다.",{modal:true});
	});
	return false;
}