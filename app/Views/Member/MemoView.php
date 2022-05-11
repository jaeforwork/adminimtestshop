<div class="col-md-10 wrap_content">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="page-header">
			<h4><?=$title?> <small></small></h4>
		</div>

		<ul class="pager">
			<li class="previous">
				<a href="<?=$prev_link?>">&larr; 이전</a>
			</li>
			<li class="next">
				<a href="<?=$next_link?>">다음 &rarr;</a>
			</li>
		</ul>

		<p class='text-right'><?=$memo_msg?></p>
		<p class="well"><?=$content?></p>

		<hr/>

		<p class="text-center">
			<?=$btn_reply?>
			<a href='<?=$path?>/Memo/Lists/<?=$flag?>' class="btn btn-success">목록보기</a>&nbsp;&nbsp;&nbsp;
			<button type="button" class="btn btn-warning" onclick="window.close();">창닫기</button>
		</p>
	</div>
	<div class="col-md-1"></div>
</div>
