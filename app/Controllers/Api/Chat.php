<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Chat_singleModel;
use App\Models\Chat_room_multiModel;
use App\Models\Chat_multiModel;
use App\Models\App_push_messagesModel;

//use App\Libraries\ValidChecker;
use App\Libraries\Pushnoti;

class Chat extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }  
  
  public function room() {   
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }
    $TR_IDX  = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table('CHAT_ROOM_MULTI AS chat_room_multi');
    $builder->select('chat_room_multi.*');
    $builder->where(['chat_room_multi.TR_IDX'=> $TR_IDX,'chat_room_multi.DELETED_AT IS NULL'=> NULL]); 
    $total = $builder->countAllResults();

    if($total==0){
       ajaxReturn(RESULT_FAIL,"채팅방이 없습니다.","");
      return;  
    }
    //select
    $builder->select('chat_room_multi.*');
    $builder->where(['chat_room_multi.TR_IDX'=> $TR_IDX,'chat_room_multi.DELETED_AT IS NULL'=> NULL]); 

    $ReturnData['chat_room'] = $builder->get()->getResult('array');      

    $this->db->transCommit();          
    $message='';
    $ReturnData['total']=$total;
    ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);    
    return;         
  }




  public function get() {
   
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }

    $TR_IDX  = esc($request->getPost('tr_idx'));
    //Room 번호를 가져온다.

    //count
    $builder = $this->db->table('CHAT_ROOM_MULTI AS chat_room_multi');
    $builder->select('chat_room_multi.ROOM_IDX');
    $builder->where(['chat_room_multi.TR_IDX'=> $TR_IDX,]); 
    
    $total = $builder->countAllResults();

    if($total==0){
       ajaxReturn(RESULT_FAIL,"채팅 방이 없습니다.","");
      return;  
    }
    //select
    $builder = $this->db->table('CHAT_ROOM_MULTI AS chat_room_multi');
    $builder->select('chat_room_multi.ROOM_IDX');
    $builder->where(['chat_room_multi.TR_IDX'=> $TR_IDX,]); 

    $data['chat_room'] = $builder->get()->getResult('array');  
    $ROOM_IDX = $data['chat_room'][0]['ROOM_IDX'];

    unset($data);
    //Room 번호

    //count
    $builder = $this->db->table('CHAT_MULTI AS chat_multi');
    $builder->select('chat_multi.*');
    $builder->where('chat_multi.ROOM_IDX', $ROOM_IDX); 

    $total = $builder->countAllResults();

    if($total==0){
       ajaxReturn(RESULT_FAIL,"글이 없습니다.","");
      return;  
    }
    //select
    $builder = $this->db->table('CHAT_MULTI AS chat');
    $builder->select('chat.*');
    $builder->where(['chat.ROOM_IDX'=> $ROOM_IDX,]); 
    $builder->orderBy('CHAT_IDX','ASC');
    $ReturnData['chat'] = $builder->get()->getResult('array');  

    $message='';
    $ReturnData['total']=$total;
    ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);    
    return; 
  }










}