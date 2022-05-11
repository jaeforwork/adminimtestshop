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

<p>접근 IP : <?php echo $_SERVER['REMOTE_ADDR'];?></p>
<p><span style="color:red;font-weight:bold">공지 : 카드 결제 처리는 PG사의 승인이 나야 테스트 가능으로 연기</span></p>
<br></br>

<p>====== sms 인증번호 받기 ========</p>
<form method="post" action="/api/sms/sms_auth">
 method="post" action="/api/sms/sms_auth"
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> </p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" />	필수		UUID</p> 
    <p>app_type : <input type="text" value='A' name="app_type" />필수	A/I	A : 안드로이드, I:아이폰  (영문은 대문자)</p> 

    <!-- <p>type : <input type="text" value='sms' name="type" /></p>  -->

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>-는 넣어도 되고 안넣어도 빼고 들어간다.</p>
<p> 카카오 알림톡으로 나가고 미수신시 sms으로 발송됨.</p>
<br></br>
<br></br>

<p>====== sms 인증번호확인 후 회원 중복 확인 ========</p>
<form method="post" action="/user/member/check_join">
 method="post" action="/user/member/check_join"
    <p>phone : <input type="text" value='010-7448-6585' name="phone" /> </p> 
    <p>device_id : <input type="text" value='3d35a25eadfcc738' name="device_id" />	필수		UUID</p> 
    <p>app_type : <input type="text" value='A' name="app_type" />필수	A/I	A : 안드로이드, I:아이폰  (영문은 대문자)</p> 

    <p>user_type : <input type="text" value='U' name="user_type" />필수	U/D	U : 사용자, D:드라이버 (영문은 대문자)</p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>* phone, device_id,  app_type, user_type, : 이 4개의 값이 같아야 이전 사용자로 판단 함.(소문자)</p>
<br></br>
<br></br>

====== 회원 정보 중복체크 ========
<form method="post" action="/user/member/check_dup"> 
/user/member/check_dup <br>

    <p>device_id : <input type="text" value="device_id" name="device_id" /></p> 
    <p>app_type : <input type="text" value="A" name="app_type" /></p> 

    <p>where : <input type="text" value="" name="where" /></p> user_id, nick_name, phone 3개의 필드에서 검색
    <p>value : <input type="text" value="" name="value" /></p> 전화번호는 - 넣어도 안넣어도 검색이 됨
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>비 가입회원이기 때문에 INPUT으로 정보 받아야 함.</p>
<p>어느 페이지에서든 회원의 아이디, 닉네임, 휴대전화번호 중복은 여기로 하면 됨.</p>
<br></br>
<br></br>


<p> ====== 회원 중복체크 카톡사용자 22.04.19 신규======== </p> 
<form method="post" action="/user/member/check_dup_openid"> 
/user/member/check_dup_openid <br>
<p>openid : kakaoID or appleID : <input type="text" value='' name="openid" /> </p> 
<p>login_type : kakao or apple : <input type="text" value='kakao' name="login_type" /> </p> 
<p>user_type : <input type="text" value='U' name="user_type" />필수	U/D	U : 사용자, D:드라이버 (영문은 대문자)</p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>삭제후 또는 게스트사용중 카카오톡이나 애플아이디로 가입시도 시 필수 확인 .</p>
<p>카카오톡인증 후 전송해야 함</p>
<br></br>
<br></br>





<p>====== 회원 복구 22.04.19 신규 ======== </p> 
<form method="post" action="/user/member/recover"> 
/user/member/recover <br>
<p>login_type : p: phone, kakao :kakao, apple :apple <input type="text" value='kakao' name="login_type" />필수</p> 
  <p>openid : 카카오인경우 필수 애플인경우 필수<input type="text" value='' name="openid" /> </p> 
  <p>phone : <input type="text" value='010-7448-6585' name="phone" /> </p> 
    <p>device_id : <input type="text" value='3d35a25eadfcc738' name="device_id" />	필수		UUID</p> 
    <p>app_type : <input type="text" value='A' name="app_type" />필수	A/I	A : 안드로이드, I:아이폰  (영문은 대문자)</p> 
    <p>email : <input type="text" value='' name="email" />필수</p> 
    <p>push_token : <input type="text" value='' name="push_token" />필수</p> 

    <p>user_type : <input type="text" value='U' name="user_type" />필수	U : 사용자, D:드라이버 (영문은 대문자)</p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>카톡 사용자는 kakaoid 필수 포함, 게스트 사용자는 phone 필수</p>
