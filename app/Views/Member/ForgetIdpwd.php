<div class="ForgetIdpwdWrap">
<?=$LMenuName?>
<div class="ContentsWrap">
<form id="fidpwd_forget" class="form-horizontal" name="fidpwd_forget" method="post" action="<?=RT_PATH?>/Member/ForgetIdpwd/Step2">
<input type="hidden" name="w" value="idpwd" />

<fieldset>
	<legend>STEP 01) <?=$title?></legend>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_id">회원아이디</label>
		<div class="col-md-9">
			<input type="text" id="mb_id" name="mb_id" class="form-control span3" maxlength="20" />
			
			<div class="btn-group" data-toggle="buttons">
				<label id="not_mb_id" class="btn btn-xs btn-default">
					<input type="checkbox" name="not_mb_id" value="1" />
					<span class="glyphicon glyphicon-unchecked"></span> 아이디 분실시 체크
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_name">이름</label>
		<div class="col-md-9">
			<input type="text" id="mb_name" name="mb_name" class="form-control span2" maxlength="10" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_email">이메일</label>
		<div class="col-md-4">
			<div class="input-group">
  				<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
				<input type="email" id="mb_email" name="mb_email" class="form-control" maxlength="50" />
			</div>
		</div>
	</div>
	
	<hr />
	
	<p class="text-center">
		<button type="submit" class="btn btn-ms btn-success">다음</button>
	</p>
</fieldset>

</form>

</div>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_ext.js'></script>
<script type='text/javascript'>
//<![CDATA[
$('#not_mb_id').checkicon();

$(function() {
	$('#not_mb_id').find('> input').change(function() {
		var mb_id = $('#mb_id');

		if (this.checked)
			mb_id.val('');
		
		mb_id.prop('disabled', this.checked);
	});

	$('#fidpwd_forget').validate({
		rules: {
			mb_id : { required: "#mb_id:enabled", alphanumunder:true },
			mb_name: { required:true, minlength:2, hangul:true },
			mb_email: { required:true, email:true }
		},
		messages: {
			mb_id: { required: "아이디를 입력하세요." },
			mb_name: { required: "이름을 입력하세요.", minlength: "최소 2자 이상 입력하세요." },
			mb_email: { required: "이메일을 입력하세요.", email: "올바른 이메일 형식이 아닙니다." }
		}
	});
});
//]]>
</script>