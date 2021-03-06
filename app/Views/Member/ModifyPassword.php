<div class="MemberModifyPasswordWrap">
	<?=$LMenuName?>
	<div class="ContentsWrap">
		<form id="fpwd_change" class="form-horizontal" name="fpwd_change" method="post" action="<?=RT_PATH?>/Member/Modify/Password">
			<input type="hidden" name="mb_id" value="<?=$mb_id?>" />

			<?=validation_errors('<pre>','</pre>')?>
			<fieldset>
				<legend><?=$title?></legend>
				<div class="form-group">
					<label class="col-md-3 control-label" for="old_password">현재 비밀번호</label>
					<div class="col-md-3">
						<input type="password" id="old_password" name="old_password" class="form-control" maxlength="20" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="new_password">새 비밀번호</label>
					<div class="col-md-3">
						<input type="password" id="new_password" name="new_password" class="form-control" maxlength="20" />
					</div>
					<div class="col-md-6">8 ~ 20자</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="new_password_re">새 비밀번호 확인</label>
					<div class="col-md-3">
						<input type="password" id="new_password_re" name="new_password_re" class="form-control" maxlength="20" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">자동등록방지</label>
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
					<button type="submit" class="btn btn-success">확인</button>
				</p>
			</fieldset>

		</form>

	</div>
	<script type='text/javascript' src='<?=JS_DIR?>/md5.js'></script>
	<script type='text/javascript' src='<?=JS_DIR?>/kcaptcha.js'></script>
	<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
	<script type='text/javascript'>
//<![CDATA[
$(function() {
	$('#fpwd_change').validate({
		rules: {
			old_password: { required:true, minlength:3 },
			new_password: { required:true, minlength:3 },
			new_password_re: { required:true, equalTo:'#new_password' },
			wr_key: { required:true, wrKey:true }
		},
		messages: {
			old_password: { required:'현재 비밀번호를 입력하세요.', minlength:'최소 3자 이상 입력하세요.' },
			new_password: { required:'새 비밀번호를 입력하세요.', minlength:'최소 3자 이상 입력하세요.' },
			new_password_re: { required:'새 비밀번호 확인을 입력하세요.', equalTo:'비밀번호가 일치하지 않습니다.' },
			wr_key: '자동등록방지용 코드가 맞지 않습니다.'
		}
	});
});
//]]>
</script>