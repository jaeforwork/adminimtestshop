<div class="col-md-10 wrap_content">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="page-header">
			<h4>자기소개 <small></small></h4>
		</div>

		<dl>
			<dt><span class="text-info">별명</span></dt>
			<dd>&raquo; <?=$name?></dd>

			<? if ($homepage): ?>
			<dt><span class="text-info">홈페이지</span></dt>
			<dd>&raquo; <a href="<?=$homepage?>" target="_blank"><?=$homepage?></a></dd>
			<? endif; ?>

			<dt><span class="text-info">포인트</span></dt>
			<dd>&raquo; <?=$point?> 점</dd>
			<dt><span class="text-info">회원가입일</span></dt>
			<dd>&raquo; <?=$join_date?></dd>
			<dt><span class="text-info">최종접속일</span></dt>
			<dd>&raquo; <?=$last_login?></dd>
			<dt><span class="text-info">자기소개</span></dt>
			<dd>&raquo; <?=$profile?></dd>
		</dl>

		<hr/>

		<p class="text-center">
			<button type="button" class="btn btn-warning" onclick="window.close();">닫기</button>
		</p>

		</div>
	</div>
	<div class="col-md-1"></div>
<script type='text/javascript' src='<?=JS_DIR?>/sideview.js'></script>