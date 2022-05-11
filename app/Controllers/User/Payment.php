<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\PaymentModel;
use App\Models\Payment_linkModel;

class Payment extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }
  
  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }
  
  public function askpay() { //조르기 결제 링크
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

    $TR_IDX = esc($request->getPost('tr_idx'));
    $fee    = esc($request->getPost('fee'));

    //쿠폰리스트
    //count
    $CREATED_AT = date('Y-m-d H:i:s');

    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder->where(['transport.TR_IDX'=> $TR_IDX,]); 
    $total = $builder->countAllResults();

    if($total>0) {
      $ENDED_AT   = date('Y-m-d H:i:s',strtotime('+20 minutes',strtotime($CREATED_AT)));
      $rand = generateRandomString('T',4);
      $link=  $rand.$TR_IDX; //추후 TR_IDX가 길어질것에 대비해서 잘라서 사용한다.
      $PAYLINK= "http://pay.petgel.com/paylink/".$link;

      $NewData = array(
        'USER_IDX'  => $USER_IDX,
        'TR_IDX'    => $TR_IDX,
        'FEE'       => $fee,
        'URL'       => $link,
        'STATUS'    => 'Y',
        'ENDED_AT'  => $ENDED_AT
      );

      $ReturnData=array(
        'PAYLINK'   => $PAYLINK,
        'FEE'       => $fee,
        'ENDED_AT'  => $ENDED_AT
      );

      $std = new Payment_linkModel();
      $std->transBegin();

      $std->insert($NewData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='링크생성 실패';
        ajaxReturn(RESULT_FAIL,$message,$NewData);
        return;
      } else {
        $std->transCommit();
        $message= '링크생성 완료';
        ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
        return;
      }
    } else {
      $message='결제할 호출이 없음';
      ajaxReturn(RESULT_FAIL,$message,$NewData);
      return;
    }
  }



  public function prepaylist() {  //결제 전 정보 전송    
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

    $TR_IDX =esc( $request->getPost('tr_idx'));
    $page   = esc($request->getPost('page'));

    if($page || $page==''){
      $page=1;
    }
		$offset = ($page - 1) *10;



    //쿠폰리스트
    //count
    $builder_coupon = $this->db->table("MEMBER_COUPON as coupon");
    $builder_coupon->select('coupon.*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder_coupon->where('coupon.USER_IDX', $USER_IDX); 
    $coupon_total = $builder_coupon->countAllResults();

    if($coupon_total>0) {
      //select
      $builder_coupon->select('coupon.*');
      //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      $builder_coupon->where('payment.USER_IDX', $USER_IDX); 
      $data['coupon'] = $builder_coupon->get()->getResult('array');   
    }
    $data['coupon_count'] = $coupon_total;  

    //포인트 리스트    
    //count
    $builder_point = $this->db->table("MEMBER as member");
    $builder_point->select('member.*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder_point->where('member.USER_IDX', $USER_IDX); 
    $point_total = $builder_point->countAllResults();

    //select
    $builder_point->select('member.POINT AS TOTAL_POINT');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder_point->where('member.USER_IDX', $USER_IDX); 
    $data['point'] = $builder_point->get()->getResult('array');   
    //$data['point_total'] = $point_total;  


    //기본요금
    //할인요금
    //결제수단

    //count
    $builder_card = $this->db->table("MEMBER_CARD as member_card");
    $builder_card->select('member_card.*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder_card->where(['member_card.USER_IDX'=>$USER_IDX,'member_card.DISP'=>'Y','member_card.BILLKEY !=' =>'',]); 
    $card_total = $builder_card->countAllResults();

    if($card_total>0) {
      //select
      $builder_card->select('member_card.IDX AS CARD_IDX, member_card.CARD_NAME');
      //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      $builder_card->where(['member_card.USER_IDX'=>$USER_IDX,'member_card.DISP'=>'Y','member_card.BILLKEY !=' =>'',]); 
      $data['card'] = $builder_card->get()->getResult('array');   
    }
    $data['card_total'] = $card_total;  












    $data['count']= $total;
    ajaxReturn(RESULT_SUCCESS,'',$data);  
    return;       
  }


  public function list() {  //결제내역 리스트
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
    $page   = esc($request->getPost('page'));
    if($page || $page==''){
      $page=1;
    }
		$offset = ($page - 1) *10;

    //count
    $builder = $this->db->table("PAYMENT as payment");
    $builder->select('payment.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = payment.USER_IDX', "left inner"); // added left here
    $builder->where('payment.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,'',"");
      return;        
    }
    //select
    $builder->select('payment.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = payment.USER_IDX', "left inner"); // added left here
    $builder->where('payment.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
    $data['page']= $page;
    ajaxReturn(RESULT_SUCCESS,'',$data);  
    return;       
  }


  public function view() {  //결제내역 상세정보
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
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("PAYMENT as payment");
    $builder->select('payment.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = payment.USER_IDX', "left inner"); // added left here
    $builder->where('payment.TR_IDX', $TR_IDX);  
    $builder->where('payment.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,'',"");
      return;        
    }
    //select
    $builder->select('payment.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = payment.USER_IDX', "left inner"); // added left here
    $builder->where('payment.TR_IDX', $TR_IDX);  
    $builder->where('payment.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
   
    ajaxReturn(RESULT_SUCCESS,'',$data);  
    return;       
  }





}