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
if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='222.112.186.71' || $_SERVER['REMOTE_ADDR']=='112.171.25.51' || $_SERVER['REMOTE_ADDR']=='175.117.79.16' || $_SERVER['REMOTE_ADDR']=='183.101.208.22' || $_SERVER['REMOTE_ADDR']=='112.151.86.82' || $_SERVER['REMOTE_ADDR']=='121.65.132.178' || $_SERVER['REMOTE_ADDR']=='220.70.51.249' || $_SERVER['REMOTE_ADDR']=='222.112.65.220') {



} else { 
  exit;
}
//사무실에서만 테스트 가능하도록 소스노출 우려
?>
<p>접근 IP : <?php echo $_SERVER['REMOTE_ADDR'];?></p>
<p><span style="color:red;font-weight:bold">공지 : 카드 결제 처리는 PG사의 승인이 나야 테스트 가능으로 연기</span></p>
<p>expired_date : 2022-04-06 16:00:00 </p>
<p> 전송 필요없음. 앱에 보관 후 이시간 지나서는 다시 로그인을 시키던가 인증받도록 하면 됨</p> 
<br></br>
<br></br>
<p>====== sms 인증번호 받기 100% ========</p>
<form method="post" action="/api/sms/sms_auth">
 method="post" action="/api/sms/sms_auth"
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> </p> 
    <p>device_id : <input type="text" value='device_id2' name="device_id" />	필수		UUID</p> 
    <p>app_type : <input type="text" value='A' name="app_type" />필수	A/I	A : 안드로이드, I:아이폰  (영문은 대문자)</p> 

    <!-- <p>type : <input type="text" value='sms' name="type" /></p>  -->

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>-는 넣어도 되고 안넣어도 빼고 들어간다.</p>
<br></br>
<br><br>
<p>====== 회원 중복체크 ========</p>
<form method="post" action="/driver/member/check_dup"> 
/driver/member/check_dup <br>

    <p>device_id : <input type="text" value="device_id2" name="device_id" /></p> 
    <p>app_type : <input type="text" value="A" name="app_type" /></p> 

    <p>where : <input type="text" value="" name="where" /></p> user_id, phone  2개의 필드에서 검색
    <p>value : <input type="text" value="" name="value" /></p> 전화번호는 - 넣어도 안넣어도 검색이 됨
    <p><input type="submit" value="입력" /></p> 
</form> 

<p>비 가입회원이기 때문에 INPUT으로 정보 받아야 함.</p>
<br></br>
<br></br>

<p>====== 등록  ========</p>
<form method="post" action="/driver/member/join">
/driver/member/join
    <p>phone : <input type="text" value='010-5896-5938' name="phone" /> 중복안됨</p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>device_id : <input type="text" value='device_id2' name="device_id" />중복안됨</p>
    <p>push_token : <input type="text" value='push_token2' name="push_token" /> 중복안됨</p> 

    <p>sms_check : <input type="text" value='Y' name="sms_check" />SMS 인증받은 후 Y</p> 
    <p>age : <input type="text" value='10' name="age" />연령대</p> 
    <p>user_type : <input type="text" value='D' name="user_type" /></p> 
    <p>user_name : <input type="text" value='김이박' name="user_name" />실명</p> 
    <p>gender : <input type="text" value='M' name="gender" />M, F</p> 
    <p>birth : <input type="text" value='1111-11-11' name="birth" />1111-11-11</p> 
    <p>email : <input type="text" value='aseded' name="email" />email</p>  
    <p>addr1 : <input type="text" value='김이박' name="addr1" />차고지 주소</p> 
    <p>addr2 : <input type="text" value='김이박' name="addr2" />차고지추가 주소</p> 
    <p>car_num : <input type="text" value='서울123가1234' name="car_num" /></p> 
    <p>mycar : <input type="text" value='Y' name="mycar" />Y,N</p> 
    <p>car_type : <input type="text" value='aseded' name="car_type" />차종, 연식,유종</p>  
    <p>career : <input type="text" value='11' name="career" />운전경력</p> 
    <p>allergy : <input type="text" value='Y' name="allergy" />동물알러지여부 Y,N</p> 
    <p>atp_num : <input type="text" value='M' name="atp_num" />동물운송업 등록번호</p> 
    <p>ibrc_num : <input type="text" value='Y' name="ibrc_num" />개인사업자 등록번호</p> 
    <p>driver_num : <input type="text" value='M' name="driver_num" />운전면허 번호</p> 
    <p>driver_security : <input type="text" value='Y' name="driver_security" />운전자 보험번호</p> 
    <p>bank_name : <input type="text" value='기업은행' name="bank_name" /></p> 
    <p>bank_account : <input type="text" value='1254-25-2644' name="bank_account" /></p> 
    <p>comment : <input type="text" value='comment' name="comment" />Y,N</p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>login_type=  드라이버는 id로만</p>
