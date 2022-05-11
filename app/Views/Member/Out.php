<div class="container">
<div class="OutWrap">
<?=$LMenuName?>
<div class="col-md-9">
<div class="ContentsWrap">
<form id="fjoin" class="form-horizontal" name="fjoin" method="post" action="<?=RT_PATH?>/Member/Out/Delete">
<input type="hidden" name="token" value="<?=$token?>" />
<?=validation_errors('<pre>','</pre>')?>
<fieldset>
	<legend><?=$title?></legend>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_password"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호</label>
		<div class="col-md-9">
			<input type="password" id="mb_password" name="mb_password" class="form-control span3" maxlength="20" /> (8 ~ 20자,특수문자1자이상, 영어대문자 1자이상 포함)
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_password_re"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호 확인</label>
		<div class="col-md-9">
			<input type="password" id="mb_password_re" name="mb_password_re" class="form-control span3" maxlength="20" />
		</div>
	</div>	
	<div class="form-group">
		<label class="col-md-3 control-label" for="wr_key"><span class="glyphicon glyphicon-exclamation-sign"></span> 자동등록방지</label>
		<div class="col-md-9">
			<img src="<?=IMG_DIR?>/js/load_kcaptcha.gif" id="kcaptcha" class="img-rounded" width="100" height="50" alt="자동등록방지" />
			<input type="text" id="wr_key" name="wr_key" class="form-control span2" /> 이미지의 글자를 입력하세요.
			<span class="help-block">글자가 잘 안 보일 때 이미지를 클릭하면 새로운 글자가 나옵니다.</span>
		</div>
	</div>

	<hr />
	
	<p class="text-center">
		<button type="submit" class="btn btn-ms btn-success">탈퇴확인</button>
	</p>
</fieldset>

</form>
</div>
</div>
</div>
<script type='text/javascript' src='<?=JS_DIR?>/md5.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/kcaptcha.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_ext.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_reg.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/datepicker.js'></script>
<script type='text/javascript'>
//<![CDATA[

$(function() {
	var year = new Date().getFullYear();
	$('#mb_birth').datepicker({yearRange:(year-60)+':'+year});

	$('#fjoin').validate({
		onkeyup: false,
		rules: {
			mb_id: { required:true, reg_mb_id:true },
			mb_nick: { required:true, reg_mb_nick:true },
			mb_email: { required:true, reg_mb_email:true },
			mb_password: { required:true, minlength:2 },
			mb_password_re: { required:true, equalTo:'#mb_password'},
			mb_password_q: 'required',
			mb_password_a: 'required',
			mb_name: { required:true,  minlength:2, hangul:true },
			mb_birth: { minlength:10 },
			/*mb_profile: { required: function(element) {
					return 0 > (<?=$todays?> - parseInt($('#mb_birth').val()) - 140000);
				}
			},*/
			wr_key: { required:true, wrKey:true }
		},
		messages: {
			mb_id: '아이디 확인 결과가 올바르지 않습니다.',
			mb_nick: '별명 확인 결과가 올바르지 않습니다.',
			mb_email: '이메일 확인 결과가 올바르지 않습니다.',
			mb_password: { required:'비밀번호를 입력하세요.', minlength:'최소 2자 이상 입력하세요.' },
			mb_password_re: { required:'비밀번호 확인을 입력하세요.', equalTo:'비밀번호가 일치하지 않습니다.' },
			mb_password_q: '비밀번호 분실시 질문을 선택하거나 입력하세요.',
			mb_password_a: '비밀번호 분실시 답변을 입력하세요.',
			mb_name: { required:'이름을 입력하세요.', minlength:'최소 2자 이상 입력하세요.' },
			mb_birth: '올바른 형식이 아닙니다.',
			//mb_profile: "만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률 제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로 법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.",
			wr_key: '자동등록방지용 코드가 맞지 않습니다.'
		}
	});
});
//]]>
</script>