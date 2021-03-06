<div class="col-md-10 wrap_content">
<div class="page-header">
	<h4><?=$title?> <small></small></h4>
</div>

<p class="lead h6">
	<span class="text-success"><strong><?=$mb_name?></strong></span>님의 회원가입을 진심으로 축하합니다.
	<br/><br/>회원님의 아이디는 <span class="text-success"><strong><?=$mb_id?></strong></span> 입니다.
	<br/>회원님의 비밀번호는 아무도 알 수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.
	<br/><br/>아이디, 비밀번호 분실 시에는
	<br/>회원가입시 입력하신 비밀번호 분실 시 질문, 답변을 이용하여 찾을 수 있습니다.

	<? if ($email_chk): ?>
	<br/><br/>이메일 ( <span class="text-info"><strong><?=$mb_email?></strong></span> ) 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다.
	<? endif; ?>

	<br/><br/>회원의 탈퇴는 언제든지 가능하며 탈퇴 후 일정 기간이 지난 후,
	<br/>회원님의 모든 소중한 정보는 삭제하고 있습니다.
	<br/><br/>감사합니다.
</p>

<hr />

<p class="text-center"><a href="<?=RT_PATH?>/" class="btn btn-lg btn-primary">홈으로</a></p>