<p> user_type=D: 기사, U:일반 사용자,</p>
<br><br>
<br><br>

<p>====== id passwd 로그인 ========</p>
<form method="post" action="/driver/member/login">
/driver/member/login
    <p>user_id : <input type="text" value='' name="user_id" /></p> 
    <p>passwd : <input type="text" value='123456' name="passwd" /></p> 
    <p>device_id : <input type="text" value='device_id2' name="device_id" /></p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>login_type : <input type="text" value='id' name="login_type" /></p> 
    <p>user_type : <input type="text" value='D' name="user_type" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<p>login_type= 로그인 타입 id',
  user_type=D: 기사, U:일반 사용자
</p>
<br><br>
<br><br>




<p>====== 자동 로그인 ========</p>
<form method="post" action="/driver/member/login">
/driver/member/login
  <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  <p>login_type :  idx<input type="text" value='idx' name="login_type" /></p> 
  <p>user_type : <input type="text" value='D' name="user_type" /></p> 

  <p><input type="submit" value="입력" /></p> 
</form> 
<p>login_type= 로그인 타입 idx',
  user_type=D: 기사, U:일반 사용자
</p>
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
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>

</p>====== 내프로필 이미지 삭제 ========</p>
<form method="post" action="/api/upload/delete_member_img"> 
/api/upload/delete_member_img
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 운전면허증 이미지 등록 ========</p>
<form method="POST" action="/api/upload/driver_license_img" enctype="multipart/form-data"> 
/api/upload/driver_license_img
    <p>
        단일 파일 업로드
        <input type="file" name="single_file" /> 
    </p>
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p>
    <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>

<p>====== 운전면허증 이미지 삭제 ========</p>
<form method="post" action="/api/upload/delete_driver_license_img"> 
/api/upload/delete_driver_license_img
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 통장 이미지 등록 ========</p>
<form method="POST" action="/api/upload/driver_bank_img" enctype="multipart/form-data"> 
/api/upload/driver_bank_img
    <p>
        단일 파일 업로드
        <input type="file" name="single_file" /> 
    </p>
    <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="user_idx" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <input type="submit" value="입력" />
</form>
<br><br>
<br><br>

<p>====== 통장 이미지 삭제 ========</p>
<form method="post" action="/api/upload/delete_driver_bank_img"> 
/api/upload/delete_driver_bank_img
  <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 드라이버 정보 요청 ========</p>
<form method="post" action="/driver/member/info_view"> 
/driver/member/info_view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p>  
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 내정보 수정 ========</p>
<form method="post" action="/driver/member/info_update"> 
/driver/member/info_update
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

 <p>phone : <input type="text" value='010-5896-5938' name="phone" /> 중복안됨</p> 
    <p>app_type : <input type="text" value='A' name="app_type" /></p> 
    <p>device_id : <input type="text" value='device_id2' name="device_id" />중복안됨</p>
    <p>push_token : <input type="text" value='push_token2' name="push_token" /> 중복안됨</p>     

    <p>passwd : <input type="text" value='1234' name="passwd" />passwd</p>  
    <p>email : <input type="text" value='aseded' name="email" />email</p>  
    <p>addr1 : <input type="text" value='서울시 영등포구' name="addr1" />차고지 주소</p> 
    <p>addr2 : <input type="text" value='김이박' name="addr2" />차고지추가 주소</p> 
    <p>car_num : <input type="text" value='서울시 영등포구' name="car_num" /></p> 
    <p>mycar : <input type="text" value='Y' name="mycar" />Y,N</p> 
    <p>car_type : <input type="text" value='aseded' name="car_type" />차종, 연식,유종</p>  
    <p>career : <input type="text" value='11' name="career" />운전경력</p> 
    <p>allergy : <input type="text" value='Y' name="allergy" />동물알러지여부 Y,N</p> 
    <p>atp_num : <input type="text" value='M' name="atp_num" />동물운송업 등록번호</p> 
    <p>ibrc_num : <input type="text" value='Y' name="ibrc_num" />개인사업자 등록번호</p> 
    <p>driver_num : <input type="text" value='M' name="driver_num" />운전면허 번호</p> 
    <p>driver_security : <input type="text" value='Y' name="driver_security" />운전자 보험번호</p> 
    <p>bank_name : <input type="text" value='기업은행' name="bank_name" /></p> 
    <p>bank_account : <input type="text" value='1254-25-2644' name="bank_account" /></p> 
    <p>comment : <input type="text" value='comment' name="comment" /></p> 
    <p><input type="submit" value="입력" /></p> 

</form> 
<br><br>
<br><br>

