## 프로그래밍 정보 ##  
api.imtest.shop
api.petgle.com 테스트용

211.110.64.66
사무실 IP - 14.36.46.90  121.65.132.178

유지보수를 위해 최대한 CI기본 구조를 유지하며 작업
MVC 패턴 유지. Model구조 유지.
내부적으로 사용하는 변수와 입력받는 데이타의 변수는 소문자(단, DB직접찾는 array는 대문자로 받는다.), MYSQL관련은 대문자
## 프로그래밍 정보 ##  

## 임시로 ##  
    //사무실 IP에서만 보이도록
    if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
      echo "--> 사무실 IP에서만 보임 (디버깅)<br>";
      echo "접속을 막는 IP result <br>";
      print_r($result);
      echo "<br>";
      echo $currentAgent;
      echo "<br>";
      echo $agent->getPlatform(); // Platform info (Windows, Linux, Mac, etc.)
      echo "<br>";
      echo "사무실 IP에서만 보임 (디버깅) <--<br>";    
      echo "<br>";
    } 

<p><span style="color:red">  </span></p> 

  $TR_IDX   = esc($request->getPost('tr_idx'));

  BIGINT(20)  TR_IDX  IDX
  CURRENT_TIMESTAMP()

  $receiver = is_hp($receiver); 
  $receiver = get_hp($receiver,'-'); 
  
  ajaxReturn(RESULT_EMPTY,"자료가 없습니다.",$NewData);
  ajaxReturn(RESULT_FAIL,"보드선택오류 입니다.",$NewData);
  ajaxReturn(RESULT_SUCCESS,"보드선택오류 입니다.",$NewData);

  ajaxReturn(RESULT_SUCCESS,"",$ReturnData);  
  return;       

  $NewData  = array(
    'USER_IDX'  => $USER_IDX,
    'CP_START'  => $CP_START,
    'CP_END'    => $CP_END,
    'UPDATED_AT'  => $UPDATED_AT
  );

  $ReturnData = array(
    'CP_START'    => $CP_START,
    'CP_END'      => $CP_END
  );
      
  //TOKEN_EXPIRED_DATE 를 지금부터 한달 후로 연장한다.
  $CREATED_AT = date('Y-m-d H:i:s');
  $UPDATED_AT = date('Y-m-d H:i:s');
  $DELETED_AT = date('Y-m-d H:i:s');
  $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+1 days',strtotime($CREATED_AT)));
  $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));

  
  $Log_db_error_NewData  = array(
    'TABLE_NAME'  => 'table',
    'URL'         => current_url(),
    'ERROR'       => 'ERROR',
    'IP'          => $_SERVER['REMOTE_ADDR'],
    'UPDATED_AT'  => ''
  );

  $Log_db_error_ReturnData  = array(
    'TABLE_NAME'  => 'table',
    'URL'         => current_url(),
    'ERROR'       => 'ERROR',
    'IP'          => $_SERVER['REMOTE_ADDR'],
    'UPDATED_AT'  => ''
  );

  $Log_db_error_std = new Log_db_error_Model();
  $Log_db_error_std->insert($Log_db_error_NewData);
  $Log_db_error_std->transCommit();
  $Log_db_error_IDX = $Log_db_error_std->getInsertID();
  //print_r($Log_db_error_IDX);

## 임시로 ##  

## 작업리스트 ##  

- VISIT 통계 구현
- 테이블전체 FOREIGN_KEY 설정
  TRANSPORT(TR_IDX), TRANSPORT_END(TR_IDX), MEMBER(USER_IDX), PAYMENT(TR_IDX)
  fk_본테이블명_참조키테이블명_참조키 (예 :  fk_MEMBER_CONFIG_MEMBER_USER_IDX )
- 소스 정리(변수정리, 순서정리, 통일화)
- common_helper.php function 가나다 순으로 정렬
- 관리자 페이지 초안잡기
- DB 대소문자 구별 위한 세팅 정리 UTF8BIN
- ** 도큐먼트 만들기 ** 
- ADMIN_IDX 로 통일화

- HTTPS 적용 API. PAY. 
- 050 통화정보 적용 API 연결
- 애플 로그인처리 구현
- 포인트 쿠폰 내소식 
  상태값
- 회원 게스트 사용->카톡로그인 탈퇴 ->재가입 확인 
- 이벤트 목록 , 보기 구현 

