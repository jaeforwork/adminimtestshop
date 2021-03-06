<div class="MemberModifyWrap">
<?=$LMenuName?>
<div class="ContentsWrap">
<form id="fconfirm" class="form-horizontal" name="fconfirm" method="post" action="<?=$action?>">
<input type="hidden" name="mb_id" value="<?=$mb_id?>"/>
<input type="hidden" name="token" value="<?=$token?>"/>

<fieldset>
	<legend><?=$title?></legend>
	<div class="form-group">
		<label class="col-md-3 control-label">회원아이디</label>
		<div class="col-md-9">
			<p class="form-control-static text-primary"><strong><?=$mb_id?></strong></p>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_password">비밀번호</label>
		<div class="col-md-9">
			<input type="password" id="mb_password" name="mb_password" class="form-control span3" maxlength="20" />
			<button type="submit" class="btn btn-primary">확인</button>
			<span class="help-block">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
				외부로부터 회원님의 정보를 안전하게 보호하기 위해 비밀번호를 확인하셔야 합니다.
			</span>
		</div>
	</div>
</fieldset>

</form>

</div>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript'>
//<![CDATA[
$(function() {
	$('#mb_password').focus();
	$('#fconfirm').validate({
		rules: {
			mb_password: { required:true, minlength:3 }
		},
		messages: {
			mb_password: { required:'비밀번호를 입력하세요.', minlength:'최소 3자 이상 입력하세요.' }
		}
	});
});
//]]>
</script>