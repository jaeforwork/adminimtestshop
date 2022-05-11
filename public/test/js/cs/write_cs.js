function SimpleUploadAdapterPlugin(editor){
	editor.plugins.get( 'FileRepository' ).createUploadAdapter = function( loader ) { 
		// Configure the URL to the upload script in your back-end here! // 결국엔 내가 구현해 주어야 할 것은, // FileRepository가 어떤 업로드 어댑터를 사용하게 하느냐만 설정해주면 된다. // 나머지 이미지 업로드 플러그인, 파일 로더, FileRepository등등은 이미 만들어져 있다. 
		
		return new MyUploadAdapter( loader, "/file/cs",window.csrf_name,window.csrf_val ); 
	};

}



class MyUploadAdapter { 
	constructor( loader, upload_url, csrf_name, csrf_val ) { 
		this.loader = loader; 
		this.upload_url=upload_url;
		this.csrf_name=csrf_name;
		this.csrf_val=csrf_val;
	} 
	upload() { 
		return this.loader.file
		 .then( file => new Promise( ( resolve, reject ) => { 
			 if(file.size>3*1024*1024){return reject("이미지가 3MB를 초과 했습니다.");}
			 this._initRequest(); 
			 this._initListeners( resolve, reject, file ); 
			 this._sendRequest( file ); 
			 }) 
		 ); 
	} 
	abort() { 
		if ( this.xhr ) { this.xhr.abort(); } 
		}
	_initRequest() {
		const xhr = this.xhr = new XMLHttpRequest();		 
		xhr.open( 'POST', this.upload_url, true ); 
		xhr.responseType = 'json';	 
	}
    _initListeners( resolve, reject, file ) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = "파일을 찾을 수 없습니다. ";
        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
        xhr.addEventListener( 'abort', () => reject() );
        xhr.addEventListener( 'load', () => {
            const response = xhr.response;            
            // 이 예제에서는 XHR서버에서의 response 객체가 error와 함께 올 수 있다고 가정한다. 이 에러는
            // 메세지를 가지며 이 메세지는 업로드 프로미스의 매개변수로 넘어갈 수 있다.
           	
            if ( !response || response.error ) {
                return reject( response && response.error ? response.error.message : genericErrorText );
            }
            
            // 만약 업로드가 성공했다면, 업로드 프로미스를 적어도 default URL을 담은 객체와 함께 resolve하라. 
            // 이 URL은 서버에 업로드된 이미지를 가리키며, 컨텐츠에 이미지를 표시하기 위해 사용된다.
            resolve( {
                default: response.url
            } );
            
            window.editor.model.change( writer => {
            	writer.insertElement( 'paragraph', editor.model.document.selection.getLastPosition() );
            } );
        } );
        
        // 파일로더는 uploadTotal과 upload properties라는 속성 두개를 갖는다.
        // 이 두개의 속성으로 에디터에서 업로드 진행상황을 표시 할 수 있다.
        if ( xhr.upload ) {
            xhr.upload.addEventListener( 'progress', evt => {
                if ( evt.lengthComputable ) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            } );
        }
    }
  //데이터를 준비하고 서버에 전송한다.
    _sendRequest( file ) {
        // 폼 데이터 준비
        const data = new FormData();
        data.append( 'upload', file );
		data.append(this.csrf_name,this.csrf_val);
	// 여기가 인증이나 CSRF 방어와 같은 방어 로직을 작성하기 좋은 곳이다. 
        // 예를들어, XHR.setREquestHeader()를 사용해 요청 헤더에 CSRF 토큰을 넣을 수 있다.

        this.xhr.send( data );
    }
}


ClassicEditor
.create( document.querySelector( '#content' ), {	
	extraPlugins: [ SimpleUploadAdapterPlugin ],
	toolbar: {
		items: [
			
			'bold',			
			'link',			
			'fontColor',
			'fontSize',
			'|',
			
			'imageUpload',
		]
	},
	language: 'ko',
	image: {
		toolbar: [
			'imageTextAlternative',
			'imageStyle:full',
			'imageStyle:side'
		]
	},
	
	
} )
.then( 
	function(editor) {
		window.editor = editor;
		editor.editing.view.change(
			function(writer){
				if(window.mobile){
					writer.setStyle('min-height', '250px', editor.editing.view.document.getRoot());
					writer.setStyle('font-size', '14px', editor.editing.view.document.getRoot());
				}else{
					writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
				}
				writer.setStyle('max-height', '1000px', editor.editing.view.document.getRoot());        
			}
		);
		
	}
);





function writeContent(frm){
	window.editor.updateSourceElement();
	
	var button = $("#" + frm + " button[type=submit]");
	button.prop("disabled",true);
	
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
		
		button.prop("disabled",false);
		
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
			button.prop("disabled",false);
			Toast.show(data.msg,{modal:true});
		}
		  
		  
	}).fail(function(){
		button.prop("disabled",false);
		Toast.show("오류가 발생하였습니다.",{modal:true});
	});
	return false;
}



function modContent(frm){
	window.editor.updateSourceElement();
	var button = $("#" + frm + " button[type=submit]");
	button.prop("disabled",true);
	
	var post_url=$("#"+frm).attr("action");
	
	$.post(post_url,$("#"+frm).serialize(),function(res){
		var data=JSON.parse(res);
		if(data.result=="Y"){
			Toast.show(data.msg,{timeOut:1000, callback:function(){
				
				
				location.href=$("#"+frm+" input[name=back_url]").val();
			}});
		}else{
			button.prop("disabled",false);
			Toast.show(data.msg,{modal:true});
		}
	}).fail(function(){
		button.prop("disabled",false);
		Toast.show("오류가 발생하였습니다.",{modal:true});
	});
	return false;
}