- 카드 승인 취소 부분취소 추가승인등 구현
- 드라이버 정산 페이지
- cctv record url 테스트
- 드라이버의 상태가 S 인 경우만 승락등이 이루어지게 업데이트 할 것
  오류메세지는 현재 상태를 보여준다.
- 주변 추천 GPS 정보 넣기



## 22.05.14 ##
## 22.05.13 ##
## 22.05.12 ##
  - GPS 테스트(위치정보 받기)

## 22.05.11 ##

  - 결제 완료시 푸쉬
- Entity 전체 삭제
- /driver/transport/list dist 추가 : 검색 거리 T-map과 거의 차의가 없음(확인 완료)
- /driver/member/info_view  // if($header  == 'refresh_token') { 주석처리
- /app/controllers/Driver.php 파일 삭제. 같은 이름 폴더로 오류(development mode 에서만 오류가 나서 몰랐음)
- driver/Transport.php list의 gps를 이용한 거리와 T-Map의 거리 비교. sql문 수정 

## 22.05.10 ##
- BaseController.php 차단 ip설정 완료
- BaseController.php Visit 테이블에 로그 쌓기
- 현금 결제 구현
- cctv 상태를 볼수 있게 log에 넣기
- 사용자가 호출 시 쿠폰 정보(쿠폰번호) 넣게 추가
- config/Constants.php define("TIME_TODAY", date('Y-m-d 00:00:00')); 추가
- esc($request->getPost('access_data'));  post 된것들 esc 할 것 

## 22.05.09 ##
- user/transport/cancel  // 예약금은 기사의 수입으로 입력한다     //사용된 쿠폰은 원위치 시킨다.
- user/transport/view  쿠폰 정보 보이기로 수정
- user/transport/order  쿠폰 정보 넣기로 수정
- 사용자 내정보 수정에서 업데이트 하기  /user/member/info_update 
- user/transport mylist driver정보 가져오기 업데이트 status에서 오류
- test_user.php, api 메뉴얼 정리

## 22.05.08 ##
## 22.05.07 ##
## 22.05.06 ##
- /user/card/bill 결제페이지 구성 중
- LOG_DB_ERROR 생성, model 구성완료
- /Model/Log_cctv_Model.php 모델파일 이름 타입 변경 _Model.php 로

## 22.05.05 ##
- typescript test 타입들 정리

## 22.05.04 ##
- LOG_LOCATION TABLE DEVICE_STATUS 추가
  미터기 정상 운행 상태 -> 미터기 운행 대기 상태(10분 후 자동 정상 운행으로 전환) / 미터기 오류 상황(스캐너 장비 무응답) / 미터기 수동 운행 상태
- 쿠폰리스트(남은 기간  일) 추가
- vs code typescript 적용
- apache_request_headers 전체 페이지에 추가(테스트 아직 못함)
- Retun -> Return 으로 철자오류 수정 

## 22.05.03 ##
## 22.05.02 ##
- Sample_Entity_Model 표준화작업
- Log_admin_page_viewModel.php, table 생성
- MEMBER_COUPON -> CUSER_IDX -> ADMIN_IDX
- 드라이버 호출 리스트 중 (전체,운행거리,요금순 옵션 만들기) 운행거리는 가까운 운행거리, 먼 운행거리로
- 테이블 FOREIGN_KEY 설정 테스트
- DB 백업

## 22.05.01 ##
## 22.04.30 ##
## 22.04.29 ##
- db int(11) ->bigint(20 unsigned로 수정)
- BaseController 방문자 통계를 위한 기초 작업 수정 
- config/Constants.php 시간정의 추가(TIME_YMDHIS, TIME_YMD, TIME_HIS) 및 정리
- 디버깅을 위한 소스 추가 -> 사무실 IP에서만 보임 (디버깅)
- 드라이버 완료 운행정보에 추가 (유저 리뷰에서)
- 드라이버 호출 리스트, 내호출, 호출 보기내에 사용자 정보, 펫정보 오류 수정

