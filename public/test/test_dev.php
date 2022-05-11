<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8"> 	
	<script src="js/jquery-3.5.1.min.js"></script>		
	<script src="js/jquery/circle-progress.min.js"></script>
	<script  src="js/file_upload.js"></script>	
</head>
<body>
<?php 
if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='222.112.186.71' || $_SERVER['REMOTE_ADDR']=='112.171.25.51' || $_SERVER['REMOTE_ADDR']=='175.117.79.16' || $_SERVER['REMOTE_ADDR']=='183.101.208.22'|| $_SERVER['REMOTE_ADDR']=='112.151.86.82' || $_SERVER['REMOTE_ADDR']=='121.65.132.178' || $_SERVER['REMOTE_ADDR']=='220.70.51.249' || $_SERVER['REMOTE_ADDR']=='222.112.65.220') {
} else {
  exit;
}
//사무실에서만 테스트 가능하도록 소스노출 우려
?>
<p>====== 개발을 위한 임시 API ========</p>
<br></br>

<p>접근 IP : <?php echo $_SERVER['REMOTE_ADDR'];?></p>
<p><span style="color:red;font-weight:bold">공지 : 카드 결제 처리는 PG사의 승인이 나야 테스트 가능으로 연기</span></p>
<p>expired_date : 2022-04-06 16:00:00 </p>
<p> 전송 필요없음. 앱에 보관 후 이시간 지나서는 다시 로그인을 시키던가 인증받도록 하면 됨</p> 
<br></br>

<p>====== 가입정보확인 ========</p>
<form method="post" action="/user/test/member">
 method="post" action="/user/test/member"
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> </p> 
    <p>user_type : <input type="text" value='U' name="user_type" /></p>  
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>-는 넣어도 되고 안넣어도 -를 포함하여 검색.</p>
<p> user_type=D: 기사, U:일반 사용자, 지금은 유저 정보만 나옵니다. 드라이버 정보는 조만간에 업데이트 하겠습니다.</p>
<br></br>


<p>====== 헤더 확인 ========</p>
<form method="post" action="/user/test/header">
 method="post" action="/user/test/header"
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> </p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>-서버로 전송된 header 값</p>
<br></br>





<p>====== 주소로 좌표값 보기 T-map ========</p>
<a href="http://api.petglet.com/test/map.php" target="_blank">
http://api.petglet.com/test/map.php
</a>

<p>-T-map</p>
<br></br>



<script>

</script>


</body>
</html>