<p>====== 탈퇴 ========</p>
<form method="post" action="/driver/member/delete"> 
/driver/member/delete
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  <p>agreed : Y,N <input type="text" value='Y' name="agreed" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>임의 탈퇴는 안됨. 정산문제로 관리자가 승인해야 탈퇴처리됨. 신청 후부터는 사용 불가.</p>
<br><br>
<br><br>


<p> ====== 호출 전체보기 22.05.11 수정 ======== </p> 
<form method="post" action="/driver/transport/list"> 
/driver/transport/list
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

  <p>loc_lon : <input type="text" value="126.98749271" name="loc_lon" /></p> 
  <p>loc_lat : <input type="text" value="37.55642775" name="loc_lat" /></p> 
  <p>dist : 5000->5km,  10000->10km <input type="text" value="10000" name="dist" /></p> 
  <p>order : 전체,운행거리=dist,예상요금순=e_fee, 최신순= tr_idx <input  value="" name="order"/></p> 
  <p>by : 내림차순(큰->작은) desc, 오름순(작은->큰) asc <input  value="" name="by"/></p> 
  <p>page : 페이지 1 <input  value="" name="page"/></p>  
  <p>perpage : 페이지당 10개 <input  value="10" name="perpage"/></p>   
  <p><input type="submit" value="입력" /></p> 
</form> 
<br># TMap 좌표 기준 현재 좌표를 기준으로 주문 리스트를 가져온다.<br>
<br>1페이지당 10개씩<br>

<br><br>
<br><br>
<p> ====== 내 호출 리스트 ======== </p> 
<form method="post" action="/driver/transport/mylist"> 
/driver/transport/mylist
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  <p>order : 전체,운행거리=dist,예상요금순=e_fee, 최신순= tr_idx <input  value="" name="order"/></p> 
  <p>by : 내림차순(큰->작은) desc, 오름순(작은->큰) asc <input  value="" name="by"/></p> 
  <p>page : 페이지 1 <input  value="" name="page"/></p>  
  <p>perpage : 페이지당 10개 <input  value="10" name="perpage"/></p>  
  <p><input type="submit" value="입력" /></p> 
</form> 
<p># 나에게 배정된 호출 전체를 가져온다...</p>
<p># 예약, 실시간은 구분짓는다.</p>
<br><br>
<br><br>

<p>====== 주문 보기 ========</p>
<form method="post" action="/driver/transport/view"> 
/driver/transport/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  <p>tr_idx : 호출 고유번호 <input type="text" value="11" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>

<p>====== 주문 승낙 ========</p>
<form method="post" action="/driver/transport/accept"> 
/driver/transport/accept
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
  <p>loc_now_lon : <input type="text" value="126.9850380932383" name="loc_now_lon" /></p> 
  <p>loc_now_lat : <input type="text" value="37.566567545861645" name="loc_now_lat" /></p> 
  <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 출발지 도착 예정시간 전송 ========</p>
<form method="post" action="/driver/transport/send_eta"> 
/driver/transport/send_eta
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>eta : 도착시간 <input type="text" value="" name="eta" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 주문 취소는 정책상 구현 안하는게 맞는것 같음 ========</p>
<form method="post" action="/driver/transport/cancel"> 
/driver/transport/cancel
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>reason : 사유 <input type="text" value="" name="reason" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 출발지 도착알림 ========</p>
<form method="post" action="/driver/transport/change_status"> 
/driver/transport/change_status
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>status : <input type="text" value="A" name="status" /></p>
    //현재 상태. W:대기,G:기사배정  M: 출발지로 이동중, A: 출발지 도착, D:운행중, C: 운행취소, E:운행완료 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 운행시작 ========</p>
<form method="post" action="/driver/transport/start"> 
/driver/transport/start
 <p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p><span style="color:red"> ====== 운행 중 정보 저장 (위치) 22.05.04 수정 ======== </span></p> 
<form method="post" action="/driver/transport/driver_loc"> 
/driver/transport/driver_loc
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

  <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
  <p>loc_lon_now : 현재 위도<input type="text" value="126.8926674311066449" name="loc_long_now" /></p> 
  <p>loc_lat_now : 현재 경도<input type="text" value="37.4805375841442014" name="loc_lat_now" /></p> 
  <p>fee : 현재 금액<input type="text" value="" name="fee" /></p> 
  <p>device_status : 통신상태 <input type="text" value="" name="device_status" /></p> 
  <p>meters : 현재 주행거리 <input type="text" value="" name="meters" /></p> 
  <p>minutes : 현재 운행시간<input type="text" value="" name="minutes" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<p>아이폰 위도 경도 임</p>
<br>device_status 1. 미터기 정상 운행 상태 / 2. 미터기 운행 대기 상태 (10분 후 자동 정상 운행으로 전환) / 3. 미터기 오류 상황(스캐너 장비 무응답) / 4. 미터기 수동 운행 상태<br>
<br><br>



