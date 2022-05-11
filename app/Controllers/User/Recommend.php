<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\Shop_listModel;

class Recommend extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }

  public function shop_list() {  
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
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }
   
    $LOC_LON      = esc($request->getPost('loc_lon'));
    $LOC_LAT      = esc($request->getPost('loc_lat'));

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {
      //count

      $sqldata="SELECT 
                  count(IDX) AS total 
                FROM 
                SHOP_LIST                            
                WHERE 
                ST_DISTANCE_SPHERE(LOC, POINT($LOC_LON, $LOC_LAT)) <=2000";
      //print_r($sqldata);
      $query = $this->db->query($sqldata);

      foreach ($query->getResult() as $row) {
        $total=$row->total;
      }

      if(!$total || $total==0) {        
        ajaxReturn(RESULT_FAIL,"근처에 추천할 곳이 없습니다.","");
        return;        
      }
 
      $sqldata="SELECT 
                shop_list.IDX,
                shop_list.SHOP_NAME, 
                shop_list.ADD1, 
                shop_list.ADD2,
                shop_list.ADD3,
                ST_X(shop_list.LOC) AS LOC_LON, 
                ST_Y(shop_list.LOC) AS LOC_LAT,
                
                shop_list.PHONE,
                shop_list.KIND1,
                shop_list.KIND2,
                shop_list.MEMO1,
                shop_list.MEMO2,
                shop_list.CREATED_AT
              FROM 
                SHOP_LIST AS shop_list         
              WHERE 
                ST_DISTANCE_SPHERE(shop_list.LOC, POINT($LOC_LON, $LOC_LAT)) <=2000";  //2km 이내

      // print_r($sqldata);
      $result=$this->db->query($sqldata);
      if($result) {
        $data['shop_list'] = $result->getResultArray(); 
        $data['tcount']= $total;     
        $message='';
        ajaxReturn(RESULT_SUCCESS,$message,$data); 
        return;
      } else {
        $message='';
        ajaxReturn(RESULT_FAIL,$message,$data); 
        return;
      }            
    }
  }    





  public function shop_view() {  
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
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }
   
    $SHOP_IDX      = esc($request->getPost('shop_idx'));

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {
      //count

      $sqldata="SELECT 
                  count(IDX) AS total 
                FROM 
                  SHOP_LIST                            
                WHERE 
                  SHOP_IDX='{$SHOP_IDX}'";
      //print_r($sqldata);
      $query = $this->db->query($sqldata);

      foreach ($query->getResult() as $row) {
        $total=$row->total;
      }

      if(!$total || $total==0) {        
        ajaxReturn(RESULT_FAIL,"근처에 추천할 곳이 없습니다.","");
        return;        
      }
 
      $sqldata="SELECT 
                shop_list.IDX,
                shop_list.SHOP_NAME, 
                shop_list.ADD1, 
                shop_list.ADD2,
                shop_list.ADD3,
                ST_X(shop_list.LOC) AS LOC_LON, 
                ST_Y(shop_list.LOC) AS LOC_LAT,                
                shop_list.PHONE,
                shop_list.KIND1,
                shop_list.KIND2,
                shop_list.MEMO1,
                shop_list.MEMO2,
                shop_list.CREATED_AT
              FROM 
                SHOP_LIST AS shop_list         
              WHERE 
                SHOP_IDX='{$SHOP_IDX}'";

      // print_r($sqldata);
      $result=$this->db->query($sqldata);
      if($result) {
        $data['shop_list'] = $result->getResultArray(); 
        $data['tcount']= $total;     
        $message='';
        ajaxReturn(RESULT_SUCCESS,$message,$data); 
        return;
      } else {
        $message='';
        ajaxReturn(RESULT_FAIL,$message,$data); 
        return;
      }            
    }
  }    



}