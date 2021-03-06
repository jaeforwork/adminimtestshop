<div class="col-md-10 wrap_content">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="page-header">
				<h4><?=$title?> <small>전체<?=$flag_title?> 쪽지 [ <strong><?=$total_cnt?></strong> ]통 / 쪽지 보관일수는 최장 <?=$memo_del_day?>일 입니다.</small></h4>
			</div>

			<ul id="memo_nav" class="nav nav-tabs">
				<li data-idx='R'><a href="<?=$path?>/Memo/Lists/R">받은쪽지</a></li>
				<li data-idx='S'><a href="<?=$path?>/Memo/Lists/S">보낸쪽지</a></li>
				<li><a href="<?=$path?>/Memo/Write">쪽지보내기</a></li>
			</ul>
			<br/>

			<form name="fmemo" method="post" action="<?=RT_PATH?>/_trans/Member/MemoDelete">
				<input type="hidden" name="token" value="<?=$token?>" />
				<input type="hidden" name="flag" value="<?=$flag?>" />

				<table class="table table-hover">
					<thead>
						<tr>
							<th><input type="checkbox" id="allcheck" /></th>
							<th><?=$me_subject?></th>
							<th>내용</th>
							<th class="col-md-2"><?=$flag_title?>시간</th>
							<th class="col-md-1">확인</th>
							<th class="col-md-1">삭제</th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($list as $o): ?>
						<tr>
							<td><input type="checkbox" name="me_no[]" value="<?=$o->me_no?>" /></td>
							<td><?=$o->name?></td>
							<td><a href="<?=$o->view_href?>"><?=$o->content?></a></td>
							<td><a href="<?=$o->view_href?>"><?=$o->datetime?></a></td>
							<td><a href="<?=$o->view_href?>" title="<?=$o->check_time?>"><?=$o->check?></a></td>
							<td><button type="button" class="btn btn-xs btn-danger" onclick="post_send('<?=$o->del_href?>', <?=$o->del_parm?>, true);"><span class="glyphicon glyphicon-trash"></span></button></td>
						</tr>
					<? endforeach; ?>
				</tbody>
			</table>
			<? if (!$list): ?><p class="lead text-center text-muted">쪽지가 없습니다.</p><? endif; ?>

			<div class="clearfix">
				<button type="button" class="btn btn-danger" onclick="select_delete();">선택삭제</button>
				<div class="pull-right">
					<?=$paging?>
				</div>
			</div>

			<hr/>

			<p class="text-center">
				<button type="button" class="btn btn-warning" onclick="window.close();">닫기</button>
			</p>

		</form>
	</div>
	<div class="col-md-1"></div>
</div>
<script type='text/javascript' src='<?=JS_DIR?>/sideview.js'></script>
<script type='text/javascript'>
//<![CDATA[
$('#allcheck').click(function() {
	$("input[name='me_no[]']", document.fmemo).attr('checked', this.checked);
});

$('#memo_nav > li[data-idx="<?=$flag?>"]').addClass('active');

// 선택한 게시물 삭제
function select_delete() {
	var f = document.fmemo;
	if ($("input[name='me_no[]']:checked", f).length < 1) {
		alert('삭제할 쪽지를 하나 이상 선택하세요.');
		return;
	}

	if (!confirm('선택한 쪽지를 정말 삭제 하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다'))
		return;

	f.submit();
}
//]]>
</script>