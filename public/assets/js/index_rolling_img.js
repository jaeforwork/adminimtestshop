var $mainMenu;
	var $subMenu;
	var $overNum; 
	var $currentNum;  
	
	var imgHeight=300;
	var currentIndex=0; 
	var $imgWrap; 
	var $imgNum; 
	
	$(document).ready(function(e) {
        init();
		setPosition();
		start();
    });
	   
	function init(){
		//메인 이미지
		$imgWrap=$("#slide ul.items li");
		$imgNum=$imgWrap.size(); 
	}
	
	
	function setPosition(){
		//메인 이미지
		$imgWrap.css({top:imgHeight,opacity:0});
		$imgWrap.eq(0).css({top:0,opacity:1}); 			
	}
	
	function start(){
		setInterval(onCount,2500);
	}
	
	function onCount(){
		if(currentIndex+1>=$imgNum){
			onSlide(0); 
		} else {
			onSlide(currentIndex+1);	
		}
	}
	
	function onSlide(newIndex){
		var $currentImg=$imgWrap.eq(currentIndex);
		var $nextImg=$imgWrap.eq(newIndex);
		$currentImg.animate({top:-imgHeight, opacity:0},2000,"easeOutCubic");
		$nextImg.css({top:imgHeight, opacity:0});
		$nextImg.animate({top:0, opacity:1},1000,"easeOutCubic");
		currentIndex=newIndex;
	}