<p>앱삭제후 또는 일반 복구시 사용 : 게스트는 입력값 전체가 다 맞아야 복구, 카톡사용자나 애플사용자는 고유아이디값이 기존 데이타와 맞아야 함.</p>
<br></br>
<br></br>










<p> ====== 회원가입-사용자만, 드라이버는 드라이버 페이지에서  ======== </p>
<br></br>

<p> ======전화번호로만 가입(문자확인후)바로 이용등록  ======== </p>
<form method="post" action="/user/member/join">
/user/member/join
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> 중복안됨</p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" />중복안됨</p>
    <p>push_token : <input type="text" value='push_token' name="push_token" />중복안됨</p> 
    <p>sms_check : <input type="text" value='Y' name="sms_check" /></p> 

    <p>login_type : <input type="text" value='p' name="login_type" /></p> 
    <p>user_type : <input type="text" value='U' name="user_type" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p> user_type=D: 기사, U:일반 사용자,</p>
<p>login_type= 로그인 타입 id, p, idx' / kakao- kakao, apple- apple, p-전화번호로, idx-idx로      기사는 id로만</p>

<br></br>
<br></br>

<p> ====== 오픈아이디로 가입(문자확인후) 22.04.20 신규 ======== </p> 
<form method="post" action="/user/member/join">
/user/member/join
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> 중복안됨</p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" />중복안됨</p>
    <p>push_token : <input type="text" value='push_token' name="push_token" />중복안됨</p> 
    <p>sms_check : <input type="text" value='Y' name="sms_check" /></p> 

    <p>login_type :kakao 또는 apple <input type="text" value='kakao' name="login_type" /></p> 
    <p>email :kakao 또는 apple <input type="text" value='email' name="email" /></p> 
    <p>openid :kakao ID 또는 apple ID <input type="text" value='openid' name="openid" /></p> 
    <p>nick_name :kakao nick_name 또는 apple nick_name <input type="text" value='nick_name' name="nick_name" /></p> 
    <p>age :kakao 연령대 <input type="text" value='10' name="age" /></p> 
    <p>user_type : <input type="text" value='U' name="user_type" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p> user_type=D: 기사, U:일반 사용자,</p>
<p>login_type= 로그인 타입 id, p, idx' / kakao- kakao, apple- apple, p-전화번호로, idx-idx로      기사는 id로만</p>

<br></br>
<br></br>


<p> ====== 아이디,비밀번호로 가입(문자확인후) ======== </p>
<form method="post" action="/user/member/join">
method="post" action="/user/member/join"
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> 중복안됨</p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" />중복안됨</p>
    <p>push_token : <input type="text" value='push_token' name="push_token" />중복안됨</p> 
    <p>sms_check : <input type="text" value='Y' name="sms_check" />SMS인증 결과값 Y, N : Y일때만 이 페이지에서 처리</p> 

    <p>login_type : <input type="text" value='id' name="login_type" /></p> 
    <p>user_id : <input type="text" value='user_id33' name="user_id" />중복안됨</p>
    <p>passwd : <input type="text" value='123456' name="passwd" /></p>  
    <p>nick_name : <input type="text" value='nickname' name="nick_name" />중복안됨</p>   
    <p>user_type : <input type="text" value='U' name="user_type" /></p>    

    <p><input type="submit" value="입력" /></p> 
</form> 
<p> user_type=D: 기사, U:일반 사용자,</p>
<p>이전에 중복체크를 다 거쳐서 들어와야 합니다.</p>
<p>login_type= 로그인 타입 id, p, idx' / id- id로 p-전화번호로, idx-idx로    중요:  기사는 id로만 가입</p>
<br></br>
<br></br>

====== Access code 받기 ========
<form method="post" action="/member/certify/getcode">
<p>method="post" action="/member/certify/getcode"</p>   
    <p>access_data[user_idx] : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_data[access_token] : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>    
    <p>access_data[refresh_token] : 안맞으면 오류남<input type="text" value="" name="access_data[refresh_token]" />필수</p> 
    <p>access_data[device_id] : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>access_data[app_type] : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br></br>
