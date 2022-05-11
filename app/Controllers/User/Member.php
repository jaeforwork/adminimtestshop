<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\TransportModel;

use App\Libraries\ValidChecker;

class Member extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();       
  }

  public function check_join() { //중복확인
    $request = \Config\Services::request();

    $PHONE      = esc($request->getPost('phone'));
    $DEVICE_ID  = esc($request->getPost('device_id'));
    $APP_TYPE   = esc($request->getPost('app_type'));
    $USER_TYPE  = esc($request->getPost('user_type'));

    $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

    if($is_hp == FALSE) {
      $message='잘못된 전화번호 형식입니다.';
      ajaxReturn(RESULT_FAIL,$message,$PHONE);
      return;
    }
    $PHONE = get_hp($PHONE,1); //-넣음 
    
    //count
    $builder = $this->db->table("MEMBER");
    $builder->select('PHONE, DEVICE_ID, APP_TYPE, USER_TYPE');
    $builder->where('PHONE', $PHONE);
    $builder->where(['PHONE' => $PHONE,'DEVICE_ID' => $DEVICE_ID,'APP_TYPE' => $APP_TYPE,'USER_TYPE' => $USER_TYPE,]);
    $total = $builder->countAllResults();
  
    if($total>0) {
      $message='중복된 자료가 있습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$total);  
      return;
    } else {
      $message='중복된 자료가 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }
  }

  public function join() {
    $request = \Config\Services::request();
    $security = \Config\Services::security();   
  
    $PHONE      = esc($request->getPost('phone'));
    $APP_TYPE   = esc($request->getPost('app_type')); 
    $DEVICE_ID  = esc($request->getPost('device_id'));   
    $PUSH_TOKEN = esc($request->getPost('push_token'));
    $SMS_CHECK  = esc($request->getPost('sms_check'));  
    $LOGIN_TYPE = esc($request->getPost('login_type'));  
    $USER_TYPE  = esc($request->getPost('user_type'));    

    $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

    //넘어온 값들 확인
    //$ValidCheck = new ValidChecker();

    if($is_hp == FALSE) {
      $message='잘못된 전화번호 형식입니다.';
      ajaxReturn(RESULT_FAIL,$message,$PHONE);
      return;
    }
  
    if(!$SMS_CHECK && $SMS_CHECK!='Y') {        
      $message='가입인증 문자오류로 가입 처리도중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$SMS_CHECK);
    }    

    //$check = $ValidCheck->check_csfs($PET_NAME); 
    // $check = check_csfs($PET_NAME); 
    // if($check) {
    //   $message='특수문자 포함되서 가입 처리도중 오류가 발생했습니다.';
    //   ajaxReturn(RESULT_FAIL,$message,$PET_NAME);
    // }
    //넘어온 값들 확인

    $PHONE = get_hp($PHONE,1); //-넣음 
    // $STATUS               = esc($request->getPost('STATUS'));
    // $REFRESH_TOKEN        = esc($request->getPost('REFRESH_TOKEN'));
    // $JOIN_IP              = esc($request->getPost('JOIN_IP'));
    // $LOGIN_IP             = esc($request->getPost('LOGIN_IP'));
    
    // $JOIN_DATE            = esc($request->getPost('JOIN_DATE'));
    // $LOGIN_DATE           = esc($request->getPost('LOGIN_DATE'));

    // $AGREED_TERM_DATE     = esc($request->getPost('AGREED_TERM_DATE'));
    // $AGREED_PERSON_DATE   = esc($request->getPost('AGREED_PERSON_DATE'));
    // $TOKEN_EXPIRED_DATE   = esc($request->getPost('TOKEN_EXPIRED_DATE'));

    $CREATED_AT = date('Y-m-d H:i:s');
    $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
    $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
    $ACCESS_TOKEN=access_token();
    $REFRESH_TOKEN=access_token();

    $STATUS         = 'Y';
    $ACCESS_TOKEN   = $ACCESS_TOKEN;
    $REFRESH_TOKEN  = $REFRESH_TOKEN;
    $JOIN_IP        = $_SERVER['REMOTE_ADDR'];
    $LOGIN_IP       = $_SERVER['REMOTE_ADDR'];

    if($LOGIN_TYPE=='p') {
      $USER_ID="p_".$PHONE;
      
      $PASSWD = password_hash($PHONE, PASSWORD_BCRYPT); // (4)
      $NICK_NAME='NICK_'.$PHONE;

      $std = new MemberModel();
      $std->transBegin();

      $std->selectMax('USER_IDX');
      $query = $std->get();
      foreach ($query->getResult() as $row) { 
        $new_USER_IDX=$row->USER_IDX;       
      }
      $new_USER_IDX=$new_USER_IDX+1;
      // echo $new_USER_IDX;
      // exit;
      $newData = ['USER_IDX'=>$new_USER_IDX,'USER_ID'=>$USER_ID,'PHONE'=>$PHONE,'PASSWD'=>$PASSWD,'USER_TYPE'=>$USER_TYPE,'NICK_NAME'=>$NICK_NAME,'STATUS'=>'Y','DEVICE_ID'=>$DEVICE_ID,'APP_TYPE'=>$APP_TYPE,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'PUSH_TOKEN'=>$PUSH_TOKEN,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'JOIN_IP'=>$JOIN_IP,'LOGIN_IP'=>$LOGIN_IP,'LOGIN_DATE'=>$CREATED_AT,'AGREED_TERM_DATE'=>$CREATED_AT,'AGREED_PERSON_DATE'=>$CREATED_AT,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'LOGIN_TYPE'=>$LOGIN_TYPE,'APPROVED_AT'=>$CREATED_AT,'APPROVED_BY'=>'1',];

      // print_r($newData);
      // exit;

      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='가입 처리도중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,"");
      } else {
        $std->transCommit();
        $message= '정상적으로 가입이 되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$newData);  
      }




    } else if($LOGIN_TYPE=='id') {    
      $USER_ID    = esc($request->getPost('user_id'));
      $PASSWD     = esc($request->getPost('passwd'));
      $NICK_NAME  = esc($request->getPost('nick_name'));

      $PASSWD     = password_hash($PASSWD, PASSWORD_BCRYPT); 

      $std = new MemberModel();
      $std->transBegin();

      $std->selectMax('USER_IDX');
      $query = $std->get();
      foreach ($query->getResult() as $row) { 
        $new_USER_IDX=$row->USER_IDX;       
      }
      $new_USER_IDX=$new_USER_IDX+1;
      // echo $new_USER_IDX;
      // exit;
      $newData = ['USER_IDX'=>$new_USER_IDX,'USER_ID'=>$USER_ID,'PHONE'=>$PHONE,'PASSWD'=>$PASSWD,'USER_TYPE'=>$USER_TYPE,'NICK_NAME'=>$NICK_NAME,'STATUS'=>'Y','DEVICE_ID'=>$DEVICE_ID,'APP_TYPE'=>$APP_TYPE,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'PUSH_TOKEN'=>$PUSH_TOKEN,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'JOIN_IP'=>$JOIN_IP,'LOGIN_IP'=>$LOGIN_IP,'LOGIN_DATE'=>$CREATED_AT,'AGREED_TERM_DATE'=>$CREATED_AT,'AGREED_PERSON_DATE'=>$CREATED_AT,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'LOGIN_TYPE'=>$LOGIN_TYPE,'APPROVED_AT'=>$CREATED_AT,'APPROVED_BY'=>'1',];
      //print_r($newData);
      //exit;
      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='가입 처리도중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,'');
      } else {
        $std->transCommit();
        $message= '정상적으로 가입이 되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$newData);  
      }

    }

    else if($LOGIN_TYPE=='kakao' || $LOGIN_TYPE=='apple') {   
      $openid     = esc($request->getPost('openid'));
      $NICK_NAME  = esc($request->getPost('nick_name'));
      $EMAIL      = esc($request->getPost('email'));
      $AGE        = esc($request->getPost('age'));


      if($LOGIN_TYPE=='kakao') {
        $USER_ID='k_'.$openid;
      } else if($openid=='apple') {
        $LOGIN_TYPE='a_'.$openid;
      }
      $PASSWD = password_hash($PHONE, PASSWORD_BCRYPT); // (4)


      $std = new MemberModel();
      $std->transBegin();

      $std->selectMax('USER_IDX');
      $query = $std->get();
      foreach ($query->getResult() as $row) { 
        $new_USER_IDX=$row->USER_IDX;       
      }
      $new_USER_IDX=$new_USER_IDX+1;
      // echo $new_USER_IDX;
      // exit;
      $newData = ['USER_IDX'=>$new_USER_IDX,'USER_ID'=>$USER_ID,'PHONE'=>$PHONE,'PASSWD'=>$PASSWD,'USER_TYPE'=>$USER_TYPE,'NICK_NAME'=>$NICK_NAME,'AGE'=>$AGE,'EMAIL'=>$EMAIL,'STATUS'=>'Y','DEVICE_ID'=>$DEVICE_ID,'APP_TYPE'=>$APP_TYPE,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'PUSH_TOKEN'=>$PUSH_TOKEN,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'JOIN_IP'=>$JOIN_IP,'LOGIN_IP'=>$LOGIN_IP,'LOGIN_DATE'=>$CREATED_AT,'AGREED_TERM_DATE'=>$CREATED_AT,'AGREED_PERSON_DATE'=>$CREATED_AT,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'LOGIN_TYPE'=>$LOGIN_TYPE,'APPROVED_AT'=>$CREATED_AT,'APPROVED_BY'=>'1',];
      //print_r($newData);
      //exit;
      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='가입 처리도중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,'');
      } else {
        $std->transCommit();
        $message= '정상적으로 가입이 되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$newData);  
      }
    }          
  }

  public function info_view() { 
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
    
      // if($header  == 'refresh_token') {
      //   $REFRESH_TOKEN = $value;
      // } else {
      //   $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      // } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }

    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    if($total==0) {
      $message='해당 사용자가 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;

    } else {
      //select
      //$builder->select('user.USER_ID, user.PHONE,user.USER_TYPE,user.NICK_NAME,user.IMAGE');
      $builder->select('user.*');
      $builder->where('user.USER_IDX', $USER_IDX);  

      $data['member'] = $builder->get()->getResult('array');       
    
      $message='';
      ajaxReturn(RESULT_SUCCESS,$message,$data);
      return;
    }
  }

  public function info_update() {
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
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }

    $PHONE        = esc($request->getPost('phone'));
    $NICK_NAME    = esc($request->getPost('nick_name'));
    
    if($PHONE !='') {
      $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

      if($is_hp == FALSE) {
        $message='잘못된 전화번호 형식입니다.';
        ajaxReturn(RESULT_FAIL,$message,$PHONE);
        return;
      }
      $PHONE = get_hp($PHONE,1); //-넣음    
       $newData['PHONE']=$PHONE;
    }

    if($NICK_NAME !='') {
      $newData['NICK_NAME']=$NICK_NAME;
    }
  
    $this->db->transBegin();
    $builder = $this->db->table("MEMBER as member");   
    $builder->where('member.USER_IDX', $USER_IDX );
		$result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='업데이트 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;
    } else {
      $this->db->transCommit();          
      $message='업데이트 되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,'');
      return;
    }

  }


  public function findidpw() {  





  }

  public function new_pwd() {
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
  
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }

    $pwd = esc($request->getPost('pwd'));

    $pwd = password_hash($pwd, PASSWORD_BCRYPT);
    $newData = ['PASSWD'=>$pwd,];

    $this->db->transBegin();
    $builder = $this->db->table("MEMBER as member");   
    $builder->where('member.USER_IDX', $USER_IDX );
		$result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='비밀번호가 업데이트 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    } else {
      $this->db->transCommit();          
      $message='비밀번호가 업데이트 되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,"");
      return;
    }

  }

  public function check_dup() {  //중복확인
    $request = \Config\Services::request();

    $appid  = esc($request->getPost('appid'));

    $where  = esc($request->getPost('where'));
    $value  = esc($request->getPost('value'));
    
    if($where=='phone'){
      $where = 'PHONE'; 
      $is_hp = is_hp($value); //휴대전화번호인지 확인 ture, false 로 리턴

      if($is_hp == FALSE) {
        $message='잘못된 전화번호 형식입니다.';
        ajaxReturn(RESULT_FAIL,$message,$value);
        return;
      }
      $value = get_hp($value,1); //-넣음 
    } else if($where=='user_id'){
      $where = 'USER_ID';
    } else if($where=='nick_name'){
      $where = 'NICK_NAME';
    } else {
      $message='잘못된 접근입니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }
    //user_id, nick_name, device_id
    //count
    $builder = $this->db->table("MEMBER");
    $builder->select('USER_ID, NICK_NAME, PHONE');
    $builder->where($where, $value);
    $total = $builder->countAllResults();
  
    if($total>0) {
      $message='중복된 자료가 있습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$total);  
      return;
    } else {
      $message='중복된 자료가 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }

  }
  
  
  public function check_dup_openid() {  // check_dup_openid 중복확인
    $request = \Config\Services::request();

    $openid     = esc($request->getPost('openid'));
    $LOGIN_TYPE = esc($request->getPost('login_type'));
   // $PHONE      = esc($request->getPost('phone'));
   // $DEVICE_ID  = esc($request->getPost('device_id'));
   // $APP_TYPE   = esc($request->getPost('app_type'));
    $USER_TYPE  = esc($request->getPost('user_type'));    

    // $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

    // if($is_hp == FALSE) {
    //   $message='잘못된 전화번호 형식입니다.';
    //   ajaxReturn(RESULT_FAIL,$message,$PHONE);
    //   return;
    // }
    // $PHONE = get_hp($PHONE,1); //-넣음

    if($openid=='') {
      $message='잘못된 접근입니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }

    if($LOGIN_TYPE=='kakao') {
      $USER_ID='k_'.$openid;
    } else if($openid=='apple') {
      $LOGIN_TYPE='a_'.$openid;
    }

    //count
    $builder = $this->db->table("MEMBER");
    $builder->select('*');
    $builder->where('USER_ID', $USER_ID);
    $builder->where('USER_TYPE', $USER_TYPE);
    $builder->where('LOGIN_TYPE', $LOGIN_TYPE);
    $total = $builder->countAllResults();

    //select
    $builder = $this->db->table("MEMBER as member");
    $builder->select('member.*');
    $builder->where('USER_ID', $USER_ID);
    // $builder->where('PHONE', $PHONE);
    // $builder->where('APP_TYPE', $APP_TYPE);
    $builder->where('USER_TYPE', $USER_TYPE);
    // $builder->where('DEVICE_ID', $DEVICE_ID);
    $data['member'] = $builder->get()->getResult('array');      

    if($total>0) {
      $message='중복된 자료가 있습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$data);  
      return;
    } else {
      $message='중복된 자료가 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }

  }

	public function delete() {
		// 회원자료는 정보만 없앤 후 아이디는 보관하여 다른 사람이 사용하지 못하도록 함
		// 게시판에서 회원아이디는 삭제하지 않기 때문
    //정산이 다 된 경우에만 삭제 처리한다.
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
    
    $AGREED = esc($request->getPost('agreed')); 

    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }

    if(!$AGREED || $AGREED!='Y'){
      ajaxReturn(RESULT_FAIL,"동의하여야 합니다.");
      return;
    }

    //count
    $builder = $this->db->table("MEMBER as member");
    $builder->select('member.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'member.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('member.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    if (!$total || $total==0) {
      $message='회원정보를 찾을 수 없어서 정상적으로 탈퇴처리가 되지 못했습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    }
    //탈퇴처리
    
       
    $newData = ['PHONE'=>'','PASSWD'=>'','NICK_NAME'=>'','STATUS'=>'O','IMAGE'=>'','APP_TYPE'=>'','ACCESS_TOKEN'=>'','TOKEN_EXPIRED_DATE'=>'','PUSH_TOKEN'=>'','REFRESH_TOKEN'=>'','REFRESH_TOKEN_EXPIRED_DATE'=>'',];
    
    $this->db->transBegin();         
    //$builder = $this->db->table("MEMBER as member");   
    $builder->where('member.USER_IDX', $USER_IDX);
		$builder->update($newData);   

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='정상적으로 탈퇴처리가 되지 않았습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    } else {
      $this->db->transCommit();  
      
      
      // 이미지 삭제, 쿠폰정보 삭제

      
      $message='정상적으로 탈퇴처리되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,"");
      return;
    }
    

	}





	public function login() {
    $request = \Config\Services::request();
    $session = \Config\Services::session();

    $USER_TYPE  = esc($request->getPost('user_type'));
    $DEVICE_ID  = esc($request->getPost('device_id'));
    $APP_TYPE   = esc($request->getPost('app_type'));
    $PUSH_TOKEN = esc($request->getPost('push_token'));
    $PHONE      = esc($request->getPost('phone'));
    $LOGIN_TYPE = esc($request->getPost('login_type'));

    $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

    if($is_hp == FALSE) {
      $message='잘못된 전화번호 형식입니다.';
      ajaxReturn(RESULT_FAIL,$message,$PHONE);
      return;
    }
    $PHONE = get_hp($PHONE,1); //-넣음    

    if($LOGIN_TYPE=='idx' || $LOGIN_TYPE=='kakao' || $LOGIN_TYPE=='apple') {
    
      $ACCESS_DATA = esc($request->getPost('access_data'));  

      $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
      // print_r($ACCESS_DATA);
      // exit;

      $ACCESS_DATA=json_decode($ACCESS_DATA,true);
      // print_r($ACCESS_DATA);
      // exit;
      $USER_IDX           = $ACCESS_DATA['user_idx'];
      $ACCESS_TOKEN       = $ACCESS_DATA['access_token'];
      $TOKEN_EXPIRED_DATE = $ACCESS_DATA['expired_date'];
      $DEVICE_ID          = $ACCESS_DATA['device_id'];
      $APP_TYPE           = $ACCESS_DATA['app_type'];

      if(!$USER_IDX){
        ajaxReturn(RESULT_FAIL,'USER_IDX PHONE DEVICE_ID APP_TYPE PUSH_TOKEN 정보 없음.','');
        return;
      }

      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.USER_IDX', $USER_IDX);  
      $builder->where('member.ACCESS_TOKEN', $ACCESS_TOKEN);  
      $builder->where('member.DEVICE_ID', $DEVICE_ID);  
      $builder->where('member.APP_TYPE', $APP_TYPE);  
      $builder->where('member.TOKEN_EXPIRED_DATE >=', $TOKEN_EXPIRED_DATE);  
      $total = $builder->countAllResults();

      if(!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } else if($total==1) {

        //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
        $CREATED_AT = date('Y-m-d H:i:s');
        $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
        $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
        $ACCESS_TOKEN=access_token();
        $REFRESH_TOKEN=access_token();  
       
        //회원정보에 넣을 것
        $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,];
    
        //$json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
        $this->db->transBegin();
        $builder = $this->db->table("MEMBER as user");   
        $builder->where('USER_IDX', $USER_IDX );
        $result = $builder->update($newData);
    
        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='데이타 업데이트 오류';
          ajaxReturn(RESULT_FAIL,$message,'');
          return;
        } else {
          $this->db->transCommit();          
          
          //select
          $builder = $this->db->table("MEMBER as member");
          $builder->select('member.USER_ID, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
          $builder->where('member.USER_IDX', $USER_IDX);  
          $data['member'] = $builder->get()->getResult('array');         
          ajaxReturn(RESULT_SUCCESS,"",$newData);  
          return;
        }         
      }
    }        

    if($LOGIN_TYPE=='id') {
      
      $USER_ID  = esc($request->getPost('user_id'));
      $PASSWD = esc($request->getPost('passwd'));   

      if(!$USER_ID || $USER_ID =='' || !$PASSWD ||$PASSWD==''){
        ajaxReturn(RESULT_FAIL,'PHONE DEVICE_ID  정보 없음','');
        return;
      }

      $PASSWD = password_hash($PASSWD, PASSWORD_BCRYPT);

      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.USER_ID', $USER_ID);  
      $total = $builder->countAllResults();

      if(!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } else if($total==1) {
        //select
        $builder->select('member.USER_IDX, member.USER_ID, member.PASSWD, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
        $builder->where('member.USER_ID', $USER_ID);  
        $data['member'] = $builder->get()->getResult('array');   

        $hash = $data['member'][0]['PASSWD'];
        $USER_IDX = $data['member'][0]['USER_IDX'];
        // print_r($data['member'][0]['PASSWD']);
        // exit;        
        if (password_verify($PASSWD ,$hash)) {          
            ajaxReturn(RESULT_FAIL,'비번이 다릅니다.','');
            return;
        } else {
          //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
          $CREATED_AT = date('Y-m-d H:i:s');
          $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
          $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
          $ACCESS_TOKEN=access_token();
          $REFRESH_TOKEN=access_token();  

          //회원정보에 넣을 것
          $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,];

          //$json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
          $this->db->transBegin();
          $builder = $this->db->table("MEMBER");   
          $builder->where('USER_IDX', $USER_IDX );
          $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='데이타 업데이트 오류';
            ajaxReturn(RESULT_FAIL,$message,'');
            return;
          } else {
            $this->db->transCommit();          
            //select
            $builder = $this->db->table("MEMBER as member");
            $builder->select('member.USER_ID, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.ACCESS_TOKEN, member.TOKEN_EXPIRED_DATE, member.REFRESH_TOKEN, member.REFRESH_TOKEN_EXPIRED_DATE');
            $builder->where('member.USER_IDX', $USER_IDX);  
            $data['member'] = $builder->get()->getResult('array');         
            ajaxReturn(RESULT_SUCCESS,"",$newData);  
            return;          
          }            
        }
      }      
    }

    if($LOGIN_TYPE=='p') {
      // print_r($PHONE);
      // print_r($DEVICE_ID);
      // print_r($APP_TYPE);
      // print_r($PUSH_TOKEN);
      // print_r($USER_TYPE);

      
      if(!$PHONE || $PHONE =='' || !$DEVICE_ID ||$DEVICE_ID=='' || !$APP_TYPE ||$APP_TYPE=='' || !$PUSH_TOKEN ||$PUSH_TOKEN==''){
        ajaxReturn(RESULT_FAIL,'PHONE DEVICE_ID APP_TYPE PUSH_TOKEN 정보 없음','');
        return;
      }

      
      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.PHONE', $PHONE);  
      $builder->where('member.DEVICE_ID', $DEVICE_ID);  
      $builder->where('member.APP_TYPE', $APP_TYPE);  
      $builder->where('member.USER_TYPE', $USER_TYPE);  
      $builder->where('member.LOGIN_TYPE', $LOGIN_TYPE);  
      $total = $builder->countAllResults();


     //print_r($total);
      if(!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } else if($total==1) {

        $builder->select('member.USER_IDX, member.USER_ID, member.PASSWD, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
        $builder->where('member.PHONE', $PHONE);
        $builder->where('member.DEVICE_ID', $DEVICE_ID);  
        $builder->where('member.APP_TYPE', $APP_TYPE);  
        $builder->where('member.USER_TYPE', $USER_TYPE);
        $data['member'] = $builder->get()->getResult('array');   
      
        $USER_IDX = $data['member'][0]['USER_IDX'];
        

    
        //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
        $CREATED_AT = date('Y-m-d H:i:s');
        $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
        $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
        $ACCESS_TOKEN   = access_token();
        $REFRESH_TOKEN  = access_token();  
       
        //회원정보에 넣을 것
        $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,];

        $this->db->transBegin();
 
        $builder->where('member.USER_IDX', $USER_IDX );
        $builder->update($newData);
    
        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='데이타 업데이트 오류';
          ajaxReturn(RESULT_FAIL,$message,'');
          return;
        } else {
          $this->db->transCommit();          
          //select      
          $Return_builder = $this->db->table("MEMBER as member");    
          $Return_builder->select('member.USER_ID, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.ACCESS_TOKEN, member.TOKEN_EXPIRED_DATE, member.REFRESH_TOKEN, member.REFRESH_TOKEN_EXPIRED_DATE');
          $Return_builder->where('member.USER_IDX', $USER_IDX);  
          $ReturnData['member'] = $Return_builder->get()->getResult('array');    
          
          
         // print_r($USER_IDX);
          ajaxReturn(RESULT_SUCCESS,"전화번호로 복구성공",$ReturnData);  
          return;
        }     
            
      }
    }			
	}

	public function logout() {

    $request = \Config\Services::request();
    $session = \Config\Services::session();
    
    $USER_ID    = esc($request->getPost('user_id'));
    $PASSWD     = esc($request->getPost('passwd'));
    $USER_IDX   = esc($request->getPost('user_idx'));
    $PHONE      = esc($request->getPost('phone'));
    $device_id  = esc($request->getPost('device_id'));
    $app_type   = esc($request->getPost('app_type'));
    $push_token = esc($request->getPost('push_token'));
    $login_type = esc($request->getPost('login_type'));
    $user_type  = esc($request->getPost('user_type'));

    $this->session->sess_destroy();
    delete_cookie('ck_mb_id');
	}


	public function recover() {
    $request = \Config\Services::request();
    //$session = \Config\Services::session();

    $USER_TYPE  = esc($request->getPost('user_type'));
    $DEVICE_ID  = esc($request->getPost('device_id'));
    $APP_TYPE   = esc($request->getPost('app_type'));
    $PUSH_TOKEN = esc($request->getPost('push_token'));

    $PHONE      = esc($request->getPost('phone'));
    $LOGIN_TYPE = esc($request->getPost('login_type'));
    $openid     = esc($request->getPost('openid'));
    $EMAIL      = esc($request->getPost('email'));

    $is_hp = is_hp($PHONE); //휴대전화번호인지 확인 ture, false 로 리턴

    if($is_hp == FALSE) {
      $message='잘못된 전화번호 형식입니다.';
      ajaxReturn(RESULT_FAIL,$message,$PHONE);
      return;
    }
    $PHONE = get_hp($PHONE,1); //-넣음 


    if($LOGIN_TYPE=='kakao' || $LOGIN_TYPE=='apple') {
       if($LOGIN_TYPE=='kakao') {
        $USER_ID ='k_'.$openid;
      } else if($LOGIN_TYPE=='apple') {
        $USER_ID ='a_'.$openid;
      }

      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.USER_ID', $USER_ID);  
      // $builder->where('member.PHONE', $PHONE);  
      // $builder->where('member.DEVICE_ID', $DEVICE_ID);  
      //$builder->where('member.APP_TYPE', $APP_TYPE);  
      $total = $builder->countAllResults();

      if(!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } else if($total==1) {

        //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
        $CREATED_AT = date('Y-m-d H:i:s');
        $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
        $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
        $ACCESS_TOKEN=access_token();
        $REFRESH_TOKEN=access_token();  
       
        //회원정보에 넣을 것
        $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'EMAIL'=>$EMAIL,'PHONE'=>$PHONE,'DEVICE_ID'=>$DEVICE_ID,'PUSH_TOKEN'=>$PUSH_TOKEN,];
    
        //$json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
        $this->db->transBegin();
        $builder = $this->db->table("MEMBER as member");   
        $builder->where('USER_ID', $USER_ID );
        $result = $builder->update($newData);
    
        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='사용자 복구 오류';
          ajaxReturn(RESULT_FAIL,$message,$newData);
          return;
        } else {
          $this->db->transCommit();          
          
          //select
          $builder = $this->db->table("MEMBER as member");
          $builder->select('member.USER_IDX, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
          $builder->where('member.USER_ID', $USER_ID);  
          $data['member'] = $builder->get()->getResult('array');         
          ajaxReturn(RESULT_SUCCESS,"",$data);  
          return;
        }         
      }
      




    }

    











    // if($LOGIN_TYPE=='id') {
      
    //   $USER_ID    = esc($request->getPost('user_id'));
    //   $PASSWD     = esc($request->getPost('passwd'));   

    //   if(!$USER_ID || $USER_ID =='' || !$PASSWD ||$PASSWD==''){
    //     ajaxReturn(RESULT_FAIL,'PHONE DEVICE_ID  정보 없음','');
    //     return;
    //   }

    //   $PASSWD = password_hash($PASSWD, PASSWORD_BCRYPT);

    //   //count
    //   $builder = $this->db->table("MEMBER as member");
    //   $builder->select('member.*');
    //   $builder->where('member.USER_ID', $USER_ID);  
    //   $total = $builder->countAllResults();

    //   if(!$total || $total==0) {
    //     ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
    //     return;
    //   } else if($total==1) {
    //     //select
    //     $builder->select('member.USER_IDX, member.USER_ID, member.PASSWD, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
    //     $builder->where('member.USER_ID', $USER_ID);  
    //     $data['member'] = $builder->get()->getResult('array');   

    //     $hash = $data['member'][0]['PASSWD'];
    //     $USER_IDX = $data['member'][0]['USER_IDX'];
    //     // print_r($data['member'][0]['PASSWD']);
    //     // exit;        
    //     if (password_verify($PASSWD ,$hash)) {          
    //         ajaxReturn(RESULT_FAIL,'비번이 다릅니다.','');
    //         return;
    //     } else {
    //       //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
    //       $CREATED_AT = date('Y-m-d H:i:s');
    //       $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+1 days',strtotime($CREATED_AT)));
    //       $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
    //       $ACCESS_TOKEN=access_token();
    //       $REFRESH_TOKEN=access_token();  

    //       //회원정보에 넣을 것
    //       $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,];

    //       //$json_data=json_encode($newData,JSON_UNESCAPED_UNICODE);
    //       $this->db->transBegin();
    //       $builder = $this->db->table("MEMBER");   
    //       $builder->where('USER_IDX', $USER_IDX );
    //       $builder->update($newData);

    //       if ($this->db->transStatus() === FALSE) {
    //         $this->db->transRollback();
    //         $message='데이타 업데이트 오류';
    //         ajaxReturn(RESULT_FAIL,$message,'');
    //         return;
    //       } else {
    //         $this->db->transCommit();          
    //         //select
    //         $builder = $this->db->table("MEMBER as member");
    //         $builder->select('member.USER_ID, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
    //         $builder->where('member.USER_IDX', $USER_IDX);  
    //         $data['member'] = $builder->get()->getResult('array');         
    //         ajaxReturn(RESULT_SUCCESS,"",$newData);  
    //         return;          
    //       }            
    //     }
    //   }      
    // }

    else if($LOGIN_TYPE=='p') {
      // print_r($PHONE);
      // print_r($DEVICE_ID);
      // print_r($APP_TYPE);
      // print_r($PUSH_TOKEN);
      // print_r($USER_TYPE);

      
      if(!$PHONE || $PHONE =='' || !$DEVICE_ID ||$DEVICE_ID=='' || !$APP_TYPE ||$APP_TYPE==''){
        ajaxReturn(RESULT_FAIL,'PHONE DEVICE_ID APP_TYPE 정보 없음','');
        return;
      }

      
      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.PHONE', $PHONE);  
      $builder->where('member.DEVICE_ID', $DEVICE_ID);  
      $builder->where('member.APP_TYPE', $APP_TYPE);  
      $builder->where('member.USER_TYPE', $USER_TYPE);  
      $total = $builder->countAllResults();



      if(!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,"가입정보가 없습니다.","");
        return;
      } else if($total==1) {

        $builder->select('member.USER_IDX, member.USER_ID, member.PASSWD, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.TOKEN_EXPIRED_DATE');
        $builder->where('member.PHONE', $PHONE);
        $builder->where('member.DEVICE_ID', $DEVICE_ID);  
        $builder->where('member.APP_TYPE', $APP_TYPE);  
        $builder->where('member.USER_TYPE', $USER_TYPE);
        $data['member'] = $builder->get()->getResult('array');   
      
        $USER_IDX = $data['member'][0]['USER_IDX'];
        
        //TOKEN_EXPIRED_DATE를 지금부터 한달 후로 연장한다.
        $CREATED_AT = date('Y-m-d H:i:s');
        $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
        $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
        $ACCESS_TOKEN   = access_token();
        $REFRESH_TOKEN  = access_token();  
       
        //회원정보에 넣을 것
        $newData = ['ACCESS_TOKEN'=>$ACCESS_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'PUSH_TOKEN'=>$PUSH_TOKEN,];

        $this->db->transBegin();
 
        $builder->where('member.USER_IDX', $USER_IDX );
        $builder->update($newData);
    
        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='사용자 복구 오류';
          ajaxReturn(RESULT_FAIL,$message,"");
          return;
        } else {
          $this->db->transCommit();          
          //select      
          $builder = $this->db->table("MEMBER as member");    
          $builder->select('member.USER_IDX, member.PHONE, member.USER_TYPE, member.IMAGE, member.ACCESS_TOKEN, member.TOKEN_EXPIRED_DATE, member.REFRESH_TOKEN, member.REFRESH_TOKEN_EXPIRED_DATE');
          $builder->where('member.USER_IDX', $USER_IDX);  
          $ReturnData['member'] = $builder->get()->getResult('array');         
          ajaxReturn(RESULT_SUCCESS,"",$ReturnData);  
          return;
        }     
            
      }
    }			
	}




}

