var FileDownloader= function (options){	
	var button=options.button;  //다운로드 
	
	var downloadUrl=options.downloadUrl;	
	var progressCallback=options.progressCallback;
	var resultCallback=options.resultCallback;
	
	var progressing=false;
	this.init=function (){
		
		
		if(button){
			targetButton=document.querySelector(button);
			targetButton.addEventListener("click",function(){
				startDownload();				
			});
		}
		
		
	}
	
	
	var startDownload=function(){
		
		
		var xhr=false;
		
		$.ajax({
			xhr: function() {
				
				xhr = new window.XMLHttpRequest();

				xhr.addEventListener("progress", function(evt) {
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
			url: downloadUrl,
			xhrFields: {
			    withCredentials: true,
			    responseType: "blob"
			},
			type: "get",
			crossDomain : true,		
			success: function(data) {
				
				xhr=false;
				if(resultCallback){	
					
					resultCallback(true);
				}
				progressing=false;
				//var blob = new Blob([data]);
				//파일저장
				if (navigator.msSaveBlob) {
					console.log("msSaveBlob");
					return navigator.msSaveBlob(data, downloadUrl);
				}
				else {
					
					var link = document.createElement('a');
					link.href = window.URL.createObjectURL(data);
					link.download = downloadUrl;
					link.click();
					window.URL.revokeObjectURL(link.href);
				}
			},
			error: function(jqXHR,  textStatus,  errorThrown){			
				
				if(resultCallback){
					resultCallback(false);
				}
				xhr=false;
				progressing=false;
			}
		});
		
		
	}

	
}
