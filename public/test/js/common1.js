

/*top btn*/
$( document ).ready( function() {
    $( '.btn-top' ).click( function() {
        $( 'html, body' ).animate( { scrollTop : 0 }, 200 );
        return false;
    } );
} );



$(document).on('click', 'a[href="#"]', function(e){
	e.preventDefault();
});


$(function(){
	$('.global__heart-btn').on("click", function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$(this).addClass('animate');
		}else{
			$(this).removeClass('animate');
		}
	});
});


$(function(){
	$('.my-info .util-btn').on("click", function(){
		$(this).toggleClass('active');
		$('.util-popup-wrap--user').toggle();
		//event.stopPropagation();
	});
	$('html').on("click", function(e){
		if(!$(e.target).hasClass('util-popup-wrap--user')){
			$('.my-info .util-btn').removeClass('active');
			$('.util-popup-wrap--user').hide();
		}
		//event.stopPropagation();
	});
});

$(function(){
	$('.timeline__item-top .util-btn').on("click", function(){
		$(this).toggleClass('active');
		$(this).parent().parent().find('.util-popup-wrap--timeline').toggleClass('active');
		$('.timeline__item-top .util-btn').not($(this)).parent().parent().find('.util-popup-wrap--timeline').removeClass('active');
		$('.timeline__item-top .util-btn').not($(this)).removeClass('active');
		event.stopPropagation();
	});
	$('html').on("click", function(e){
		if(!$(e.target).hasClass('.util-popup-wrap--timeline')){
			$('.timeline__item-top .util-btn').removeClass('active');
			$('.util-popup-wrap--timeline').removeClass('active');
		}
		event.stopPropagation();
	});
});


$(function(){
	$('.global__share-btn').on("click", function(){
		$(this).parent().children('.util-popup-wrap--share').toggleClass('active');
		$('.global__share-btn').not($(this)).parent().find('.util-popup-wrap--share').removeClass('active');
		event.stopPropagation();
	});
	$('html').on("click", function(e){
		if(!$(e.target).hasClass('.util-popup-wrap--share')){
			$('.util-popup-wrap--share').removeClass('active');
		}
		event.stopPropagation();
	});
});

$(function(){
	$('.chat__btn-wrap .util-btn').on("click", function(){
		$(this).toggleClass('active');
		$(this).parent().parent().find('.util-popup-wrap--chat-list').toggleClass('active');
		$('.chat__btn-wrap .util-btn').not($(this)).parent().parent().find('.util-popup-wrap--chat-list').removeClass('active');
		$('.chat__btn-wrap .util-btn').not($(this)).removeClass('active');
		event.stopPropagation();
	});
	$('html').on("click", function(e){
		if(!$(e.target).hasClass('.util-popup-wrap--chat-list')){
			$('.chat__btn-wrap .util-btn').removeClass('active');
			$('.util-popup-wrap--chat-list').removeClass('active');
		}
		event.stopPropagation();
	});
});

$(function(){
	$('.celeb-list__item--fan .util-btn').on("click", function(){
		$(this).toggleClass('active');
		$(this).parent().find('.util-popup-wrap--fan').toggleClass('active');
		$('.celeb-list__item--fan .util-btn').not($(this)).parent().find('.util-popup-wrap--fan').removeClass('active');
		$('.celeb-list__item--fan .util-btn').not($(this)).removeClass('active');
		event.stopPropagation();
	});
	$('html').on("click", function(e){
		if(!$(e.target).hasClass('.util-popup-wrap--fan')){
			$('.celeb-list__item--fan .util-btn').removeClass('active');
			$('.util-popup-wrap--fan').removeClass('active');
		}
		event.stopPropagation();
	});
});

$(function(){
	$('.subscribe-btn').on("click", function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).text("구독")
			$(this).parent().children('.celeb-inner__btn').removeClass('active');
			var result = confirm("구독을 취소하시겠습니까?");
			if(!result){
				$(this).addClass('active');
				$(this).text("구독중")
				$(this).parent().children('.celeb-inner__btn').addClass('active');
			}else{

			}
		}else{
			$(this).addClass('active');
			$(this).text("구독중")
			$(this).parent().children('.celeb-inner__btn').addClass('active');
		}
	});
});


/*
$(function(){
	$('.subscribe-btn').on("click", function(){
		$(this).toggleClass('active');
		$(this).text(function(i, v){
			return v === '구독' ? '구독중' : '구독'
		});
	});
	$('.subscribe-btn').on("click", function(){
		if($(this).hasClass('active')){
			$(this).parent().children('.celeb-inner__btn').addClass('active');
		}else{
			$(this).parent().children('.celeb-inner__btn').removeClass('active');
		}
	});
});
*/


