
<div class="JoinCheckWrap">
<?=$LMenuName?>
<div class="ContentsWrap">

<form id="fjoin_check" name="fjoin_check" action="<?=RT_PATH?>/Member/Join" method="post">

<?=validation_errors("<pre>","</pre>")?>
<fieldset>
	<legend><?=$title?></legend>

	<h5>회원가입약관</h5>
	<textarea class="form-control" rows="15" readonly="readonly"><?=$stipulation?></textarea>
	<br/>

	<div class="btn-group" data-toggle="buttons">
		<label id="agree" class="btn btn-sm btn-default">
			<input type="checkbox" name="agree" value="1" />
			<span class="glyphicon glyphicon-unchecked"></span> 위의 '서비스 이용약관'에 동의합니다.
		</label>
	</div>

	<br/><br/>

	<h5>개인정보취급방침</h5>
	<h5>수집하는 개인정보의 항목 | 개인정보의 수집 이용목적 | 개인정보의 보유 및 이용기간 </h5>
	<textarea class="form-control" rows="15" readonly="readonly"><?=$privacy?></textarea>
	<br/>

	<div class="btn-group" data-toggle="buttons">
		<label id="agree2" class="btn btn-sm btn-default">
			<input type="checkbox" name="agree2" value="1" />
			<span class="glyphicon glyphicon-unchecked"></span> 위의 '개인정보 수집 이용'에 동의 합니다. 
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
$('#agree,#agree2').checkicon();

$(function() {
	$('#fjoin_check').validate({
		rules: {
			agree  : { required:true },
			agree2 : { required:true }
		},
		messages: {
			agree  : { required:'회원가입약관의 내용에 동의해야 회원가입 하실 수 있습니다.' },
			agree2 : { required:'개인정보취급방침의 내용에 동의해야 회원가입 하실 수 있습니다.' }
		}

	});
			$('#agree,#agree2').prop("checked",true);	


	
});
//]]>
</script>