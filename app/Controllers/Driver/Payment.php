<?php
namespace App\Controllers\Driver;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\Member_cardModel;
use App\Models\Member_couponModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\PaymentModel;

class Payment extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }
  
  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
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
        $DRIVER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $DRIVER_IDX = $ACCESS_DATA['user_idx'];
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
        $APP_TYPE = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN  = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 

    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }

    $TR_IDX = esc($request->getPost('tr_idx'));


    //호출 내역
    //count
    $builder_transport = $this->db->table("TRANSPORT as transport");
    $builder_transport->select('transport.TR_IDX');
    //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
    $builder_transport->where(['transport.TR_IDX'=> $TR_IDX,'transport.DRIVER_IDX'=> $DRIVER_IDX,]); 
    $transport_total = $builder_transport->countAllResults();

    if($transport_total>0) {
      //select
      $builder_transport->select('transport.D_FEE as d_fee,transport.R_FEE as r_fee,transport.P_FEE as p_fee,transport.A_FEE as a_fee,');
      //$builder->join('MEMBER as member', 'member.USER_IDX = coupon.USER_IDX', "left inner"); // added left here
      $builder_transport->where(['transport.TR_IDX'=> $TR_IDX,'transport.DRIVER_IDX'=> $DRIVER_IDX,]); 
      $data['transport'] = $builder_transport->get()->getResult('array');   
    }





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

  public function paybycash() {  //현금결제일 경우만
    $request = \Config\Services::request();

    $ACCESS_DATA  = esc($request->getPost('access_data'));  
    $ACCESS_DATA  = json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA  = json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $DRIVER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $DRIVER_IDX = $ACCESS_DATA['user_idx'];
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
        $DEVICE_ID  = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN  = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    if(!$DRIVER_IDX){
      ajaxReturn(RESULT_FAIL,"사용자 정보가 넘어오지 않았습니다.","");
      return;
    }
   
    $TR_IDX       = esc($request->getPost('tr_idx'));
    $coupon_list  = esc($request->getPost('coupon_list'));
    $point        = esc($request->getPost('point')); 
    $D_FEE        = esc($request->getPost('d_fee'));
    $O_FEE        = esc($request->getPost('o_fee'));
    $P_FEE        = esc($request->getPost('p_fee'));
    $A_FEE        = esc($request->getPost('a_fee'));
    $DC_FEE       = esc($request->getPost('dc_fee'));
    $r_fee        = esc($request->getPost('r_fee'));
    $fee          = esc($request->getPost('fee'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.DRIVER_IDX', $DRIVER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"현금 결제를 진행 할 호출이 없습니다.","");
      return;        
    }
    
    //select
    $sqldata="SELECT 
    transport.TR_IDX,
    ST_X(transport.LOC_START) AS LOC_START_LON, 
    ST_Y(transport.LOC_START) AS LOC_START_LAT,
    transport.CALL_TYPE, 
    transport.STATUS, 
    transport.USER_IDX,
    transport.DRIVER_IDX,
    ST_X(transport.DRIVER_START) AS DRIVER_DEST_LON, 
    ST_Y(transport.DRIVER_START) AS DRIVER_DEST_LAT,
    ST_X(transport.LOC_DEST) AS LOC_DEST_LON, 
    ST_Y(transport.LOC_DEST) AS LOC_DEST_LAT,
    transport.ADDR_START,
    transport.ADDR_DEST,
    transport.ROUND_TRIP,
    transport.E_DISTANCE,
    transport.E_FEE,
    transport.E_TIME,
    transport.E_ARRIVE_TIME,
    transport.PET_LIST,
    transport.USER_RIDE,
    transport.DISTANCE,
    transport.TIME,
    transport.FEE,
    transport.FEE_PAY,
    transport.D_FEE,
    transport.R_FEE,
    transport.P_FEE,
    transport.A_FEE,
    transport.A_FEE_MEMO,
    transport.DC_FEE,
    transport.DC_FEE_MEMO,
    transport.O_FEE,
    transport.USER_MEMO,
    transport.MEMO,
    transport.RESERVE_TIME,
    transport.C_IDX,
    transport.START_TIME,
    transport.ARRIVE_TIME,
    transport.CCTV_URL,
    transport.IS_USER_SHOW,
    member.NICK_NAME,
    member.PHONE
    FROM 
      TRANSPORT AS transport
    INNER JOIN MEMBER AS member ON transport.USER_IDX=member.USER_IDX
    WHERE 
      transport.TR_IDX='{$TR_IDX}' AND transport.DRIVER_IDX='{$DRIVER_IDX}'";

    //print_r($sqldata);
    $result=$this->db->query($sqldata);

    if($result) {
      $data['transport'] = $result->getResultArray();
     // print_r($data); 
      $data['tcount']= $total;
      //USER정보를 가져온다.
      $USER_IDX=$data['transport'][0]['USER_IDX'];

      if ($data['transport'][0]['C_IDX']!='') {
        // 쿠폰은 사용으로 변경
        $CP_newData = ['IS_USED'=>'Y',];
        $CP_std = new Member_couponModel();    
        $CP_result = $CP_std->update($data['transport'][0]['C_IDX'],$CP_newData);
      }

      // 포인트 차감

      // PAYMENT에 넣는다.

      // 드라이버 수입에서 차감한다. 추후 차감할 현금이 부족한경우는 어떻게 할 것인지 정한다.
      //현재는 -는 안되게 해야 할 것 같다. 20.05-10

      $up_sql = "UPDATE MEMBER SET ";
      $up_sql.= "POINT  = POINT - {$fee} ";
      $up_sql.= "WHERE USER_IDX = '{$DRIVER_IDX}' ";         
      $up_sql_result=$this->db->query($up_sql);

      // 확인이 필요 없는 데이타는 TRANSPORT_END 로 이동한다.     
      $MoveData="INSERT INTO TRANSPORT_END SELECT * FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";  
      $this->db->query($MoveData);

      $DeleteData="DELETE FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";
      $this->db->query($DeleteData);  
      
      //사용자에게 푸쉬




      $ReturnData = array(
        'TR_IDX'  => $TR_IDX
      );

      ajaxReturn(RESULT_SUCCESS,"정상적으로 결제되었습니다.",$ReturnData);  
      return; 
    } else {
      ajaxReturn(RESULT_FAIL,"",$ReturnData);
      return;     
    }
  }

}