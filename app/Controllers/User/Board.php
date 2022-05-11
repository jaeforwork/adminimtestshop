<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\BoardModel;

class Board extends BaseController
{
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }
    $board  = esc($request->getPost('board'));
   
    if($board=='notice'){
      $board='NOTICE';
    } else if($board=='fna'){
      $board='FNA';
    } else if($board=='fqna'){
      $board='FQNA';
    } else {
      ajaxReturn(RESULT_FAIL,"보드선택오류 입니다.","");
      return;  
    }
    
    //count
    $builder = $this->db->table('BOARD_'.$board.' as board');
    $builder->select('board.*');
    $builder->where('board.DISP', 'Y'); 
    $total = $builder->countAllResults();

    if($total==0){
       ajaxReturn(RESULT_EMPTY,"글이 없습니다.","");
      return;  
    }
    //select
    $builder->select('board.IDX, board.TITLE, board.CONTENT, board.VIEW_COUNT, board.CREATED_AT');
    $builder->where('board.DISP', 'Y');  

    $data['board'] = $builder->get()->getResult('array');   
    $data['total']=$total;
    ajaxReturn(RESULT_SUCCESS,"",$data);      
        
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }

    $idx    = esc($request->getPost('idx'));
    $board  = esc($request->getPost('board'));

    if(!$idx){
      ajaxReturn(RESULT_EMPTY,"공지사항이 없습니다.");
      return;
    }

   
    if($board=='notice'){
      $board='NOTICE';
    } else if($board=='fna'){
      $board='FNA';
    } else if($board=='fqna'){
      $board='FQNA';
    } else {
      ajaxReturn(RESULT_FAIL,"글이 없습니다.","");
      return;  
    }
    
    //count
    $builder = $this->db->table('BOARD_'.$board.' as board');
    $builder->select('board.*');
    $builder->where('board.IDX', $idx);  
    $total = $builder->countAllResults();

    //select
    $builder->select('board.IDX, board.TITLE, board.CONTENT, board.VIEW_COUNT, board.CREATED_AT');
    $builder->where('board.DISP', 'Y'); 
    $builder->where('board.IDX', $idx);   

    $data['board'] = $builder->get()->getResult('array');   
    $data['total']=$total;
    ajaxReturn(RESULT_SUCCESS,"",$data);    

       

  }



}
