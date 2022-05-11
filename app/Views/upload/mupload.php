<form method="POST" action="/api/upload/mupload" enctype="multipart/form-data"> <!-- (1) --><!-- (2) -->
    <p>
        단일 파일 업로드
        <p>
        멀티 파일 업로드
        <input type="file" name="files[]" multiple="multiple" /> <!-- (1) -->
        <input type="file" name="files[]" multiple="multiple" /> <!-- (1) -->
        <input type="file" name="files[]" multiple="multiple" /> <!-- (1) -->
    </p>
    <input type="submit" value="입력" />
    <hr />

</form>
