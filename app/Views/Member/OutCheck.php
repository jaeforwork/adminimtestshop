<div class="OutWrap">
<?=$LMenuName?>
<div class="ContentsWrap">
<form id="fout_check" name="fout_check" action="<?=RT_PATH?>/Member/Out" method="post">

<?=validation_errors("<pre>","</pre>")?>
<fieldset>
	<legend><?=$title?></legend>

	<h5>회원탈퇴안내</h5>
	<textarea class="form-control" rows="15" readonly="readonly"><?=$OutInfo?></textarea>
	<br/>

	<div class="btn-group" data-toggle="buttons">
		<label id="agree" class="btn btn-sm btn-default">
			<input type="checkbox" name="agree" value="1" />
			<span class="glyphicon glyphicon-unchecked"></span> 위의 '서비스 탈퇴'에 동의합니다.
		</label>
	</div>
	<hr />

	<p class="text-center">
		<button type="submit" class="btn btn-ms btn-success">확인</button>
	</p>
</fieldset>

</form>
</div>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript'>
//<![CDATA[
$('#agree').checkicon('agree','','check');

$(function() {	

	$('#fout_check').validate({
		rules: {
			agree  : { required:true }
		},
		messages: {
			agree  : { required:'회원탈퇴 내용에 동의하여 주세요.' }
		}
		

	});
	

});
//]]>
</script>