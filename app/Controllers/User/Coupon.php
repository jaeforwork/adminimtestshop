<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;
use CodeIgniter\I18n\Time;

use App\Models\Member_couponModel;
use App\Models\App_push_messagesModel;

use App\Libraries\ValidChecker;
use App\Libraries\Sms;
use App\Libraries\Pushnoti;

class Coupon extends BaseController {

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

	public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();   
	}

  public function reg() {  
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
    
    $CP_ID  = esc($request->getPost('cp_id'));

    // $lib = new ValidChecker();

    // $check = $lib->check_csfs($PET_NAME);
 
    // if($check) {
    //   $message= '특수문자 포함됨';
    //   ajaxReturn(RESULT_FAIL,$message,""); 
    //   return;
    // }
    
    if(!$ACCESS_TOKEN && $ACCESS_TOKEN=='') {
      $message= '로그인 후 이용 하세요.';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    }

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {
      $UPDATED_AT = date('Y-m-d H:i:s');
      $CP_START = date('Y-m-d');
      $CP_END = date('Y-m-d',strtotime('+30 days',strtotime($CP_START)));

      //count
      $builder = $this->db->table("MEMBER_COUPON as member_coupon");  
      $builder->where(['member_coupon.CP_ID' => $CP_ID]); 
      $total = $builder->countAllResults();
      if($total==0) {  
        ajaxReturn(RESULT_FAIL,"사용할 수 없는 쿠폰 번호입니다.","");
        return;        
      }

      //select
      $builder->select('member_coupon.*');
      $builder->where(['member_coupon.CP_ID' => $CP_ID]);  
      //$builder->orderBy('user.USER_IDX','DESC');   
      $data['coupon'] = $builder->get()->getResult('array');  

      $CP_IDX       = $data['coupon'][0]['CP_IDX'];
      $CP_SUBJECT   = $data['coupon'][0]['CP_SUBJECT'];
      $CP_ONLY_USE  = $data['coupon'][0]['CP_ONLY_USE'];
      $CP_PRICE     = $data['coupon'][0]['CP_PRICE'];

      // print_r($CP_IDX);
      // exit;
      $NewData = array(
        'USER_IDX'  => $USER_IDX,
        'CP_START'  => $CP_START,
        'CP_END'    => $CP_END,
        'UPDATED_AT'  => $UPDATED_AT
      );

      $ReturnData=array(
        'CP_SUBJECT'  => $CP_SUBJECT,
        'CP_PRICE'    => $CP_PRICE,
        'CP_ONLY_USE' => $CP_ONLY_USE,
        'CP_START'    => $CP_START,
        'CP_END'      => $CP_END
      );
      
      $ReturnData['total_count']= $total;

      if($CP_IDX !='' && $USER_IDX !='') {
        $this->db->transBegin();
        $builder = $this->db->table("MEMBER_COUPON as member_coupon");  
        $builder->where(['member_coupon.CP_IDX'=>$CP_IDX] ); 
        $result = $builder->update($NewData);

        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='쿠폰 등록 중 오류가 발생했습니다.';
          ajaxReturn(RESULT_FAIL,$message,$NewData);
          return;
        } else {
          $this->db->transCommit();          
          $message='쿠폰발행이 정상적으로 등록되었습니다.';
          ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);
          return;
        }  
      }
    }
  }
  
  public function list($page=1) {
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

   // $pager=service('pager'); //instantiate pager..

    if($request->getGet('page')==null) { $page=1; } else {
      $page=$request->getGet('page');
    }
    //count
    $builder = $this->db->table("MEMBER_COUPON as coupon");
    $builder->select('coupon.*');
    //$builder->join('MEMBER_COUPON as coupon', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder->where('coupon.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"쿠폰이 없습니다.","");
      return;        
    }

    if($total>0) {
      //select
      // $builder->select('member.*, coupon.*');
      // $builder->join('MEMBER_COUPON as coupon', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      // $builder->where('member.USER_IDX', $USER_IDX);  
      // $builder->orderBy('coupon.CP_START','DESC');    
     // $builder = $this->db->table("MEMBER_COUPON as coupon");
      $builder->select('coupon.CP_IDX,coupon.CP_ID,coupon.CP_SUBJECT,coupon.CP_ONLY_USE,coupon.CP_START,coupon.CP_END,coupon.CP_PRICE,coupon.CP_TYPE,coupon.IS_USED,');
      //$builder->join('MEMBER_COUPON as coupon', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      $builder->where('coupon.USER_IDX', $USER_IDX);  
      $builder->orderBy('coupon.CP_START','DESC');    


      // $perPage =  5; //offset
      // if ($page != 1) {
      //   $offset = (($page - 1) * $perPage);
      //   $data['list'] = $builder->get($perPage, $offset)->getResult('array');
      // } else {
      //   $offset = $perPage;
      //   $data['list'] = $builder->get($offset, 0)->getResult('array');
      // }

    // $data['total']= $total;

    $data['coupon'] = $builder->get()->getResult('array');   

    $target=$data['coupon'][0]['CP_END'];
    
    $current = Time::parse(date('Y-m-d'));
    $test    = Time::parse($target);

    $diff = $current->difference($test);
    $left_days  = $diff->getDays()."일 남음"; 
    $data['coupon'][0]['LEFT_DAYS']=$left_days;  

    $data['total'] = $total;   

    //$json_data=json_encode($data,JSON_UNESCAPED_UNICODE);
      ajaxReturn(RESULT_SUCCESS,"",$data);
    return;
    }  else {
      ajaxReturn(RESULT_FAIL,"쿠폰이 없습니다.","");
      return;
    }  
	}

	public function view() {
    $request = \Config\Services::request();
    $USER_IDX = esc($request->getPost('user_idx'));
    $CP_IDX   = esc($request->getPost('cp_idx'));

    //count
    $builder = $this->db->table("MEMBER_COUPON as coupon");
    $builder->select('coupon.*');
    $builder->where('coupon.CP_IDX', $CP_IDX);  
   // $builder->join('MEMBER_COUPON as coupon', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $total = $builder->countAllResults();

    if($total<>0) {
      //select
      $builder->select('coupon.CP_IDX,coupon.CP_ID,coupon.CP_SUBJECT,coupon.CP_ONLY_USE,coupon.CP_START,coupon.CP_END,coupon.CP_PRICE,coupon.CP_TYPE,coupon.IS_USED,');
      //$builder->join('MEMBER_COUPON as coupon', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      $builder->where('coupon.CP_IDX', $CP_IDX);   
      $builder->orderBy('coupon.CP_START','DESC');
      $data['list'] = $builder->get()->getResult('array');    

      $data['total']= $total;

     // $json_data=json_encode($data,JSON_UNESCAPED_UNICODE);

      ajaxReturn(RESULT_SUCCESS,"",$data);
      return;
    } else {
      ajaxReturn(RESULT_FAIL,"쿠폰 정보가 없습니다.","");
      return;

    }
	}

  public function use() {
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

    $CP_IDX   = esc($request->getPost('cp_idx'));
    $USER_IDX = esc($request->getPost('user_idx'));
    //$STATUS="Y";
    //$UPDATED_AT = date('Y-m-d H:i:s');

    $std = new Member_couponModel();
    $std->transBegin();

    $newData = ['TR_IDX'=>$TR_IDX,'USER_IDX'=>$USER_IDX,'ADMIN_IDX'=>0,'TYPE'=>'U','UPO_USE_POINT'=>$UPO_USE_POINT,'STATUS'=>'Y','UPO_EXPIRE_DATE'=>'','UPO_CONTENT'=>''];

    $ReturnData = ['UPO_USE_POINT'=>$UPO_USE_POINT];
    // print_r($newData);
    // exit;

    $std->insert($newData);

    if ($std->transStatus() === FALSE) {
      $std->transRollback();
      $message='처리도중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,"");
    } else {
      $std->transCommit();
      
      $this->db->transBegin();
      
     // $data="UPDATE MEMBER SET POINT = POINT-'{$UPO_USE_POINT}' WHERE USER_IDX='{$USER_IDX}'"; 

     // $result = $this->db->query($data);

      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $message='오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,'');
        return;
      } else {
        $this->db->transCommit();          
        $tranResult='Y'; 
      }

  }






    if($tranResult=='Y'){
      //고객에게 도착 알림 전송.
     // $pushNoti=new \Pushnoti();//알림 발송
     // $dataMessage=array(); //notification 데이터
      
      if($memberInfo->app_type=="A"){//android noti 구조 설정
          
      } else {//ios noti 구조 설정
          
      }
      
    //  $pushNoti->send($memberInfo->push_token, $dataMessage);
      $message= '정상적으로 포인트 차감 처리 되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
    } else {
      
    }

  }











}