<br></br>

====== 로그인 access code ========
<form method="post" action="/user/member/login">
/user/member/login access code로
    <p>login_type : idx <input type="text" value='idx' name="login_type" /></p>     
    <p>user_type : <input type="text" value='U' name="user_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" /></p> 
    <p>app_type : <input type="text" value="A" name="app_type" /></p> 
    <p>push_token : <input type="text" value='push_token' name="push_token" /></p> 
    <p>phone : <input type="text" value='010-1234-5678' name="phone" /> </p>     
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br></br>
<br></br>

====== 로그인 전화번호 ========
<form method="post" action="/user/member/login">
/user/member/login 전화번호로
    <p>user_type : <input type="text" value='U' name="user_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" /></p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>push_token : <input type="text" value='push_token' name="push_token" /></p> 
    <p>phone : <input type="text" value='' name="phone" /> </p> 
    <p>login_type : <input type="text" value='p' name="login_type" /></p> 
  


    <p><input type="submit" value="입력" /></p> 
</form> 
<p>login_type= 로그인 타입 id, p, idx' / id- id로 p-전화번호로, idx-idx로      기사는 I로만</p>
<p> user_type=D: 기사, U:일반 사용자,</p>
<br><br>
<br><br>


====== 로그인 id.pw ========
<form method="post" action="/user/member/login">
/user/member/login 전화번호로
    <p>user_type : <input type="text" value='U' name="user_type" /></p> 
    <p>device_id : <input type="text" value='device_id' name="device_id" /></p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>push_token : <input type="text" value='push_token' name="push_token" /></p> 
    <p>phone : <input type="text" value='' name="phone" /> </p> 
    <p>user_id : <input type="text" value='' name="user_id" /> </p> 
    <p>passwd : <input type="text" value='' name="passwd" /> </p> 
    <p>login_type : <input type="text" value='id' name="login_type" /></p> 
  


    <p><input type="submit" value="입력" /></p> 
</form> 
<p>login_type= 로그인 타입 id, p, idx' / id- id로 p-전화번호로, idx-idx로      기사는 I로만</p>
<p> user_type=D: 기사, U:일반 사용자,</p>
<br><br>
<br><br>

====== 회원 비번 변경 ========
<form method="post" action="/user/member/new_pwd"> 
method="post" action="/user/member/new_pwd" 
<p>access_data[user_idx] : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_data[access_token] : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>access_data[device_id] : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>access_data[app_type] : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>pwd : <input type="text" value="" name="pwd" /></p>
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 내프로필 이미지 등록 ========</p>
<form method="POST" action="/api/upload/member" enctype="multipart/form-data"> 
/api/upload/member
    <p>
        단일 파일 업로드
        <input type="file" name="single_file" /> 
    </p>
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>
====== 내프로필 이미지 삭제 ========
<form method="post" action="/api/upload/delete_member_img"> 
/api/upload/delete_member_img
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>


====== 내정보 요청 ========
<form method="post" action="/user/member/info_view"> 
/user/member/info_view
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>     
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>

====== 내정보 수정 ========
<form method="post" action="/user/member/info_update"> 
/user/member/info_update
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>     
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>nick_name : <input type="text" value="nick_name" name="nick_name" /></p> 
    <p>phone : <input type="text" value="010-4567-8963" name="phone" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>


====== 탈퇴 ========
<form method="post" action="/user/member/delete"> 
/user/member/delete
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
  <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

  <p>agreed : <input type="text" value="Y" name="agreed" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>동의는 반드시 Y를 해야 함.</p>
<br><br>
<br><br>

<p> = 팻 리스트 요청  22-04-11 수정됨 = </p> 
<form method="post" action="/user/pet/list"> 
/user/pet/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
<p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> = 팻 등록  22-04-28 수정됨 = </p> 
<form method="post" action="/user/pet/put"> 
/user/pet/put
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    
    <p>pet_name : <input type="text" value="" name="pet_name" /></p> 
    <p>pet_type : 개:D,고양이:C, 기타:E<input type="text" value="" name="pet_type" /></p> 
    <p>pet_kind : 품종 <input type="text" value="" name="pet_kind" /></p>    
    <p>character : 성격<input type="text" value="" name="character" /></p> 
    <p>comment : 한줄소개<input type="text" value="" name="comment" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 팻 수정  22-04-22 신규 = </p> 
