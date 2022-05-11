<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\PaymentModel;
use App\Models\Transport_endModel;

class Visit extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
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

    if (!$USER_IDX) {
      ajaxReturn(RESULT_FAIL,"사용자 정보가 없습니다.","");
      return;
    }
    $page   = esc($request->getPost('page'));
    if ($page || $page=='') {
      $page=1;
    }
		$offset = ($page - 1) *10;

    //count
    $builder = $this->db->table("TRANSPORT_END as transport_end");
    $builder->select('transport_end.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport_end.USER_IDX', "left inner"); // added left here
    $builder->where('transport_end.USER_IDX', $USER_IDX); 
    $builder->where('transport_end.STATUS', 'E'); 
    $builder->where('transport_end.IS_USER_SHOW', 'Y'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    //select
    $sqldata="SELECT 
    transport_end.TR_IDX,
    ST_X(transport_end.LOC_START) AS LOC_START_LON, 
    ST_Y(transport_end.LOC_START) AS LOC_START_LAT,
    transport_end.CALL_TYPE, 
    transport_end.STATUS, 
    transport_end.USER_IDX,
    transport_end.DRIVER_IDX,
    transport_end.DRIVER_START,
    ST_X(transport_end.LOC_DEST) AS LOC_DEST_LON, 
    ST_Y(transport_end.LOC_DEST) AS LOC_DEST_LAT,
    transport_end.ADDR_START,
    transport_end.ADDR_DEST,
    transport_end.ROUND_TRIP,
    transport_end.E_DISTANCE,
    transport_end.E_FEE,
    transport_end.E_TIME,
    transport_end.E_ARRIVE_TIME,
    transport_end.PET_LIST,
    transport_end.USER_RIDE,
    transport_end.DISTANCE,
    transport_end.TIME,
    transport_end.FEE,
    transport_end.FEE_PAY,
    transport_end.D_FEE,
    transport_end.R_FEE,
    transport_end.P_FEE,
    transport_end.A_FEE,
    transport_end.A_FEE_MEMO,
    transport_end.DC_FEE,
    transport_end.DC_FEE_MEMO,
    transport_end.O_FEE,
    transport_end.USER_MEMO,
    transport_end.MEMO,
    transport_end.RESERVE_TIME,
    transport_end.START_TIME,
    transport_end.ARRIVE_TIME,
    transport_end.CCTV_URL,
    transport_end.IS_USER_SHOW,
    driver_join_info.*
  FROM 
    TRANSPORT_END AS transport_end  
  LEFT JOIN 
    DRIVER_JOIN_INFO AS driver_join_info ON transport_end.DRIVER_IDX=driver_join_info.USER_IDX       
  WHERE 
    transport_end.USER_IDX='{$USER_IDX}' AND transport_end.STATUS='E' AND transport_end.IS_USER_SHOW='Y'";
 
       //print_r($sqldata);
    $result=$this->db->query($sqldata);
    if ($result) {
      $data['transport'] = $result->getResultArray();
      $data['tcount']= $total;
      $data['page']= $page;
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return;       
    }
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
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT_END as transport_end");
    $builder->select('transport_end.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport_end.USER_IDX', "left inner"); // added left here
    $builder->where('transport_end.USER_IDX', $USER_IDX); 
    $builder->where('transport_end.TR_IDX', $TR_IDX); 
    $builder->where('transport_end.STATUS', 'E'); 
    $builder->where('transport_end.IS_USER_SHOW', 'Y'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    //select
    $sqldata="SELECT 
    transport_end.TR_IDX,
    ST_X(transport_end.LOC_START) AS LOC_START_LON, 
    ST_Y(transport_end.LOC_START) AS LOC_START_LAT,
    transport_end.CALL_TYPE, 
    transport_end.STATUS, 
    transport_end.USER_IDX,
    transport_end.DRIVER_IDX,
    transport_end.DRIVER_START,
    ST_X(transport_end.LOC_DEST) AS LOC_DEST_LON, 
    ST_Y(transport_end.LOC_DEST) AS LOC_DEST_LAT,
    transport_end.ADDR_START,
    transport_end.ADDR_DEST,
    transport_end.ROUND_TRIP,
    transport_end.E_DISTANCE,
    transport_end.E_FEE,
    transport_end.E_TIME,
    transport_end.E_ARRIVE_TIME,
    transport_end.PET_LIST,
    transport_end.USER_RIDE,
    transport_end.DISTANCE,
    transport_end.TIME,
    transport_end.FEE,
    transport_end.FEE_PAY,
    transport_end.D_FEE,
    transport_end.R_FEE,
    transport_end.P_FEE,
    transport_end.A_FEE,
    transport_end.A_FEE_MEMO,
    transport_end.DC_FEE,
    transport_end.DC_FEE_MEMO,
    transport_end.O_FEE,
    transport_end.USER_MEMO,
    transport_end.MEMO,
    transport_end.RESERVE_TIME,
    transport_end.START_TIME,
    transport_end.ARRIVE_TIME,
    transport_end.CCTV_URL,
    transport_end.IS_USER_SHOW,
    driver_join_info.*
  FROM 
    TRANSPORT_END AS transport_end  
  LEFT JOIN 
    DRIVER_JOIN_INFO AS driver_join_info ON transport_end.DRIVER_IDX=driver_join_info.USER_IDX       
  WHERE 
    transport_end.USER_IDX='{$USER_IDX}' AND transport_end.TR_IDX='{$TR_IDX}'  AND transport_end.STATUS='E' AND transport_end.IS_USER_SHOW='Y'";
 
       //print_r($sqldata);
    $result=$this->db->query($sqldata);
    if ($result) {
      $data['transport'] = $result->getResultArray();
      $data['tcount']= $total;
      $data['page']= $page;
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return;       
    }
  }



  public function delete() {  
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
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT_END as transport_end");
    $builder->select('transport_end.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport_end.USER_IDX', "left inner"); // added left here
    $builder->where('transport_end.USER_IDX', $USER_IDX); 
    $builder->where('transport_end.TR_IDX', $TR_IDX); 
    $builder->where('transport_end.STATUS', 'E'); 
    $builder->where('transport_end.IS_USER_SHOW', 'Y'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    
    $DELETED_AT = date('Y-m-d H:i:s');
    $newData = ['IS_USER_SHOW'=>'N','DELETED_AT'=>$DELETED_AT,];

    $ReturnData=array(
      'TR_IDX'   => $TR_IDX,
      'USER_IDX'  => $USER_IDX,
      'IS_USER_SHOW'  => 'N',
      'DELETED_AT'  => $DELETED_AT
    );

    $std = new Transport_endModel();
    $std->transBegin();

    $result = $std->update($TR_IDX,$newData);

    if ($std->transStatus() === FALSE) {
      $std->transRollback();
      $message="삭제도중 오류가 발생했습니다.";
      ajaxReturn(RESULT_FAIL,$message,$ReturnData);
      return;
    } else {
      $std->transCommit();          
      $message="삭제하였습니다.";
      ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);
      return;
    }  


  }
  

}