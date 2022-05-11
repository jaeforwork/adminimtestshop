<?php
  namespace App\Controllers\Driver;
  
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
    $order  = esc($request->getPost('order'));
    $by     = esc($request->getPost('by'));
    $page   = esc($request->getPost('page'));

    if (!$order || $order=="" || $order=="created_at") {
      $order  = "point.CREATED_AT";
    } else if ($order=="idx"){
      $order  = "point.IDX";
    }

    if (!$by || $by=="" || $by=="DESC") {
      $by  = "DESC";
    } else {
      $by  = "ASC";
    }   

    if(!$page || $page=="" || $page=="1") {
      $page=1;
      $from=0;     
    } else {
      $from=($page * 10)-10; 
    }
    
    //count
    $builder = $this->db->table("MEMBER_POINT as point");
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where(['point.USER_IDX'=> $USER_IDX,]); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"검색결과가 없습니다.","");
      return;        
    }
    
    if($total-$from>9) {
      $to=9;    
    } else {
      $to=$total-$from;
    }

    // print_r($total);
    // print_r("total <br>");
    // print_r($to);
    // print_r("to <br>");
    // print_r($from);
    // print_r(" from <br>");

    //select
    $builder->select('point.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = point.USER_IDX', "left inner"); // added left here
    $builder->where('point.USER_IDX', $USER_IDX); 
    $builder->orderBy($order, $by);
    $builder->limit($to, $from);
    //echo $builder->limit($to, $from)->getCompiledSelect(false);
    
    //print_r($builder);
    $data['pointlist'] = $builder->get()->getResult('array');   

    //select
    $builder->select('sum(point.UPO_GET_POINT) AS get_point, sum(point.UPO_USE_POINT) AS use_point,');
    $builder->where('point.USER_IDX', $USER_IDX); 
    $builder->orderBy($order, $by);
    $builder->limit($to, $from);
    $data['sum_point'] = $builder->get()->getResult('array'); 
    //echo $builder->limit($to, $from)->getCompiledSelect(false);

    $data['sum_point'][0]['total_point']= number_format($data['sum_point'][0]['GET_POINT'] - $data['sum_point'][0]['USE_POINT']);
    $data['sum_point'][0]['get_point']= number_format($data['sum_point'][0]['get_point']);
    $data['sum_point'][0]['use_point']= number_format($data['sum_point'][0]['use_point']);

    $data['tcount'] = $total;
    $data['page']   = $page;
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


  



}