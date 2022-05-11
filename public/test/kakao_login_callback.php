<?php
//kakao_login_callback.php
$returnCode = $_GET["code"]; // 서버로 부터 토큰을 발급받을 수 있는 코드를 받아옵니다
$restAPIKey = "b81db7a8aaa112f7c7e6d374b6250715"; // 본인의 REST API KEY를 입력해주세요
$callbacURI = urlencode("http://api.petglet.com/test/kakao_login_callback.php"); // 본인의 Call Back URL을 입력해주세요
$getTokenUrl = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=".$restAPIKey."&redirect_uri=".$callbacURI."&code=".$returnCode;
 
$isPost = false;

//http://api.petglet.com/test/kakao_login_callback.php?code=h2sXZGSg-E2N8AXHVS8BRZ9rqIGkvVQTPwXTSziIDgDNUVtiqw_rdZx-C4twin8txQgPrQopcNEAAAF_9_qrKA

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getTokenUrl);
curl_setopt($ch, CURLOPT_POST, $isPost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$headers = array();
$loginResponse = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);
 
$accessToken= json_decode($loginResponse)->access_token; //Access Token만 따로 뺌
  
$header = "Bearer ".$accessToken; // Bearer 다음에 공백 추가
$getProfileUrl = "https://kapi.kakao.com/v2/user/me";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getProfileUrl);
curl_setopt($ch, CURLOPT_POST, $isPost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$headers = array();
$headers[] = "Authorization: ".$header;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
$profileResponse = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);
 
var_dump($profileResponse); // Kakao API 서버로 부터 받아온 값
 
$profileResponse = json_decode($profileResponse);
 
$userId = $profileResponse->id;
$userName = $profileResponse->properties->nickname;
$userEmail = $profileResponse->kakao_account->email;
 
echo "<br><br> userId : ".$userId;
echo "<br> userName : ".$userName;
echo "<br> userEmail : ".$userEmail;
//string(966) "{"id":2188915874,"connected_at":"2022-04-05T04:30:27Z","properties":{"nickname":".","profile_image":"http://k.kakaocdn.net/dn/bjNlIl/btruLuEOchh/lxUUU2RRrS3jCHW9QWlzp1/img_640x640.jpg","thumbnail_image":"http://k.kakaocdn.net/dn/bjNlIl/btruLuEOchh/lxUUU2RRrS3jCHW9QWlzp1/img_110x110.jpg"},"kakao_account":{"profile_nickname_needs_agreement":false,"profile_image_needs_agreement":false,"profile":{"nickname":".","thumbnail_image_url":"http://k.kakaocdn.net/dn/bjNlIl/btruLuEOchh/lxUUU2RRrS3jCHW9QWlzp1/img_110x110.jpg","profile_image_url":"http://k.kakaocdn.net/dn/bjNlIl/btruLuEOchh/lxUUU2RRrS3jCHW9QWlzp1/img_640x640.jpg","is_default_image":false},"name_needs_agreement":false,"name":"최재훈","has_email":true,"email_needs_agreement":false,"is_email_valid":true,"is_email_verified":true,"email":"smartjaewon94@gmail.com","has_age_range":true,"age_range_needs_agreement":false,"age_range":"50~59","has_gender":true,"gender_needs_agreement":false,"gender":"male"}}"

//userId : 2188915874
//userName : .
//userEmail : smartjaewon94@gmail.com
?>