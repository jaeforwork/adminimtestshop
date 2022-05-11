<?php
namespace App\Controllers\Member;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

//use App\Models\DriverModel;
use App\Models\MemberModel;
//use App\Models\TransportModel;
//use App\Libraries\ValidChecker;
//use App\Libraries\Sms;

class Certify extends BaseController {
  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();       
  }

  //Access_token 발생
  public function getcode() {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 

    if(!$USER_IDX || $USER_IDX=='') {
      ajaxReturn(RESULT_FAIL,"사용자정보가 없음","");      
      return;
    }

    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();
  
    //회원정보에서 기존값이 맞는지 확인
    $builder->select('user.USER_ID, user.PHONE, user.USER_TYPE, user.NICK_NAME, user.IMAGE, user.ACCESS_TOKEN, user.REFRESH_TOKEN, user.REFRESH_TOKEN_EXPIRED_DATE');
    $builder->where('user.USER_IDX', $USER_IDX);  

    $data['member'] = $builder->get()->getResult('array');   
    $CREATED_AT = date('Y-m-d H:i:s');

    // print_r($data['member'][0]['REFRESH_TOKEN_EXPIRED_DATE']);
    // exit;

    if($REFRESH_TOKEN==$data['member'][0]['REFRESH_TOKEN'] && $data['member'][0]['REFRESH_TOKEN_EXPIRED_DATE'] >= $CREATED_AT) {

    } else {
      ajaxReturn(RESULT_FAIL,"기존 정보와 맞지 않아 처리가 되지 못했습니다.","");      
      return;
    }  

    if(!$total || $total==0) {
      ajaxReturn(RESULT_FAIL,"사용자정보가 없음","");      
      return;
    }

    $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
    $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
    $ACCESS_TOKEN=access_token();
    $REFRESH_TOKEN=access_token();
    $UPDATED_AT = date('Y-m-d H:i:s');
   
    //회원정보에 넣을 것
    $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'UPDATED_AT'=>$UPDATED_AT,];

    $ReturnData = ['USER_IDX'=>$USER_IDX,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE];

   // $json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
    $this->db->transBegin();
    $builder = $this->db->table("MEMBER as member");   
    $builder->where('member.USER_IDX', $USER_IDX );
    $result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='데이타 업데이트 오류';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    } else {
      $this->db->transCommit();          
      $message='성공';
      ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);
      return;
    }   

  }


  
  //Access_token update
  public function update_code() {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 

    if(!$USER_IDX || $USER_IDX=='') {
      ajaxReturn(RESULT_FAIL,"사용자정보가 없음","");      
      return;
    }  

    //회원정보에서 기존값이 맞는지 확인

    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    //select
    $builder->select('user.USER_ID, user.PHONE,user.USER_TYPE,user.NICK_NAME,user.IMAGE');
    $builder->where('user.USER_IDX', $USER_IDX);  

    $data['member'] = $builder->get()->getResult('array');   

    // print_r($data['member'][0]['ACCESS_TOKEN']);
    // exit;
      
    $access_token   = access_token();
    $refresh_token  = access_token();


    $token_expired_date=ddayTimeNum('',1,'');
    $token_expired_date=date("Y-m-d H:i:s", $token_expired_date);

    $refresh_token_expired_date=ddayTimeNum('',30,'');
    $refresh_token_expired_date=date("Y-m-d H:i:s", $refresh_token_expired_date);
   // $data=array("user_idx"=>$USER_IDX,"access_token"=>$access_token,"expired_date"=>$expired_date);
 
   
    //회원정보에 넣을 것
    $newData = ['ACCESS_TOKEN'=>$access_token,'TOKEN_EXPIRED_DATE'=>$token_expired_date,'REFRESH_TOKEN'=>$refresh_token,'REFRESH_TOKEN_EXPIRED_DATE'=>$refresh_token_expired_date,];

    $json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
    
    $std = new MemberModel();
    $std->transBegin();

    $std->update($USER_IDX,$newData);
   
    if ($std->transStatus() === FALSE) {
      $std->transRollback();
      ajaxReturn(RESULT_FAIL,"데이타 업데이트 오류","");
      return;
    } else {
      $std->transCommit();
      ajaxReturn(RESULT_SUCCESS,"성공",$json_data);
      return;
    }

  }


   
  //SMS 인증확인
	public function sms_auth_check() {
    $request = \Config\Services::request();

    $headers = apache_request_headers();
    foreach ($headers as $header => $value) {     
      //if($header=='User-Agent'){
        echo "$header: $value <br />";
     // }
    }


    $auth_no    = esc($request->getPost('auth_no'));  
    $auth_ok    = esc($request->getPost('auth_ok'));  
    $phone      = esc($request->getPost('phone'));  
    $device_id  = esc($request->getPost('device_id'));  

    if(!$auth_ok || $auth_ok=='' || $auth_ok=='N') {
      ajaxReturn(RESULT_FAIL,"auth_ok 오류","");      
      return;
    }

		

	}
  
  
  
  //이메일 인증
	public function email($mb_id, $mb_md5) {
	
	}

}
?>