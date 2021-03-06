<div class="col-md-10 wrap_content">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="page-header">
			<h4><?=$title?> <small></small></h4>
		</div>

		<ul class="nav nav-tabs">
			<li><a href="<?=$path?>/Memo/Lists/R">받은쪽지</a></li>
			<li><a href="<?=$path?>/Memo/Lists/S">보낸쪽지</a></li>
			<li class="active"><a href="<?=$path?>/Memo/Write">쪽지보내기</a></li>
		</ul>
		<br/>

		<form id="fmemo" class="form-horizontal" name="fmemo" method="post" action="<?=RT_PATH?>/Member/Memo/Write">

		<?=validation_errors("<pre>","</pre>")?>
		<fieldset>
			<div class="form-group">
				<label class="col-xs-3 control-label" for="recv_mb_id">받는 회원아이디</label>
				<div class="col-xs-9">
					<input type="text" id="recv_mb_id" name="recv_mb_id" class="form-control span3" value="<?=$recv_mb_id?>" />
					<span class="help-block">※ 여러 회원에게 보낼 때는 콤마(,)로 구분하세요.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label" for="me_content">내용</label>
				<div class="col-xs-9">
					<textarea id="me_content" name="me_content" class="form-control" rows="5"></textarea>
				</div>
			</div>
			
			<hr />
			
			<p class="text-center">
				<button type="submit" class="btn btn-success">확인</button>&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-warning" onclick="window.close();">창닫기</button>
			</p>
		</fieldset>

		</form>

		</div>
	</div>
	<div class="col-md-1"></div>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript'>
//<![CDATA[
$(function() {
	$('#fmemo').validate({
		rules: {
			recv_mb_id: 'required',
			me_content: 'required'
		},
		messages: {
			recv_mb_id: '받는 회원아이디를 입력하세요.',
			me_content: '내용을 입력하세요.'
		}
	});
});
//]]>
</script>