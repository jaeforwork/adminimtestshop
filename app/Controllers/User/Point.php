<?php
  namespace App\Controllers\User;
  
  use App\Controllers\BaseController;
  use CodeIgniter\Exceptions\AlertError;
  
  use App\Models\MemberModel;
  use App\Models\PaymentModel;
  use App\Models\Member_pointModel;
  
  class Point extends BaseController   {
    private $db;
  
    public function __construct() {
      $this->db = \Config\Database::connect('default');
    }

    public function mypoint() {  
      $request = \Config\Services::request();
      $ACCESS_DATA  = esc($request->getPost('access_data'));      
      $ACCESS_DATA  = json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);    
      $ACCESS_DATA  = json_decode($ACCESS_DATA,true);    

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
        ajaxReturn(RESULT_FAIL,"사용자 정보가 없습니다.","");
        return;
      }

      //count
      $builder = $this->db->table("MEMBER as member");
      $builder->select('member.*');
      //$builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
      $builder->where('member.USER_IDX', $USER_IDX); 
      $total = $builder->countAllResults();
      if($total==0) {        
        ajaxReturn(RESULT_FAIL,"회원정보가 검색결과가 없습니다.","");
        return;        
      }
      //select
      $builder->select('member.USER_IDX, member.POINT');
      //$builder->join('MEMBER_POINT as member_point', 'member.USER_IDX = member_point.USER_IDX', "left inner"); // added left here
      $builder->where('member.USER_IDX', $USER_IDX); 
      $data['member'] = $builder->get()->getResult('array');   
      $data['tcount']= $total;
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return;    
    }
    
  public function list() {   
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
      ajaxReturn(RESULT_FAIL,"사용자 정보가 없습니다.","");
      return;
    }
    $page   = esc($request->getPost('page'));
    if($page || $page==''){
      $page=1;
    }
		$offset = ($page - 1) *10;

    //count
    $builder = $this->db->table("MEMBER_POINT as point");
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where('point.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    //select
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where('point.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
    $data['page']= $page;
    ajaxReturn(RESULT_SUCCESS,"",$data);  
    return;       
  }


  public function view() {    
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
      ajaxReturn(RESULT_FAIL,"사용자 정보가 없습니다.","");
      return;
    }
   
    $IDX   = esc($request->getPost('po_idx'));

    //count
    $builder = $this->db->table("MEMBER_POINT as point");
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where('point.IDX', $IDX);  
    $builder->where('point.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    //select
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where('point.IDX', $IDX);  
    $builder->where('point.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
   
    ajaxReturn(RESULT_SUCCESS,"",$data);  
    return;       
  }


  
  public function use() {
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

    $UPO_USE_POINT   = esc($request->getPost('point'));
    $USER_IDX = esc($request->getPost('user_idx'));
    $STATUS="Y";
    //$UPDATED_AT = date('Y-m-d H:i:s');

    $std = new Member_pointModel();
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
      
      $data="UPDATE MEMBER SET POINT = POINT-'{$UPO_USE_POINT}' WHERE USER_IDX='{$USER_IDX}'"; 

      $result = $this->db->query($data);

      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $message='포인트 차감중 오류가 발생했습니다.';
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
          
      }else{//ios noti 구조 설정
          
      }
      
    //  $pushNoti->send($memberInfo->push_token, $dataMessage);
      $message= '정상적으로 포인트 차감 처리 되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
    } else{
      
    }

  }





}