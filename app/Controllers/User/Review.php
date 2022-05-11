<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\Review_userModel;

use App\Libraries\ValidChecker;

class Review extends BaseController {  
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();       
  }

  public function post() {
    $security = \Config\Services::security();
    //helper('text');

    $request = \Config\Services::request();

    $ACCESS_DATA  = esc($request->getPost('access_data'));  
    
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    //print_r($ACCESS_DATA);
    //exit;

    $ACCESS_DATA=json_decode($ACCESS_DATA,true);

    $USER_IDX     = $ACCESS_DATA['user_idx'];
    $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
    $DEVICE_ID    = $ACCESS_DATA['device_id'];
    $APP_TYPE     = $ACCESS_DATA['app_type'];

    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return;
    }
    
    $TR_IDX   = esc($request->getPost('tr_idx'));
    $STAR     = esc($request->getPost('star'));  
    $COMMENT  = esc($request->getPost('comment'));





    //$lib = new ValidChecker();

    $check = check_csfs($COMMENT);
 
    if($check) {
      $message= '특수문자 포함됨';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    }
    

    //if (!$this->validate($rules, $messages)) {
        // return view("add-member", [
        //     "validation" => $this->validator,

      //  $msg= 'validation error';
       // echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
        // ]);
    //} else {
     
    // $NICK_NAME = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $NICK_NAME);



    // $disallowed = ['<sc', '?', '*', 'phooey'];
    // $NICK_NAME     = word_censor($NICK_NAME, $disallowed, 'x');

    if(!$ACCESS_TOKEN || $ACCESS_TOKEN=='' || empty($ACCESS_TOKEN)) {
      $message= '로그인 후 이용 하세요.';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    }

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {

      //드리아버 idx를 가져온다.
      //호출이 정상적이지 않을 경우 여기서 종료 시킴

      //count
      $builder = $this->db->table("TRANSPORT_END as transport_end");
      $builder->select('transport_end.*');
      $builder->where('transport_end.TR_IDX', $TR_IDX);  
      $total = $builder->countAllResults();

      if (!$total || $total==0) {
        $message='평가할 호출 정보가 없습니다.';
        ajaxReturn(RESULT_FAIL,$message,"");
        return;
      }

      //select
      $builder->select('transport_end.DRIVER_IDX');
      $builder->where('transport_end.TR_IDX', $TR_IDX);  

      $data['transport'] = $builder->get()->getResult('array');   

      $DRIVER_IDX=$data['transport'][0]['DRIVER_IDX'];
//       print_r($data);
// print_r($DRIVER_IDX);

      $newData = array(
        'USER_IDX'    => $USER_IDX,
			  'DRIVER_IDX'  => $DRIVER_IDX,
			  'TR_IDX'      => $TR_IDX,
			  'STAR'        => $STAR,		
        'COMMENT'     => $COMMENT		
	  	);

      //중복 체크할지는 나중에

      $std = new Review_userModel();
      $std->transBegin();

      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='';
        ajaxReturn(RESULT_FAIL,$message,"");
      } else {
        $std->transCommit();
        //$PET_IDX = $std->getInsertID();

        $ReturnData=array(
          'USER_IDX'    => $USER_IDX,
          'DRIVER_IDX'  => $DRIVER_IDX,
          'TR_IDX'      => $TR_IDX,
          'STAR'        => $STAR,		
          'COMMENT'     => $COMMENT			
        );
       /// echo $db->insertID();

        $message= '';
        ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
      }
    } else {
      $message='';
      ajaxReturn(RESULT_FAIL,$message,$ReturnData); 
    }
  //}

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
        ajaxReturn(RESULT_FAIL,"로그인하셔야 합니다.","");
        return;
      }
  
      //count
      $builder = $this->db->table("REVIEW_DRIVER as review_driver");
      $builder->select('review_driver.*');
      $builder->where('review_driver.USER_IDX', $USER_IDX); 
      $total = $builder->countAllResults();

      if (!$total || $total==0) {
        $message='등록되어 있는 정보가 없습니다.';
        ajaxReturn(RESULT_FAIL,$message,"");
        return;
      }
  
      //select
      $builder->select('review_driver.*');
      $builder->where('review_driver.USER_IDX', $USER_IDX); 
  
      $data['review_driver'] = $builder->get()->getResult('array');   
      $data['total'] = $total;   
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
        ajaxReturn(RESULT_FAIL,'',"");
        return;
      }

      $IDX     = esc($request->getPost('idx'));

      //count
      $builder = $this->db->table("REVIEW_DRIVER as review_driver");
      $builder->select('review_driver.*');
      $builder->where('review_driver.IDX', $IDX);
      $builder->where('review_driver.USER_IDX', $USER_IDX);  
      $total = $builder->countAllResults();
      if($total==0) {        
        ajaxReturn(RESULT_FAIL,"","");
        return;        
      }

      //select
      $builder->select('review_driver.*');
      $builder->where('review_driver.IDX', $IDX); 
      $builder->where('review_driver.USER_IDX', $USER_IDX); 
      $data['review_driver'] = $builder->get()->getResult('array');   
      ajaxReturn(RESULT_SUCCESS,"",$data); 
      return;
    }
}

