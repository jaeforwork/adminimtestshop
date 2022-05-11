<form method="POST" action="/api/upload/supload" enctype="multipart/form-data">
    <p>
        단일 파일 업로드
        <input type="file" name="single_file" /> 
    </p>
    <input type="submit" value="입력" />
    <hr />
    <?php foreach ($fileInfo as $key => $val) : ?> 
        <p><?= $key ?> : <?= $val ?></p>
    <?php endforeach; ?>
</form>