<form method="post" action="/user/pet/update"> 
/user/pet/update
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>pet_idx : 펫번호 <input type="text" value="" name="pet_idx" /></p> 
    <p>pet_name : <input type="text" value="" name="pet_name" /></p> 
    <p>pet_type : 개:D,고양이:C<input type="text" value="" name="pet_type" /></p> 
    <p>pet_kind : 품종 <input type="text" value="" name="pet_kind" /></p>    
    <p>character : 성격<input type="text" value="" name="character" /></p> 
    <p>comment : 한줄소개<input type="text" value="" name="comment" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>  = 팻 삭제  22-04-22 신규 =  </p> 
<form method="POST" action="/user/pet/delete"> 
/user/pet/delete   
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" style="width:400px;"/></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>pet_idx : 팻 idx<input type="text" value="" name="pet_idx" /></p> 
    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>


<p> ====== post 팻 이미지 등록 ======== </p>
<form method="POST" action="/api/upload/pet" enctype="multipart/form-data"> 
/api/upload/pet
    <p>
        단일 파일 업로드 :jpg png 파일만 가능(jpeg는 안됨(필요하면 알려주세요)
        <input type="file" name="single_file" /> 
    </p>
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;"  style="width:400px;"/></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>pet_idx : <input type="text" value="" name="pet_idx" /></p> 
    <input type="submit" value="입력" />
</form>
<br><br>
* 이미지가 있을 경우 기존 이미지는 삭제되고 새로운 이미지로 업로드 됨
<br><br>

<p> ====== post 팻 이미지 삭제 ======== </p>
<form method="POST" action="/api/upload/delete_pet_img" enctype="multipart/form-data"> 
/api/upload/delete_pet_img
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" style="width:400px;"/></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>idx : <input type="text" value="" name="idx" /></p> 
    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>

<p> ====== 팻 정보 요청 ======== </p>
<form method="post" action="/user/pet/view"> 
/user/pet/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
<p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>pet_idx : <input type="text" value="" name="pet_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>
<p> ================ 예약  ======================= </p>
<br><br>
<p> ====== 예약하기 ======== </p>
<br><br>
<form method="post" action="/user/transport/order"> 
/user/transport/order
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>call_type : <input type="text" value="R" name="call_type" /></p> R: 예약 ,N: 지금 C:취소
    <p>loc_start_lon : <input type="text" value="126.9850380932383" name="loc_start_lon" /></p> 
    <p>loc_start_lat : <input type="text" value="37.566567545861645" name="loc_start_lat" /></p> 
    <p>loc_dest_lon : <input type="text" value="127.10331814639885" name="loc_dest_lon" /></p> 
    <p>loc_dest_lat : <input type="text" value="37.403049076341794" name="loc_dest_lat" /></p> 
    <p>addr_start : <input type="text" value="경기도 성남시 분당구 분당로 55 퍼스트타워 9층" name="addr_start" /></p> 
    <p>addr_dest : <input type="text" value="경기도 성남시 분당구 대왕판교로 660" name="addr_dest" /></p> 
    <p>round_trip : <input type="text" value="Y" name="round_trip" /></p> 
    <p>reserve_time : 예약시간 2022-04-11 12:00:00<input type="text" value="2022-04-11 12:00:00" name="reserve_time" /></p> 
    <p>e_distance : 예상 운행 거리(1000미터)<input type="text" value="2000" name="e_distance" /></p> 
    <p>e_time : 예상운행시간(예:90분)<input type="text" value="90" name="e_time" /></p> 
    <p>e_arrive_time : 출발지 예상도착시간 <input type="text" value="60" name="e_arrive_time" /></p> 
    <p>e_fee : 예상 요금<input type="text" value="" name="e_fee" /></p> 
    <p>pet_list : 반려동물 idx<input type="text" value="1,2,3" name="pet_list" /></p>   
    <p>user_ride : 고객 동승여부 (Y/N)<input type="text" value="Y" name="user_ride" /></p> 
    <p>r_fee : 예약콜비<input type="text" value="" name="r_fee" /></p> 
    <p>c_idx : 사용 쿠폰 IDX<input type="text" value="" name="c_idx" />현재는 하나만 사용가능</p>
    <p>memo : <input type="text" value="memods" name="memo" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 실시간 호출 확인  22-04-13 신규=</p> 
<form method="post" action="/user/transport/tr_check"> 
/user/transport/tr_check
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 내 호출 리스트  22-04-12 신규 = </p> 
<form method="post" action="/user/transport/mylist"> 
/user/transport/mylist
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>call_type : 예약 R, 실시간 : N <input type="text" value="" name="call_type" /></p> 
    
  <p>order : 전체,일자 = created_at,tr_idx,RESERVE_TIME <input  value="" name="order"/></p> 
  <p>by : 내림차순(큰->작은) desc, 오름순(작은->큰) asc <input  value="" name="by"/></p> 
  <p>page : 페이지 1 <input  value="1" name="page"/></p> 
  <p>perpage : 페이지당 리스트 갯수 10 <input  value="10" name="perpage"/></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 예약취소 ======== </p> 
<form method="post" action="/user/transport/cancel"> 
/user/transport/cancel
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>reason : 사유 <input type="text" value="" name="reason" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 내 호출 확인 또는 보기  22-04-12 업데이트 = </p> 
<form method="post" action="/user/transport/view"> 
/user/transport/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 



    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 최근방문 목록 ========</p> 
<form method="post" action="/user/visit/list"> 
/user/visit/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  
  <p>order : 전체,일자 = created_at <input  value="" name="order"/></p> 
  <p>by : 내림차순(큰->작은) desc, 오름순(작은->큰) asc <input  value="" name="by"/></p> 
  <p>page : 페이지 1 <input  value="1" name="page"/></p> 
  <p>perpage : 페이지당 리스트 갯수 10 <input  value="10" name="perpage"/></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ======최근방문 보기 ========</p> 
<form method="post" action="/user/visit/view"> 
/user/visit/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>



<p> ======최근방문 삭제 ======== </p> 
<form method="post" action="/user/visit/delete"> 
/user/visit/delete
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 내 포인트 확인  22-04-11 신규 = </p> 
<form method="post" action="/user/point/mypoint"> 
/user/point/mypoint
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 포인트 목록 ========</p> 
<form method="post" action="/user/point/list"> 
/user/point/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>page : <input type="text" value="1" name="page" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ======포인트 보기 ======== </p> 
<form method="post" action="/user/point/view"> 
/user/point/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>po_idx : <input type="text" value="" name="po_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ======포인트 사용 ======== </p>
<form method="post" action="/user/point/use"> 
/user/point/use
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>user_idx : user_idx<input type="text" value="" name="user_idx" /></p> 
    <p>point : 사용포인트<input type="text" value="" name="point" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> = 리뷰등록  22-04-11 신규 = </p> 
<p>결제까지 끝나야 드라이버에 대한 리뷰작성이 가능함. tr_idx 하나당 한번.TRANSPORT_END table 사용</p> 
<form method="post" action="/user/review/post"> 
/user/review/post
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>star : 0-5 까지<input type="text" value="5" name="star" /></p> 
    <p>tr_idx : 호출번호<input type="text" value="9" name="tr_idx" /></p> 
    <p>comment : <input type="text" value="COMMENT" name="comment" style="width:400px;"/></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>




















<p>====== card 리스트 (카드정보를 가지고 있을 필요가 없어서 앱에서 저장하면 될듯)======== </p>
<form method="post" action="/user/card/list"> 
/user/card/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> = card 등록  22-04-11 신규 - 13일 수정 = </p> 
<form method="post" action="/user/card/reg"> 
/user/card/reg
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>card_name : 국민카드, 신한카드 등 <input type="text" value="" name="card_name" /></p> 
    <p>card_num : 카드번호 <input type="text" value="" name="card_num" /></p> 
    <p>year : 유효기간 년<input type="text" value="" name="year" /></p> 
    <p>month : 유효기간 월 <input type="text" value="" name="month" /></p> 
    <p>type : 법인 C,개인 P<input type="text" value="" name="type" /></p> 
    <p>owner_num : 법인은 법인번호, 개인은 주민번호<input type="text" value="" name="owner_num" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== card 보기 (카드정보를 가지고 있을 필요가 없어서 앱에서 저장하면 될듯) ======== </p> 
<form method="post" action="/user/card/view"> 
/user/card/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 


    <p>idx : 카드 고유값<input type="text" value="" name="idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>



<p> ====== 결제 리스트 ======== </p> 
<form method="post" action="/user/payment/list"> 
/user/payment/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p>  
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>page : <input type="text" value="1" name="page" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 결제 보기 ======== </p> 
<form method="post" action="/user/payment/view"> 
/user/payment/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 쿠폰 등록 22.04.27 ======== </p> 
<form method="post" action="/user/coupon/reg"> 
/user/coupon/reg
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>cp_id : 쿠폰정보 고유값 2022-AAAA-0225-3T8Z <input type="text" value="" name="cp_id" /></p> 
    <p>reg_end_date : 쿠폰등록 마감일 <input type="text" value="2020-11-01" name="reg_end_date" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
고유값하고 마감일이 같아야 등록됨
<br><br>


<p> ====== 쿠폰 리스트 ======== </p> 
<form method="post" action="/user/coupon/list"> 
/user/coupon/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>page : 보려는 페이지 <input type="text" value="1" name="page" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 쿠폰 보기 ======== </p> 
<form method="post" action="/user/coupon/view"> 
/user/coupon/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 



    <p>cp_idx : 쿠폰정보 고유값<input type="text" value="" name="cp_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 쿠폰 사용 (필요없을듯)======== </p> 
<form method="post" action="/user/coupon/use"> 
/user/coupon/use
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>cp_idx : 쿠폰정보 고유값<input type="text" value="" name="cp_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== board list ========</p>
<form method="post" action="/user/board/list"> 
/user/board/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>board : notice, fna, fqna <input type="text" value="notice" name="board" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== board 보기 ======== </p>
<form method="post" action="/user/board/view"> 
/user/board/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>board : notice, fna, fqna <input type="text" value="notice" name="board" /></p> 


    <p>idx : <input type="text" value="1" name="idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 운행정보 위치 보기 ======== </p>
<form method="post" action="/user/transport/driver_loc"> 
/user/transport/driver_loc
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>  ====== 실시간 요금조회 보기 22.04.28 신규 ======== </p> 
<form method="post" action="/user/transport/realtime_fee"> 
/user/transport/realtime_fee
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 채팅 test 하기 ======== </p>
<form method="post" action="/api/chat/test"> 
/api/chat/test
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 채팅방보기 22.04.27 신규 ======== </p> 
<form method="post" action="/api/chat/room"> 
/api/chat/room
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 채팅하기 22.04.27 신규 ======== </p> 
<form method="post" action="/user/chat/send"> 
/user/chat/send
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>to_idx : 드라이버 to_idx <input type="text" value="" name="to_idx" /></p> 
    <p>message : <input type="text" value="" name="message" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>




<p> ====== 채팅 가져오기 22.04.27 신규 ======== </p> 
<form method="post" action="/api/chat/get"> 
/api/chat/get
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>



<p> ====== 근처 추천가계 리스트 22.04.21 ======== </p> 
<form method="post" action="/user/recommend/shop_list"> 
/user/recommend/shop_list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>loc_lon : <input type="text" value="" name="loc_lon" /></p> 
    <p>loc_lat : <input type="text" value="" name="loc_lat" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>




<p> ====== 근처 추천가계 보기 22.04.21 ========  </p> 
<form method="post" action="/user/recommend/shop_view"> 
/user/recommend/shop_view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>shop_idx : <input type="text" value="" name="shop_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 운행 CCTV  보기 22.04.26 수정 ======== </p> 
<form method="post" action="/user/cctv/cctv_url"> 
/user/cctv/cctv_url
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>




<p>================================================</p>
여기까지 TEST 완료  22년 4월 26일에 수정됨
<p>================================================</p>


<p>============= 아래는 미완료 PG사 승인 후 가능 =================================</p>



<p>=========== 카드는 추후 테스트 가능 ===========</p>
<div>
<p><span style="color:red"> ====== card 유효성만 검사할때  ======== </span></p> 
<form method="post" action="/user/card/test_pay"> 
<p>/user/card/test_pay</p>

<div>
  <table>
    <tr><td>USER_IDX</td><td><input type="text" value="" name="access_data[user_idx]" /></td></tr>
    <tr><td>access_token</td><td><input type="text" value="" name="access_data[access_token]" style="width:400px;"/></td></tr>
    <tr><td>device_id</td><td><input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></td></tr>
    <tr><td>app_type</td><td><input type="text" value="A" name="access_data[app_type]" /></td></tr>
  </table>
</div>    
    <p>card_num : 카드번호<input type="text" class="it1" value="" name="card_num" />       </p> 
    <p>month : <input type="text" class="it1" value="" name="month" />       </p> 
    <p>year : <input type="text" class="it1" value="" name="year" />       </p> 
    <p>cvc : <input type="text" class="it1" value="" name="cvc" />       </p> 
    <p>passwd : 비밀번호 앞2자리 : <input type="text" class="it1" value="" name="passwd" />       </p> 
    <p>jumin_number : 주민등록번호 : <input type="text" class="it1" value="" name="jumin_number" />       </p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
- test 결제는 100원 결제 후 10분뒤 자동 취소
</div>
<br><br>
<br><br>


<div>
<p><span style="color:red"> ====== card 유효성 검사 및 등록 ======== </span></p> 
<form method="post" action="/user/card/reg"> 
<p>/user/card/reg</p>
<div>
  <table>
    <tr><td>USER_IDX</td><td><input type="text" value="" name="access_data[user_idx]" /></td></tr>
    <tr><td>access_token</td><td><input type="text" value="" name="access_data[access_token]" style="width:400px;"/></td></tr>
    <tr><td>device_id</td><td><input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></td></tr>
    <tr><td>app_type</td><td><input type="text" value="A" name="access_data[app_type]" /></td></tr>
  </table>
</div>    
    <p>card_num : 카드번호  <input type="text" class="it1" value="" name="card_num" />       </p> 
    <p>month : <input type="text" class="it1" value="" name="month" />       </p> 
    <p>year : <input type="text" class="it1" value="" name="year" />       </p> 
    <p>cvc : <input type="text" class="it1" value="" name="cvc" />       </p> 
    <p>passwd : 비밀번호 앞2자리 : <input type="text" class="it1" value="" name="passwd" />       </p> 
    <p>jumin_number : 주민등록번호 : <input type="text" class="it1" value="" name="jumin_number" />       </p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
- test 결제는 100원 결제 후 10분뒤 자동 취소
<br><br>
</div>
<br><br>
<br><br>



<p> ====== card 결제요청정보 신규======== </p> 
<form method="post" action="/user/payment/prepaylist"> 
/user/payment/prepaylist
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <div class="payDev">
      <dl>
        <dt>* 호출고유값 tr_idx</dt>
        <dd><input type="text" class="it1" value="" name="tr_idx" /></dd>
      </dl>
    <div>        
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>



<p>====== card 결제요청 ========</p>
<form method="post" action="/user/card/bill"> 
/user/card/bill
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <div class="payDev">
    <dl>
        <dt>* tr_idx : 호출번호</dt>
        <dd><input type="text" class="it1" value="" name="tr_idx" /></dd>
      </dl>
      <dl>
      <dl>
        <dt>* coupon_list : 쿠폰번호</dt>
        <dd><input type="text" class="it1" value="1,2,3" name="coupon_list" /></dd>
      </dl>
      <dl>
        <dt>* point : 사용포인트</dt>
        <dd><input type="text" class="it1" value="" name="point" /></dd>
      </dl>
      <dl>
        <dt>*  d_fee : 기본요금</dt>
        <dd><input type="text" class="it1" value="" name="d_fee" /></dd>
      </dl>
      <dl>
        <dt>* o_fee : 미터기요금 </dt>
        <dd><input type="text" class="it1" value="" name="o_fee" /></dd>
      </dl>
      <dl>
        <dt>* p_fee : 시계외할증 </dt>
        <dd><input type="text" class="it1" value="" name="p_fee" /></dd>
      </dl>
      <dl>
        <dt>* a_fee 추가요금</dt>
        <dd><input type="text" class="it1" value="" name="a_fee" /></dd>
      </dl>
      <dl>
        <dt>* dc_fee 할인 </dt>
        <dd><input type="text" class="it1" value="" name="dc_fee" /></dd>
      </dl>
      <dl>
        <dt>* r_fee 사전예약금 </dt>
        <dd><input type="text" class="it1" value="" name="r_fee" /></dd>
      </dl>

      <dl>
        <dt>* fee 최종결제금액 </dt>
        <dd><input type="text" class="it1" value="" name="fee" /></dd>
      </dl>
      <dl>
        <dt>* card_idx 등록된 카드 </dt>
        <dd><input type="text" class="it1" value="" name="card_idx" /></dd>
      </dl>

    <div>        
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== 조르기 결제 링크 요청 22.04.27 신규======== </p> 
<form method="post" action="/user/payment/askpay"> 
/user/payment/askpay
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p> tr_idx : 호출 번호 <input type="text" class="it1" value="" name="tr_idx" />       </p> 
    <p> fee : 전체금액<input type="text" class="it1" value="" name="fee" />       </p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== card 결제 취소 요청 ========</p>
<form method="post" action="/user/card/cancel"> 
/user/card/post
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p>fee : 금액<input type="text" class="it1" value="" name="fee" />       </p> 
    <p> tr_idx : 호출번호<input type="text" class="it1" value="" name="tr_idx" />       </p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>
<p>=========== 카드는 추후 테스트 가능 ===========</p>













<script>
jQuery.fn.serializeObject = function() {
    var obj = null;
    try {
        if (this[0].tagName && this[0].tagName.toUpperCase() == "FORM") {
            var arr = this.serializeArray();
            if (arr) {
                obj = {};
                jQuery.each(arr, function() {
                    obj[this.name] = this.value;
                });
            }//if ( arr ) {
        }
    } catch (e) {
        alert(e.message);
    } finally {
    }
 
    return obj;
};

var idcard_image_idx="", bank_image_idx="", auth_idx="";
var idCallback=function(data){
	$("#idcard_file").val("");
	if(data.result==FileUploader.RESULT_TYPE_START){
		
	}else if(data.result==FileUploader.RESULT_TYPE_END){	
		if(data.upload.result=="Y"){
			var fileInfo=data.upload.data;console.log(fileInfo);
			idcard_image_idx=fileInfo.file_idx;
			console.log(idcard_image_idx);
		}else{
			alert(data.upload.msg);
		}
	}
}


var idOptions={
		button:"#btn_idcard",
		target:"#idcard_file",
		uploadUrl:"/fileup/idcard",		
		resultCallback:idCallback,	
		imageLimit:{size:10*1024*1024, msg:"10MB"}		
}


var idUploader=new FileUploader(idOptions);
idUploader.init();



var bankCallback=function(data){
	$("#bank_file").val("");
	if(data.result==FileUploader.RESULT_TYPE_START){
		
	}else if(data.result==FileUploader.RESULT_TYPE_END){	
		if(data.upload.result=="Y"){
			var fileInfo=data.upload.data;console.log(fileInfo);
			bank_image_idx=fileInfo.file_idx;
			console.log(bank_image_idx);
		}else{
			alert(data.upload.msg);
		}
	}
}


var bankOptions={
		button:"#btn_bank",
		target:"#bank_file",
		uploadUrl:"/fileup/bank",		
		resultCallback:bankCallback,	
		imageLimit:{size:10*1024*1024, msg:"10MB"}		
}


var bankUploader=new FileUploader(bankOptions);
bankUploader.init();


function joinrequest(frm){
	$form=$(frm);
	var p=$form.serializeObject();
	p.deviceid="abdfsdafdsfds";
	p.app_type="A";
	p.push_token="push_token";
	p.auth_idx=auth_idx;
	p.email="kkk@aaaa.com";
	p.addr1="인천광역시 중구 용유동";
	p.addr2="잠진도";
	p.car="Y";
	p.mycar="Y";
	p.car_type="올란도/디젤/2018";
	p.career=10;
	p.allergy="N";
	p.atp="Y";
	p.ibrc="Y";
	p.idcard_image_idx=idcard_image_idx;
	p.bankbook_image_idx=bank_image_idx;
	p.comment="성공하자";

	$.post("/driver/member/joinrequest",p,function(res){
		console.log(res);
	});

	return false;
}



function auth(frm){
	$form=$(frm);
	var p=$form.serializeObject();
	

	$.post("/driver/member/authtest",p,function(res){
		console.log(res);
		var resultData=JSON.parse(res);
		if(resultData.result=="Y"){
			auth_idx=resultData.data.auth_idx;
		}else{
			alert(resultData.msg);
		}
	});

	return false;
}
</script>
</body>
</html>