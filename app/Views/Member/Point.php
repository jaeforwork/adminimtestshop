<div class="col-md-10 wrap_content">
<div class="page-header">
	<h4><?=$title?> <small>보유 포인트 : <strong><?=$mb_point?> 점</strong></small></h4>
</div>

<table class="table table-striped">
	<thead>
		<tr>
            <th>일시</th>
            <th>내용</th>
            <th>지급 포인트</th>
            <th>사용 포인트</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($list as $o): ?>
		<tr>
			<td><?=$o->po_datetime?></td>
			<td title="<?=$o->po_content?>"><?=$o->po_content?></td>
			<td><span class="label label-success"><?=$o->point1?></span></td>
			<td><span class="label label-danger"><?=$o->point2?></span></td>
		</tr>
		<? endforeach; ?>

		<? if ($sum_point1): ?>
		<tr class="success">
			<td colspan="2">소계</td>
			<td><?=$sum_point1?></td>
			<td><?=$sum_point2?></td>
		</tr>
		<? endif; ?>
	</tbody>
</table>
<? if (!$list): ?><p class="lead text-center text-muted">자료가 없습니다.</p><? endif; ?>

<div class="text-center">
	<?=$paging?>
</div>