$(function(){
	$('.chatroom__header__coin').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.product-pop').show();
	});
	$('.product-pop .product__item > a').on("click", function(){
		$('.product-pop').hide();
		$('.global-pop--coin').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.product-pop,.global-pop--coin').hide();
	});
});









$(function(){
	$('.chatroom__header__gift').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.gift-pop').show();
	});
	$('.product__item--gift').on("click", function(){
		$('.npop--gifticon').show();
	});
	$('.npop__close,.gifticon__btn-wrap > button').on("click", function(){
		$('.npop--gifticon').hide();
	});
	$('.npop--gifticon .btn-confirm').on("click", function(){
		$('.gift-pop').hide();
		$('.global-pop--gift').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.gift-pop').hide();
	});
});


$(function(){
	$('.btn-myitem').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--myitem').show();
	});
	$('.myitem__name--usable').on("click", function(){
		$('.npop--item--useble').show();
	});
	$('.myitem__name--hold').on("click", function(){
		$('.npop--item--hold').show();
	});
	$('.npop__close,.gifticon__btn-wrap > button').on("click", function(){
		$('.npop--item--hold,.npop--item--usable').hide();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--myitem').hide();
	});
});



$(function(){
	$('.product__item--coin').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--coin').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--coin').hide();
	});
});
$(function(){
	$('.modify-btn').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--setting').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--setting').hide();
	});
});
$(function(){
	$('.btn-detail-list').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--detail').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--detail').hide();
	});
});

$(function(){
	$('.btn-exchange').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--exchanged').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--detail,.global-pop--exchanged').hide();
	});
});

$(function(){
	$('.btn-modify-pw').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--pw').show();
	});
	$('.global-pop--pw .btn-input-confirm').on("click", function(){
		$('.global-pop--pw').hide();
		$('.global-pop--modify-pw').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--pw').hide();
	});
});

$(function(){
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--modify-pw').hide();
	});
});

$(function(){
	$('.btn-withdraw').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--withdraw').show();
	});
	$('.global-pop--withdraw .btn-agreement').on("click", function(){
		$('.global-pop--withdraw').hide();
		$('.global-pop--withdraw-confirm').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--withdraw').hide();
	});
});

$(function(){
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--withdraw-confirm').hide();
	});
});

$(function(){
	$('.btn-terms--terms').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--terms').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--terms').hide();
	});
});

$(function(){
	$('.btn-terms--privacy').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--privacy').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--privacy').hide();
	});
});

$(function(){
	$('.btn-report').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--report').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--report').hide();
	});
});

$(function(){
	$('.btn-mysignal').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--signal').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--signal').hide();
	});
});

$(function(){
	$('.timeline__message__more').on("click", function(){
		$('.timeline__message > p').toggleClass('active');
		//$(this).text("닫기");
		var text = $(this).text();
		$(this).text(text == "닫기" ? "더보기" : "닫기");
	});
});





function numberWithCommas(x) {
  x = x.replace(/[^0-9]/g,'');   // 입력값이 숫자가 아니면 공백
  x = x.replace(/,/g,'');          // ,값 공백처리
  $("#coin").val(x.replace(/\B(?=(\d{3})+(?!\d))/g, ",")); // 정규식을 이용해서 3자리 마다 , 추가 
}
function numberWithCommas2(x) {
  x = x.replace(/[^0-9]/g,'');   // 입력값이 숫자가 아니면 공백
  x = x.replace(/,/g,'');          // ,값 공백처리
  $("#sponsor").val(x.replace(/\B(?=(\d{3})+(?!\d))/g, ",")); // 정규식을 이용해서 3자리 마다 , 추가 
}
function numberWithCommas3(x) {
  x = x.replace(/[^0-9]/g,'');   // 입력값이 숫자가 아니면 공백
  x = x.replace(/,/g,'');          // ,값 공백처리
  $("#coin").val(x.replace(/\B(?=(\d{3})+(?!\d))/g, ",")); // 정규식을 이용해서 3자리 마다 , 추가 
}

$(function(){
	$('#coin').on("input", function(){
		if($(this).val() == ""){
			$('.gift-btn').removeClass('active');
		}else{
			$('.gift-btn').addClass('active');
		}
	});
});

$(function(){
	$('#sponsor').on("input", function(){
		if($(this).val() == ""){
			$('.gift-btn').removeClass('active');
		}else{
			$('.gift-btn').addClass('active');
		}
	});
});

$(function(){
	$('ul.tabs li').on("click", function(){
		var tab_id = $(this).attr('data-tab');
		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');
		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});
});



$(function(){
	$('.chat__gift').on("click", function(){
		$('.npop--gift').show();
	});
	$('.npop--gift .npop__close').on("click", function(){
		$('.npop--gift').hide();
	});
});