<br><br>
<p>====== 도착지 도착 ========</p>
<form method="post" action="/driver/transport/arrived"> 
/driver/transport/arrived
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>loc_lon_now : 현재 위도<input type="text" value="126.8926674311066449" name="loc_long_now" /></p> 
  <p>loc_lat_now : 현재 경도<input type="text" value="37.4805375841442014" name="loc_lat_now" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== 추가요금 입력 ========</p>
<form method="post" action="/driver/transport/add_charge"> 
/driver/transport/add_charge
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : 호출번호<input type="text" value="" name="tr_idx" /></p> 
    <p>a_fee_memo : 사유<input type="text" value="" name="a_fee_memo" /></p> 
    <p>a_fee : 금액<input type="text" value="" name="a_fee" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 할인요금 입력 ========</p>
<form method="post" action="/driver/transport/dis_charge"> 
/driver/transport/dis_charge
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : 호출번호<input type="text" value="" name="tr_idx" /></p> 
    <p>dc_fee_memo : 사유<input type="text" value="" name="dc_fee_memo" /></p> 
    <p>dc_fee : 금액<input type="text" value="" name="dc_fee" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>



<p> ====== 결제요청정보 ======== </p> 
<form method="post" action=/driver/payment/prepaylist"> 
/driver/payment/prepaylist
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


<p>====== 현금 결제요청 ========</p>
<form method="post" action="/driver/payment/paybycash"> 
/driver/payment/paybycash
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


    <div>        
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<br><br>
<p> = 운행 내역  22-04-12 신규 = </p> 
<form method="post" action="/driver/transport/endlist"> 
/driver/transport/endlist
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p>====== board list ========</p>
<form method="post" action="/driver/board/list"> 
/driver/board/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>board : notice, fna, fqna <input type="text" value="notice" name="board" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p> ====== board 보기 ======== </p>
<form method="post" action="/driver/board/view"> 
/driver/board/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>board : notice, fna, fqna <input type="text" value="notice" name="board" /></p> 


    <p>idx : <input type="text" value="1" name="idx" /></p> 
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
<form method="post" action="/driver/chat/send"> 
/driver/chat/send
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p> 
    <p>access_token : <input type="text" value="" name="access_data[access_token]" style="width:400px;"/></p> 
    <p>device_id : <input type="text" value="device_id" name="access_data[device_id]" style="width:400px;" /></p> 
    <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>to_idx : 이용자 to_idx <input type="text" value="" name="to_idx" /></p> 
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



<p> ====== 포인트 리스트 22.05.02 신규 ======== </p> 
<form method="post" action="/driver/point/list"> 
/driver/point/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

  <p>order : 전체,일자 = created_at <input  value="" name="order"/></p> 
  <p>by : 내림차순(큰->작은) desc, 오름순(작은->큰) asc <input  value="" name="by"/></p> 
  <p>page : 페이지 1 <input  value="1" name="page"/></p> 
  <p>perpage : 페이지당 리스트 갯수 10 <input  value="10" name="perpage"/></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>

<p> ====== 포인트 보기 22.05.02 신규 ======== </p> 
<form method="post" action="/driver/point/view"> 
/driver/point/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 


    <p>po_idx : <input type="text" value="" name="po_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>





















<p>================================================</p>
여기까지 TEST 완료  22년 3월 23일에 수정됨
<p>================================================</p>
<br><br>










<p>====== 결제요청 ========</p>
<form method="post" action="/driver/payment/ask"> 
/driver/payment/ask
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>tr_idx : <input type="text" value="" name="tr_idx" /></p> 
    <p>amount : 금액 <input type="text" value="" name="amount" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 결제취소 ========</p>
<form method="post" action="/driver/payment/cancel"> 
/driver/payment/cancel
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p>payment_idx : <input type="text" value="" name="payment_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 결제 리스트 ========</p>
<form method="post" action="/driver/payment/list"> 
/driver/payment/list
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 

    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>


<p>====== 결제 보기 ========</p>
<form method="post" action="/driver/payment/view"> 
/driver/payment/view
<p>user_idx : <input type="text" value="" name="access_data[user_idx]" /></p>  
  <p>access_token : <input type="text" value="" name="access_data[access_token]" /></p> 
  <p>device_id : <input type="text" value="device_id2" name="access_data[device_id]" /></p> 
  <p>app_type : <input type="text" value="A" name="access_data[app_type]" /></p> 


    <p>payment_idx : <input type="text" value="" name="payment_idx" /></p> 
    <p><input type="submit" value="입력" /></p> 
</form> 
<br><br>
<br><br>











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




    
<p>fee_total : <input type="text" value='aseded' name="email" /></p>  
    <p>fee_current : <input type="text" value='김이박' name="addr1" /></p> 
    <p>exchange_total : <input type="text" value='김이박' name="addr2" /></p> 
