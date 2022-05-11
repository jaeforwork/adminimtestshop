
<form method="POST"  action="/api/file/upload"  enctype="multipart/form-data"> <!-- (1) --><!-- (2) -->
    <p>
        단일 파일 업로드
        <input type="file" name="single_file" /> <!-- (3) -->
    </p>
    <input type="submit" value="입력" />
    <hr />
    <?php 

    foreach ($fileInfo as $key => $val) : ?> <!-- (4) -->
        <p><?= $key ?> : <?= $val ?></p>
    <?php endforeach; ?>

</form>