function giftRefuse(){
	var giftRefuse = confirm("정말 선물을 거부하시겠습니까?");
	if(giftRefuse == true){
		alert("선물을 거부하였습니다.");
		$('.npop--gift').hide();
	}else if(giftRefuse == false){
		//alert("거부를 철회하였습니다.");
	}
}
function getGift(){
	var getGift = alert("선물을 받았습니다.\n받은 선물은 마이페이지 > 나의 아이템에서 확인하실 수 있습니다.");
	$('.npop--gift').hide();
}

function holdGift(){
	var holdGift = alert("선물받기를 보류하였습니다.\n받은 보류된 선물은 마이페이지 > 나의 아이템에서 확인하실 수 있습니다.\n90일 후 토큰몬드(10원단위 절삭)로 충전됩니다.");
	$('.npop--gift').hide();
}

function useGift(){
	var useGift = alert("기프티콘을 SMS메시지로 발송하였습니다.");
	$('.npop--item').hide();
}
$(function(){
	$('.payment li').on("click", function(){
		$(this).addClass('active');
		$('.payment li').not($(this)).removeClass('active');
	});
});
$(function(){
	$('#exchanged1 .btn-agreement').on("click", function(){
		$('#exchanged2').show();
		$('#exchanged1').hide();
	});
});

$(function(){
	$('#agree-pay').on("click", function(){
		if($('#agree-pay').is(":checked") == true){
			$('#payment').addClass('active');
		}else{
			$('#payment').removeClass('active');
		}
	});
});

$(function(){
	$('#agree-pay2').on("click", function(){
		if($('#agree-pay2').is(":checked") == true){
			$('#payment2').addClass('active');
		}else{
			$('#payment2').removeClass('active');
		}
	});
});

$(function(){
	$('#agree-pay3').on("click", function(){
		if($('#agree-pay3').is(":checked") == true){
			$('#payment3').addClass('active');
		}else{
			$('#payment3').removeClass('active');
		}
	});
});

$(function(){
	$('#agree-withdraw').on("click", function(){
		if($('#agree-withdraw').is(":checked") == true){
			$('.global-pop--withdraw .btn-agreement').addClass('active');
		}else{
			$('.global-pop--withdraw .btn-agreement').removeClass('active');
		}
	});
});


$(function(){
	$('.celeb-list__item').on("click", function(){
		//console.log($(this).find('input:checkbox').prop("checked"));
		if($(this).find('input:checkbox').prop("checked")==false){
			$(this).find('input:checkbox').prop("checked", true);
			$('.btn-inner-confirm').addClass('active');
			//event.preventDefault();
		}else{
			$(this).find('input:checkbox').prop("checked", false);
			$('.btn-inner-confirm').removeClass('active');
			//event.preventDefault();
		}
		if($('.celeb-list__item').find('input:checkbox').is(":checked")==true){
			$('.btn-inner-confirm').addClass('active');
		}else{
			$('.btn-inner-confirm').removeClass('active');
		}
	});
});

$(function(){
	$('.product__detail .btn-agreement').on("click", function(){
		$('html,body').css("overflow","hidden");
		$('.global-pop--gift').show();
	});
	$('.pop__close,.overlay').on("click", function(){
		$('html,body').css("overflow","auto");
		$('.global-pop--gift').hide();
	});
});

$(function(){
	$('.btn-inner-confirm').on("click", function(){
		if($(this).hasClass('active')){
			$('.global-pop__box--select').css("display","none");
			$('.global-pop__box--product').css("display","block");
		}else{
			$('.global-pop__box--select').css("display","block");
			$('.global-pop__box--product').css("display","none");
		}
	});
});

$(function(){
	$(window).scroll(function(){
		var num = $(this).scrollTop();
		if(num > 340){
			$('.aside-wrap--mypage').addClass('aside-fixed');
		}else {
			$('.aside-wrap--mypage').removeClass('aside-fixed');
		}
	});
});

$(function(){
	$('.img-profile__normal').on("click", function(){
		$(this).addClass('active');
		if($('.img-profile__normal').hasClass('active')){
			$('.img-profile__normal').not($(this)).removeClass('active');
		}
	});
});


$(function(){
	$('.writepost__title-box').on("input change paste", function(){
		var btnConfirm = $('.writepost__write-btn .btn-inner-confirm');
		if($(this).val() != ""){
			btnConfirm.addClass('active');
		}else{
			btnConfirm.removeClass('active');
		}
	});
});


$(function(){
	$('.input-check input').on("focus", function(){
		$(this).siblings('.icon-common').show();
	});
	//$('.input-check input').on("focusout", function(){
		//$(this).siblings('.icon-common').hide();
	//});
});