## 22.04.28 ##
- 펫 종류에 개 고양이 기타(기타 추가할 것)
- 사용자의 실시간 요금보기 API (주행거리, 미터기 요금, 주행시간, 평균속도)
- App\Libraries\ValidChecker common_helper.php로 이동 후 /user/Member.php, pet.php, review.php 소스 수정
- App\Libraries 에서 db 사용 가능한지 확인
- T-map 좌표로 거리 가져오기 완료 distance()
- T-map 좌표로 주소 가져오기 완료 geotoadd()
- App\Libraries\Tmap 업데이트(geotoadd 추가, request 수정)
- 드라이버 public function delete() { 업데이트해야함
- DB 백업(신규적용 DB 저장을 위해)

## 22.04.27 ##
- 채팅 구현 (푸쉬포함)
- /user/pet/put 소스 정리(변수정리, 순서정리, 통일화)
- /user/payment/askpay 조르기 결제 링크. 결제 URL (조르기용 API)
- 사용자 쿠폰등록 api

## 22.04.26 ##
- /user/card/prebill 결제 전 전체 정보 받기(쿠폰, 포인트 등) 결제전 전체 리스트 API
- 드라이버정보 업데이트 controller 수정   public function info_update() {
- APP_PUSH_MESSAGES 업데이트 하기 TR_IDX 추가
- 카톡 알림톡 템플렛 추가
- CCTV 시작 종료 구성 완료

## 22.04.25 ##
- App\Libraries\Pushnoti 에 추가
  $url      = $dataMessage['url'];
  $user_img_url    = $dataMessage['user_img_url'];
  $driver_img_url    = $dataMessage['driver_img_url'];
- /User/Transport/ push 처리 추가및 정리 APP_PUSH_MESSAGES 에 넣기 추가
- 이미지 등록시 기존 이미지 삭제처리(pet, driver,user 전체)
- 목적지 도착시 도착시간등 업데이트, 푸쉬처리
- 카카오톡, 전화번호 회원복구 오류 수정 

## 22.04.22 ##
- pet 수정
- pet 삭제(실제 삭제를 하면 운행내역에서 펫정보가 사라지게 되서 update STATUS='N'로 변경 )
  펫리스트에서 STATUS='Y'만 가져오게 변경
- 드라이버 Driver/Transport.php 주행시작시 CCTV자동 실행, 운행 종료시 자동 종료 추가
- 드라이버 Driver/Cct.php 추가 CCtv Test, 시작, 종료를 드라이버가 할 수 있게(테스트 시)
- 사용자 User/Cctv.php 추가 CCTV URL만 가져올때 사용할 수 있도록
- User/Coupon.php 푸쉬 모델 라이브러리 추가(기본 세팅만)
- TRANSPORT, TRANSPORT_END 에 IS_USER_SHOW 추가
- 방문 목록에서 삭제 구현 TRANSPORT_END 의 IS_USER_SHOW => N로 변경, LIST에서는 Y만 보이게 변경

## 22.04.21 ##
- Shop_listModel.php LOCATION->LOC로 변경(LOCATION 사용시 변수 중복이나 MYSQL function 중복 우려로)
- Recommend.php function shop_list 추가
- test_user.php에 Recommend 추가
- Entity Test 완료

## 22.04.20 ##
- 비즈니스 로직을 위한 Entity 구성 시작
- 오픈아이디 로그인 처리 추가(test_user.php, )
- MODEL내 전체 /app/model/*Model.php 변수 통일화 완료
- TOKEN_EXPIRED_DATE 30일로 REFRESH_TOKEN_EXPIRED_DATE 60일로 변경

## 22.04.19 ##
- system/helpers/text_helper.php의 random_string 가 적용된 부분을 common_helper.php의 generateRandomString 로 변경
- Api\User\Member.php is_hp 추가
- Api\Driver\Transport.php list 중 좌표값 2km 이내로 수정
- Api\User\Member.php  check_dup_openid recover 신규
  오픈아이디 중복체크, 회원복구
- Kakao_alrimtalk.php 기본 구성 만듬-> 템플렛 보고 디테일하게 만들어야 함
- App\Libraries\Sms.php msg_type 삭제
- readme.md 파일에 작업 내역 넣기 시작
- get_hp 수정
  get_hp($hp,1)이나 get_hp($hp,0)로 수정

## 초기설정 시 ##
  .env url 수정
  /app/Config/App.php  : URL 수정
  /app/Config/Database.php  : DB접속 정보 수정
  /app/Config/Routes.php  : Routes 정보 수정

  폴더 775 , 파일 755, write folder 707
## 초기설정 시 ##