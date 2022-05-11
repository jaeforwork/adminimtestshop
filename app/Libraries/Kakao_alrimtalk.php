<?php
namespace App\Libraries;

class Kakao_alrimtalk {
   
  private $api_key  = "14u8my4yzy1sum6jpfy29iigihownon3"; //인증키
  private $send_num = "01092203333";
  private $user_id  = "petgle"; // SMS 아이디

  // public function send_sms(string $to, string $message){
  public function send_sms($to, $data){

    $title    = $data['title'];
    $message  = $data['message'];
    $msg_type = $data['msg_type'];
    $template = $data['template'];
    
    $msg_type = 'kakao';

    $sms['user_id']     = $this->user_id; // SMS 아이디
    $sms['key']         = $this->api_key;//인증키
    $sms['msg']         = $message;
    $sms['receiver']    = $to;
    $sms['destination'] = '';
    $sms['sender']      = $this->send_num;
    $sms['rdate']       = '';
    $sms['rtime']       = '';
    $sms['testmode_yn'] = 'N';
    $sms['title']       = $title;
    $sms['msg_type']    = $msg_type;
      
    $oCurl = curl_init();
      
    curl_setopt($oCurl, CURLOPT_URL, "https://apis.aligo.in/send/");
    curl_setopt($oCurl, CURLOPT_PORT, 443);
    curl_setopt($oCurl, CURLOPT_POST, 1);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $ret = curl_exec($oCurl);
    curl_close($oCurl);
      
    //echo $ret;
    $retArr = json_decode($ret, true); // 결과배열
    if($retArr['result_code']==1){
      return $retArr;    
    }
    //print_r($retArr);
    return false;
    // print_r($retArr); // Response 출력 (연동작업시 확인용)
      
    /**** Response 항목 안내 ****
    // result_code : 전송성공유무 (성공:1 / 실패: -100 부터 -999)
    // message : success (성공시) / reserved (예약성공시) / 그외 (실패상세사유가 포함됩니다)
    // msg_id : 메세지 고유ID = 고유값을 반드시 기록해 놓으셔야 sms_list API를 통해 전화번호별 성공/실패 유무를 확인하실 수 있습니다
    // error_cnt : 에러갯수 = receiver 에 포함된 전화번호중 문자전송이 실패한 갯수
    // success_cnt : 성공갯수 = 이동통신사에 전송요청된 갯수
    // msg_type : 전송된 메세지 타입 = SMS / LMS / MMS (보내신 타입과 다른경우 로그로 기록하여 확인하셔야 합니다)
    /**** Response 예문 끝 ****/
  }

}