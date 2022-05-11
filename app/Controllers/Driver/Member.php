<?php
namespace App\Controllers\Driver;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\Driver_join_infoModel;

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

  public function check_dup() {  //중복확인
    $request = \Config\Services::request();

    $appid  = esc($request->getPost('appid'));
    $where  = esc($request->getPost('where'));
    $value  = esc($request->getPost('value'));
    
    if($where=='phone'){
      $where = 'PHONE'; 
      $value = get_hp($value,1); //-넣음
    } else if($where=='user_id'){
      $where = 'USER_ID';
    } else if($where=='nick_name'){
      $where = 'NICK_NAME';
    } else {
      $message='잘못된 접근입니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;
    }
    //user_id, nick_name, device_id
    //count
    $builder = $this->db->table("MEMBER");
    $builder->select('USER_ID, NICK_NAME, PHONE');
    $builder->where($where, $value);
    $total = $builder->countAllResults();
  
    if ($total>0) {
      $message='중복된 자료가 있습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$total);  
      return;
    } else {
      $message='중복된 자료가 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,$total);
      return;
    }
  }
  public function join() {
    $request = \Config\Services::request();
    $security = \Config\Services::security();    
    $lib = new ValidChecker();

    $PHONE      = esc($request->getPost('phone'));
    $APP_TYPE   = esc($request->getPost('app_type')); 
    $DEVICE_ID  = esc($request->getPost('device_id'));   
    $PUSH_TOKEN = esc($request->getPost('push_token'));
    $SMS_CHECK  = esc($request->getPost('sms_check'));  
    $LOGIN_TYPE = 'id';  
    $USER_TYPE  = esc($request->getPost('user_type'));    

    $PHONE = get_hp($PHONE,1); //-를 넣음
    // if(!$SMS_CHECK && $SMS_CHECK!='Y') {        
    //   exit;
    // }

    $CREATED_AT = date('Y-m-d H:i:s');
    $TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+30 days',strtotime($CREATED_AT)));
    $REFRESH_TOKEN_EXPIRED_DATE = date('Y-m-d H:i:s',strtotime('+60 days',strtotime($CREATED_AT)));
    $ACCESS_TOKEN=access_token();
    $REFRESH_TOKEN=access_token();

    $STATUS         = 'W';
    $ACCESS_TOKEN   = $ACCESS_TOKEN;
    $REFRESH_TOKEN  = $REFRESH_TOKEN;
    $JOIN_IP        = $_SERVER['REMOTE_ADDR'];
    $LOGIN_IP       = $_SERVER['REMOTE_ADDR'];
   
    // $check = $lib->check_csfs($PET_NAME);
 
    // if($check) {
    //   $msg= '특수문자 포함됨';
    //   echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
    //   exit;
    // }   

    // $USER_ID    = esc($request->getPost('user_id'));
    $USER_ID  ='driver_'.$PHONE;
    $PASSWD   = 123456;



    $NICK_NAME  = esc($request->getPost('user_name'));
    $NAME       = esc($request->getPost('user_name'));
    $GENDER     = esc($request->getPost('gender'));
    $BIRTH      = esc($request->getPost('birth'));
    $EMAIL      = esc($request->getPost('email'));
    $ADDR1      = esc($request->getPost('addr1'));
    $ADDR2      = esc($request->getPost('addr2'));
    $CAR_NUM    = esc($request->getPost('car_num'));
    $MYCAR      = esc($request->getPost('mycar'));
    $CAR_TYPE   = esc($request->getPost('car_type'));
    $CAREER     = esc($request->getPost('career'));
    $ALLERGY    = esc($request->getPost('allergy'));
    $ATP_NUM    = esc($request->getPost('atp_num'));
    $IBRC_NUM   = esc($request->getPost('ibrc_num'));
    $DRIVER_NUM = esc($request->getPost('driver_num'));
    $DRIVER_SECURITY  = esc($request->getPost('driver_security'));
    $BANK_ACCOUNT     = esc($request->getPost('bank_account'));
    $COMMENT          = esc($request->getPost('comment'));

    if ($NICK_NAME=="" || $NAME=="" || $GENDER=="" || $BIRTH=="" || $EMAIL=="" ||  $ADDR1=="" || $ADDR2=="" || $CAR_NUM=="" || $MYCAR=="" || $CAR_TYPE=="" || $CAREER =="" || $ALLERGY =="" || $ATP_NUM =="" || $IBRC_NUM =="" || $DRIVER_NUM =="" || $DRIVER_SECURITY =="" || $BANK_ACCOUNT =="") {
      $message='기본정보의 입력이 충분하지 않습니다.';
      ajaxReturn(RESULT_FAIL,$message,$NICK_NAME);

    }

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
      $newData = ['USER_IDX'=>$new_USER_IDX,'USER_ID'=>$USER_ID,'PHONE'=>$PHONE,'PASSWD'=>$PASSWD,'USER_TYPE'=>$USER_TYPE,'NICK_NAME'=>$NICK_NAME,'STATUS'=>$STATUS,'DEVICE_ID'=>$DEVICE_ID,'APP_TYPE'=>$APP_TYPE,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'PUSH_TOKEN'=>$PUSH_TOKEN,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'JOIN_IP'=>$JOIN_IP,'LOGIN_IP'=>$LOGIN_IP,'LOGIN_DATE'=>$CREATED_AT,'AGREED_TERM_DATE'=>$CREATED_AT,'AGREED_PERSON_DATE'=>$CREATED_AT,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE,'LOGIN_TYPE'=>$LOGIN_TYPE,'APPROVED_AT'=>$CREATED_AT,'APPROVED_BY'=>'1',];

      $PrintData = ['USER_IDX'=>$new_USER_IDX,'STATUS'=>$STATUS,'ACCESS_TOKEN'=>$ACCESS_TOKEN,'REFRESH_TOKEN'=>$REFRESH_TOKEN,'TOKEN_EXPIRED_DATE'=>$TOKEN_EXPIRED_DATE,'REFRESH_TOKEN_EXPIRED_DATE'=>$REFRESH_TOKEN_EXPIRED_DATE];
      //print_r($newData);
      // exit;
      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='드라이버 기본정보 입력 실패';
        ajaxReturn(RESULT_FAIL,$message,"");
      } else {
        $std->transCommit();
        // $message= '드라이버 기본정보 입력 성공';
        // ajaxReturn(RESULT_SUCCESS,$PrintData,"");  
      }

      $std_join = new Driver_join_infoModel();
      $std_join->transBegin();

      $newData = ['USER_IDX'=>$new_USER_IDX, 'NAME'=>$NAME, 'GENDER'=>$GENDER,'STATUS'=>$STATUS, 'BIRTH'=>$BIRTH, 'EMAIL'=>$EMAIL, 'ADDR1'=>$ADDR1,'ADDR2'=>$ADDR2, 'MYCAR'=>$MYCAR, 'CAR_TYPE'=>$CAR_TYPE,'CAREER'=>$CAREER,'ALLERGY'=>$ALLERGY,'ATP_NUM'=>$ATP_NUM,'IBRC_NUM'=>$IBRC_NUM,'COMMENT'=>$COMMENT,'IBRC_NUM'=>$IBRC_NUM,'DRIVER_SECURITY'=>$DRIVER_SECURITY,'BANK_ACCOUNT'=>$BANK_ACCOUNT,'CAR_NUM'=>$CAR_NUM,];
      // echo"<p></p>";
      // print_r($newData);
      // echo"<p></p>";
      $std_join->insert($newData);

      if ($std_join->transStatus() === FALSE) {
        $std_join->transRollback();
        $message='드라이버 추가정보 입력 실패';
        ajaxReturn(RESULT_FAIL,$message,"");
      } else {
        $std_join->transCommit();
        $message= '드라이버 정보 입력 성공';
        ajaxReturn(RESULT_SUCCESS,"",$newData);  
      }

  
        
  }


	public function login() {
    $request = \Config\Services::request();
    $session = \Config\Services::session();

    $LOGIN_TYPE = esc($request->getPost('login_type'));

    if ($LOGIN_TYPE=='id') {

      $USER_TYPE  = esc($request->getPost('user_type'));
      $DEVICE_ID  = esc($request->getPost('device_id'));
      $APP_TYPE   = esc($request->getPost('app_type'));
      // $PUSH_TOKEN = esc($request->getPost('push_token'));
      // $PHONE      = esc($request->getPost('phone'));
      //$PHONE = get_hp($PHONE,1); //-넣음
     
      $USER_ID    = esc($request->getPost('user_id'));
      $PASSWD     = esc($request->getPost('passwd')); 

      if (!$USER_ID || $USER_ID =='' || !$PASSWD ||$PASSWD=='') {
        ajaxReturn(RESULT_FAIL,'USER_ID PASSWD 정보 없음',"");
        return;
      }

      $PASSWD = password_hash($PASSWD, PASSWORD_BCRYPT);

      //count  좀더 디테일하게 where을 구성한다. 비번 디바이스id등
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.USER_ID', $USER_ID);  
      $total = $builder->countAllResults();
      // print_r($USER_ID);
      // exit;

      if (!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } else if ($total==1) {
        //select
        $builder->select('member.USER_IDX, member.USER_ID, member.PASSWD, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.ACCESS_TOKEN, member.TOKEN_EXPIRED_DATE, member.REFRESH_TOKEN, member.REFRESH_TOKEN_EXPIRED_DATE');
        $builder->where('member.USER_ID', $USER_ID);  
        $data['member'] = $builder->get()->getResult('array');   
        $data['total'] = $total; 

        $hash = $data['member'][0]['PASSWD'];
        $USER_IDX = $data['member'][0]['USER_IDX'];
        // print_r($data['member'][0]['PASSWD']);
        // exit;
        if (password_verify($PASSWD ,$hash)) {          
          ajaxReturn(RESULT_FAIL,'비번이 다릅니다.','');
          return;
        } else {
          ajaxReturn(RESULT_SUCCESS,"",$data);  
          return;
        }
      } 
    } else if ($LOGIN_TYPE=='idx') {
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

      if (!$USER_IDX || $USER_IDX =='' || !$ACCESS_TOKEN ||$ACCESS_TOKEN=='') {
        ajaxReturn(RESULT_FAIL,'DEVICE_ID  정보 없음',"");
        return;
      }

      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      $builder->where('member.USER_IDX', $USER_IDX);  
      $total = $builder->countAllResults();
     
      if (!$total || $total==0) {
        ajaxReturn(RESULT_FAIL,'가입정보가 없습니다.','');
        return;
      } 

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
          $builder->select('member.USER_IDX, member.USER_ID, member.PHONE, member.USER_TYPE, member.NICK_NAME, member.IMAGE, member.ACCESS_TOKEN, member.TOKEN_EXPIRED_DATE, member.REFRESH_TOKEN, member.REFRESH_TOKEN_EXPIRED_DATE');
          $builder->where('member.USER_IDX', $USER_IDX);  
          $data['member'] = $builder->get()->getResult('array');         
          ajaxReturn(RESULT_SUCCESS,"",$data);  
          return;
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
    $builder->select('user.*,join_info.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    if($total==1){
      //select
      $builder->select('user.*,join_info.*');
      $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
      $builder->where('user.USER_IDX', $USER_IDX);  

      $data['member'] = $builder->get()->getResult('array');   
    
      ajaxReturn(RESULT_SUCCESS,"",$data); 
      return;

    } else {
      ajaxReturn(RESULT_FAIL,"해당 회원 정보가 없습니다.","");
      return;
    }
            
  }



  public function info_update() {
    $newData = array();
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

    $PASSWD     = esc($request->getPost('passwd'));
    $EMAIL      = esc($request->getPost('email'));
    $ADDR1      = esc($request->getPost('addr1'));
    $ADDR2      = esc($request->getPost('addr2'));
    $CAR_NUM    = esc($request->getPost('car_num'));
    $MYCAR      = esc($request->getPost('mycar'));
    $CAR_TYPE   = esc($request->getPost('car_type'));
    $CAREER     = esc($request->getPost('career'));
    $ALLERGY    = esc($request->getPost('allergy'));
    $ATP_NUM    = esc($request->getPost('atp_num'));
    $IBRC_NUM   = esc($request->getPost('ibrc_num'));
    $DRIVER_NUM = esc($request->getPost('driver_num'));
    $DRIVER_SECURITY  = esc($request->getPost('driver_security'));
    $BANK_ACCOUNT     = esc($request->getPost('bank_account'));
    $COMMENT          = esc($request->getPost('comment'));

    if ($EMAIL=="" ||  $ADDR1=="" || $ADDR2=="" || $CAR_NUM=="" || $MYCAR=="" || $CAR_TYPE=="" || $CAREER =="" || $ALLERGY =="" || $ATP_NUM =="" || $IBRC_NUM =="" || $DRIVER_NUM =="" || $DRIVER_SECURITY =="" || $BANK_ACCOUNT =="") {
      $message='기본정보의 입력이 충분하지 않습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
    }

    $PASSWD     = password_hash($PASSWD, PASSWORD_BCRYPT); 

    $newData = ['PHONE'=>$PHONE,'PASSWD'=>$PASSWD,'NICK_NAME'=>$NICK_NAME,];
    
    $DRIVER_UPDATE_DATA = ['EMAIL'=>$EMAIL,'ADDR1'=>$ADDR1,'ADDR2'=>$ADDR2,'CAR_NUM'=>$CAR_NUM,'MYCAR'=>$MYCAR,'CAR_TYPE'=>$CAR_TYPE,'CAREER'=>$CAREER,'ALLERGY'=>$ALLERGY,'ATP_NUM'=>$ATP_NUM,'IBRC_NUM'=>$IBRC_NUM, 'DRIVER_NUM'=>$DRIVER_NUM,'DRIVER_SECURITY'=>$DRIVER_SECURITY,'BANK_ACCOUNT'=>$BANK_ACCOUNT,'COMMENT'=>$COMMENT];
     
    $this->db->transBegin();
    $builder = $this->db->table("MEMBER as member");   
    $builder->where('member.USER_IDX', $USER_IDX );
    $result = $builder->update($newData);
  
    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='업데이트 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
      return;
    } else {
      $this->db->transCommit();  

      $this->db->transBegin();
      $builder_driver_join_info = $this->db->table("DRIVER_JOIN_INFO as driver");   
      $builder_driver_join_info->where('driver.USER_IDX', $USER_IDX );
      $driver_join_info_result = $builder_driver_join_info->update($DRIVER_UPDATE_DATA);
    
      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $message='업데이트 중 오류가 발생했습니다.QQ';
        ajaxReturn(RESULT_FAIL,$message,"");
        return;
      } else {
        $this->db->transCommit(); 
      }     
    }
    $message='업데이트 되었습니다.';
    ajaxReturn(RESULT_SUCCESS,$message,$DRIVER_UPDATE_DATA);
    return;     
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
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }

    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*,join_info.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    if (!$total || $total==0) {
      $message='회원정보를 찾을 수 없어서 정상적으로 탈퇴처리가 되지 못했습니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;
    }  
    //탈퇴처리
    $newData = ['STATUS'=>'O'];
                
    $this->db->transBegin();         
    $builder->where('USER_IDX', $USER_IDX );
    $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='정상적으로 탈퇴처리가 되지 않았습니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;
    } else {
      $this->db->transCommit();  
      
      // 이미지 삭제, 쿠폰정보 삭제
      

      $message='정상적으로 탈퇴처리되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,'');
      return;
    }
    

	}



}

