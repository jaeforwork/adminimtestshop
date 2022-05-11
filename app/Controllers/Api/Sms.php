<?php
namespace App\Controllers\Api;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\SmsModel;

class Sms extends BaseController
{
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index()
  {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();  
  }

  public function kakao_auth() {  //단문자
    $request = \Config\Services::request();

    $receiver   = esc($request->getPost('phone'));
    $type       = esc($request->getPost('type'));
    // $receiver       = $_GET['receiver'];
    // $type           = $_GET['type'];
    
    // $receiver = '010-5896-5938';
    // $receiver = '010-1111-2222';
    $receiver = get_hp($receiver,0); //-를 뺌
    // echo"$receiver";
    // exit;

    if($receiver=='') { 
      $msg="수신자의 정보가 없습니다.";
      echo(json_encode(array("result" => 'F', "msg" => $msg))); 
      exit;
    }
    
    
    /****************** 인증정보 시작 ***//***************/
    $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
    $sms['user_id'] = "petgle"; // SMS 아이디
    $sms['key'] = "14u8my4yzy1sum6jpfy29iigihownon3";//인증키
    // /****************** 인증정보 끝 ********************/
    
    //count
    $builder = $this->db->table("SMS_HISTORY as sms_history");
    //$builder->select('user.*, member.*');
    $builder->select('sms_history.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('sms_history.SH_PHONE', $receiver);  
    // $where = "sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 24 HOUR)";
    // $builder->where($where);  
    $builder->where('sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 24 HOUR)');
    $total = $builder->countAllResults();
    
    if($total>4) {
      $msg="하루 인증한도 5회를 초과되었습니다.";
      echo(json_encode(array("result" => 'F', "msg" => $msg))); 
      exit;
    }

    //count
    $builder = $this->db->table("SMS_HISTORY as sms_history");
    //$builder->select('user.*, member.*');
    $builder->select('sms_history.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('sms_history.SH_PHONE', $receiver);  
    $builder->where('sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 5 MINUTE)');
    $total = $builder->countAllResults();

    if($total>0) {
      $msg="5분이 지난 후 다시 인증발송 할 수 있습니다.";
      echo(json_encode(array("result" => 'F', "msg" => $msg))); 
      exit;
    }
      
    $certi_num = rand(000000,999999);
    
    $_POST['msg']='[팻글택시] 인증번호는 '.$certi_num.' 입니다.';
    
    $_POST['receiver'] = $receiver; // 수신번호
    $_POST['destination'] = $receiver; // 수신인 %고객명% 치환
    // $_POST['destination'] = '01027349514|담당자'; // 수신인 %고객명% 치환
    $_POST['sender'] ="01092203333"; // 발신번호
    // $_POST['rdate'] = ''; // 예약일자 - 20161004 : 2016-10-04일기준
    // $_POST['rtime'] = ''; // 예약시간 - 1930 : 오후 7시30분    
    
    $sms['msg']         = stripslashes($_POST['msg']);
    $sms['receiver']    = $_POST['receiver'];
    $sms['destination'] = $_POST['destination'];
    $sms['sender']      = $_POST['sender'];
    // $sms['rdate'] = $_POST['rdate'];
    // $sms['rtime'] = $_POST['rtime'];
    $sms['testmode_yn'] = empty($_POST['testmode_yn']) ? '' : $_POST['testmode_yn'];
    //$sms['title'] = $_POST['subject'];
    $sms['title']       = '[팻글택시] 가입 인증번호';
    //$sms['msg_type'] = $_POST['msg_type'];
    //$sms['testmode_yn'] = 'Y';

    /*****/
    $host_info = explode("/", $sms_url);
    $port = $host_info[0] == 'https:' ? 443 : 80;
    
    $oCurl = curl_init();
    curl_setopt($oCurl, CURLOPT_PORT, $port);
    curl_setopt($oCurl, CURLOPT_URL, $sms_url);
    curl_setopt($oCurl, CURLOPT_POST, 1);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $ret = curl_exec($oCurl);
    curl_close($oCurl);
    
    //echo $ret;
    $retArr = json_decode($ret); // 결과배열
    
    //print_r($retArr);
    //exit;
    // $retArr = stdClass Object ( [result_code] => 1 [message] => success [msg_id] => 326140600 [success_cnt] => 1 [error_cnt] => 0 [msg_type] => SMS )





    if($retArr->result_code==1){

      $message  = $retArr->message;  // success;
      $msg_id  = $retArr->msg_id;  // 326140600;

      $success_cnt  = $retArr->success_cnt;  // success;
      $msg_type  = $retArr->msg_type;  // 326140600;
 
        $newData = ['SH_IDX'=>$msg_id,'TYPE'=>$msg_type,'SH_PHONE'=>$receiver,'SH_MEMO'=>$certi_num,];

        $std = new SmsModel();
        $std->transBegin();
        $result = $std->insert($newData);
       
        if ($std->transStatus() === FALSE) {
          $std->transRollback();
          $message='';
          echo(json_encode(array("result" => 'F', "msg" => $message))); 
          exit;
        } else {
          $std->transCommit();          
          $message=$certi_num;
          echo(json_encode(array("result" => 'Y', "msg" => $message))); 
          exit;
        }
      }
     else {
      $message='';
      echo(json_encode(array("result" => 'F', "msg" => $message))); 
      exit;
    }     




  }


  public function kako_auth_token() //카카오
  {

  /* 
  -----------------------------------------------------------------------------------
  알림톡 토큰 생성
  -----------------------------------------------------------------------------------
  API호출 URL의 유효시간을 결정하며 URL 의 구성중 "30"은 요청의 유효시간을 의미하며, "s"는 y(년), m(월), d(일), h(시), i(분), s(초) 중 하나이며 설정한 시간내에서만 토큰이 유효합니다.
  운영중이신 보안정책에 따라 토큰의 유효시간을 특정 기간만큼 지정할 경우 매번 호출할 필요없이 해당 유효시간내에 재사용 가능합니다.
  주의하실 점은 서버를 여러대 운영하실 경우 토큰은 서버정보를 포함하므로 각 서버에서 생성된 토큰 문자열을 사용하셔야 하며 토큰 문자열을 공유해서 사용하실 수 없습니다.
  */

  $_apiURL	  =	'https://kakaoapi.aligo.in/akv10/token/create/30/d/';
  $_hostInfo	=	parse_url($_apiURL);
  $_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables	=	array(
    'apikey' => '14u8my4yzy1sum6jpfy29iigihownon3',
    'userid' => 'petgle'
  );

  $oCurl = curl_init();
  curl_setopt($oCurl, CURLOPT_PORT, $_port);
  curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
  curl_setopt($oCurl, CURLOPT_POST, 1);
  curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
  curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

  $ret = curl_exec($oCurl);
  $error_msg = curl_error($oCurl);
  curl_close($oCurl);

  // 리턴 JSON 문자열 확인
  print_r($ret . PHP_EOL);

  // JSON 문자열 배열 변환
  $retArr = json_decode($ret);

  // 결과값 출력
  print_r($retArr);

  /*
  code : 0 성공, 나머지 숫자는 에러
  message : 결과 메시지
  */

// 12개월 22-03-29 생성
//2d4df759bedc654888b7ca3d7d7a0c46e935acb94c83332d906fce7865cfb01e5abdcaebee08fa69528ee39071461bd09d9780d66b4f0a866059b5065389ff89XhoJHOhoCah/ujD0GRnHqffk2CCeASAdd328wFtHNGwrjXEussf3yaXNx7j0QBn3e15ct5upMwyDsfvJG/uwzA==
  }

  public function sms_auth() { //카카오 
    // exit;
    $request = \Config\Services::request();
      
    $receiver   = esc($request->getPost('phone'));
    $TYPE       = esc($request->getPost('type'));
    // $receiver       = $_GET['receiver'];
    // $type           = $_GET['type'];
          
    //$receiver       ='01058965938';
    $certi_num = rand(000000,999999);

    $receiver = get_hp($receiver,1); 

    if($receiver=='') { 
      $msg="수신자의 정보가 없습니다.";
      ajaxReturn(RESULT_FAIL,$msg,"");
      return;     
    }

    //count
    $builder = $this->db->table("SMS_HISTORY as sms_history");
    //$builder->select('user.*, member.*');
    $builder->select('sms_history.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('sms_history.SH_PHONE', $receiver);  
    // $where = "sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 24 HOUR)";
    // $builder->where($where);  
    $builder->where('sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 24 HOUR)');
    $total = $builder->countAllResults();
    
    if($total>4) {
      $msg="하루 인증한도 5회를 초과되었습니다.";      
      ajaxReturn(RESULT_FAIL,$msg,"");
      return;  
    }

    //count
    $builder = $this->db->table("SMS_HISTORY as sms_history");
    //$builder->select('user.*, member.*');
    $builder->select('sms_history.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('sms_history.SH_PHONE', $receiver);  
    $builder->where('sms_history.CREATED_AT > SUBDATE(NOW(), INTERVAL 5 MINUTE)');
    $total = $builder->countAllResults();

    if($total>0) {
      $msg="5분이 지난 후 다시 인증발송 할 수 있습니다.";
      ajaxReturn(RESULT_FAIL,$msg,"");
      return;  
    }

  /* 
  -----------------------------------------------------------------------------------
  알림톡 전송
  -----------------------------------------------------------------------------------
  버튼의 경우 템플릿에 버튼이 있을때만 버튼 파라메더를 입력하셔야 합니다.
  버튼이 없는 템플릿인 경우 버튼 파라메더를 제외하시기 바랍니다.
  */

  $_apiURL    =	'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
  $_hostInfo  =	parse_url($_apiURL);
  $_port      =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables =	array(
    'apikey'      => '14u8my4yzy1sum6jpfy29iigihownon3', 
    'userid'      => 'petgle', 
    'token'       => '4e216e1fcb7dd5daf6e27806201ed61db768926e0e95b1c32d09f206a4039136d224ceb757efcf02fc63047c3464311b0ee2bd729d5be1c4ef91cafeac15b755knx8wU6NtpcUu3OHvrhj/avpBYk0lpasYdJmU1um0dVzwsEKbfn1IUGRIPmKIpKs5bEZTFESTmvu9SwiqtT89Q==', 
    'senderkey'   => 'a229b09c421f81c3de2c252b8a365a2b4f772b53', 
    'tpl_code'    => 'TH_8030',
    'sender'      => '01092203333',
    // 'senddate'    => date("YmdHis", strtotime("+7 minutes")),
   // 'senddate'    => date("YmdHis"),
    'receiver_1'  => $receiver,
    'recvname_1'  => $receiver,
    'subject_1'   => '팻글에서 보내드리는 회원가입 인증문자',
    'message_1'   => '안녕하세요. 고객님! [팻글택시] 회원가입을 위한 인증번호는
    '.$certi_num.' 입니다. 5분간 유효합니다. 하루 5번으로 제한되니 이점 양해바랍니다.
    [팻글택시]',
   // 'button_1'    => '{"button":[{"name":"테스트 버튼","linkType":"DS"}]}', // 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
    // 'receiver_2'  => '01058965938',
    // 'recvname_2'  => '01058965938 사용자 명2',
    // 'subject_2'   => '01058965938  제목2',
    // 'message_2'   => '안녕하세요. 고객명2님! 서비스 회원가입을 위한 인증번호는
    // 256346 입니다. 5분간 유효합니다. 하루 5번으로 제한되니 이점 양해바랍니다.
    // 회사명',
    //'button_2'    => '{"button":[{"name":"테스트 버튼","linkType":"DS"}]}' // 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
  );

  /*

  -----------------------------------------------------------------
  치환자 변수에 대한 처리
  -----------------------------------------------------------------

  등록된 템플릿이 "#{이름}님 안녕하세요?" 일경우
  실제 전송할 메세지 (message_x) 에 들어갈 메세지는
  "홍길동님 안녕하세요?" 입니다.

  카카오톡에서는 전문과 템플릿을 비교하여 치환자이외의 부분이 일치할 경우
  정상적인 메세지로 판단하여 발송처리 하는 관계로
  반드시 개행문자도 템플릿과 동일하게 작성하셔야 합니다.

  예제 : message_1 = "홍길동님 안녕하세요?"

  -----------------------------------------------------------------
  버튼타입이 WL일 경우 (웹링크)
  -----------------------------------------------------------------
  링크정보는 다음과 같으며 버튼도 치환변수를 사용할 수 있습니다.
  {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}]}

  -----------------------------------------------------------------
  버튼타입이 AL 일 경우 (앱링크)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"AL","linkI":"https://www.링크주소.com/?example=12345", "linkA": "https://www.링크주소.com/?example=12345"}]}

  -----------------------------------------------------------------
  버튼타입이 DS 일 경우 (배송조회)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"DS"}]}

  -----------------------------------------------------------------
  버튼타입이 BK 일 경우 (봇키워드)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"BK"}]}

  -----------------------------------------------------------------
  버튼타입이 MD 일 경우 (메세지 전달)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"MD"}]}

  -----------------------------------------------------------------
  버튼이 여러개 인경우 (WL + DS)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}, {"name":"버튼명","linkType":"DS"}]}

  */

  $oCurl = curl_init();
  curl_setopt($oCurl, CURLOPT_PORT, $_port);
  curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
  curl_setopt($oCurl, CURLOPT_POST, 1);
  curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
  curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

  $ret = curl_exec($oCurl);
  $error_msg = curl_error($oCurl);
  curl_close($oCurl);

  // 리턴 JSON 문자열 확인
//
  // JSON 문자열 배열 변환
  // $retArr = json_decode($ret);
  $retArr = json_decode($ret,true);
     
//     print_r($retArr);
//     echo "code <br>";
//     // exit;

// //stdClass Object ( [code] => 0 [message] => 성공적으로 전송요청 하였습니다. [info] => stdClass Object ( [type] => AT [mid] => 336962435 [current] => 49869.0 [unit] => 6.5 [total] => 6.5 [scnt] => 1 [fcnt] => 0 ) )

// print_r($retArr->code);
// echo "message <br>";
// print_r($retArr->message);
// echo "info <br>";
// print_r($retArr->info);
// echo "<br>";

//

//$retArr0 = json_decode($ret,true);
     
// print_r($retArr0);
// echo "code <br>";
// exit;

//stdClass Object ( [code] => 0 [message] => 성공적으로 전송요청 하였습니다. [info] => stdClass Object ( [type] => AT [mid] => 336962435 [current] => 49869.0 [unit] => 6.5 [total] => 6.5 [scnt] => 1 [fcnt] => 0 ) )

// print_r($retArr['code']);
// echo "message <br>";
// print_r($retArr['message']);
// echo "info <br>";
// print_r($retArr['info']);
// echo "<br>";
// print_r($retArr['info']['type']);
// echo "<br>";












    if($retArr['code']=="0") {
      $message  = $retArr['message'];  // success;
      $msg_id  = $retArr['info']['mid'];  // 326140600;

      $success_cnt  = $retArr['info']['scnt'];  // success;
      $msg_type     = "K";  // 326140600;
 
      $newData = ['SH_IDX'=>$msg_id,'TYPE'=>$msg_type,'SH_PHONE'=>$receiver,'SH_MEMO'=>$certi_num,];

      $std = new SmsModel();
      $std->transBegin();
      $result = $std->insert($newData);
       
      if($std->transStatus() === FALSE) {
        $std->transRollback();
        $message="roll back";
        ajaxReturn(RESULT_FAIL,$message,"");
        return;  
      } else {
        $std->transCommit();          
        $message=$certi_num;
        ajaxReturn(RESULT_SUCCESS,"",$certi_num);
        return;  
      }
    } else {
      $message="전송에 실패 하였습니다.";
      ajaxReturn(RESULT_FAIL,$message,"");
      return;  
    }     

    // // 결과값 출력
    // print_r($retArr);

    /*
    code : 0 성공, 나머지 숫자는 에러
    message : 결과 메시지
    */    
          
  }
      
}//class