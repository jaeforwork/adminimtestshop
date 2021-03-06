<div class="col-md-10 wrap_content">
<form id="fpwd_forget2" class="form-horizontal" name="fpwd_forget2" method="post" action="<?=RT_PATH?>/Member/ForgetIdpwd/Step3">
<input type="hidden" name="mb_id" value="<?=$mb_id?>" />

<fieldset>
	<legend>STEP 02) <?=$title?></legend>
	<div class="form-group">
		<label class="col-md-3 control-label">회원아이디</label>
		<div class="col-md-9">
			<p class="form-control-static text-primary"><strong><?=$mb_id?></strong></p>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label">비밀번호 분실시 질문</label>
		<div class="col-md-9">
			<p class="form-control-static text-info"><strong><?=$mb_password_q?></strong></p>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_password_a">비밀번호 분실시 답변</label>
		<div class="col-md-4">
			<input type="text" id="mb_password_a" name="mb_password_a" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label"></label>
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-2">
					<img src="<?=IMG_DIR?>/js/load_kcaptcha.gif" id="kcaptcha" class="img-rounded" width="100" height="50" alt="자동등록방지" />
				</div>
				<div class="col-md-10">
					<input type="text" name="wr_key" class="form-control span2" /> 이미지의 글자를 입력하세요.
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					글자가 잘 안 보일 때 이미지를 클릭하면 새로운 글자가 나옵니다.
				</div>
			</div>
		</div>
	</div>
	
	<hr />

	<p class="text-center">
		<button type="submit" class="btn btn-lg btn-success">다음</button>
	</p>
</fieldset>

</form>


<script type="text/javascript" src="<?=JS_DIR?>/md5.js"></script>
<script type="text/javascript" src="<?=JS_DIR?>/kcaptcha.js"></script>
<script type="text/javascript" src="<?=JS_DIR?>/jquery/validate.js"></script>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fpwd_forget2').validate({
		rules: {
			mb_password_a: "required",
			wr_key: { required:true, wrKey:true }
		},
		messages: {
			mb_password_a: "비밀번호 분실시 답변을 입력하세요.",
			wr_key: "자동등록방지용 코드가 맞지 않습니다."
		}
	});
});
//]]>
</script>