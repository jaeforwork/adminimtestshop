<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\Member_cardModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\PaymentModel;

class Card extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }
  
  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }

  public function test_pay() {  
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
      ajaxReturn(RESULT_FAIL,"조회할 사용자 정보가 넘어오지 않았습니다.",$ACCESS_DATA);
      return;
    }
    $rand=generateRandomString('n',8);

    $orderid    = $USER_IDX.$rand;
    $itemname   = '카드테스트 결제';
    $amount     = '';
    // $userphone  = esc($request->getPost('userphone'));
    // $useremail  = esc($request->getPost('useremail'));
    // $userid     = esc($request->getPost('userid'));
    $useragent  = 'ONLINE';

    //pg사에 결제를 요청하고 승인 값을 받아 온후 진행 한다.



    //등록된 카드 정보를 가져온다.

    //
    $RESULT   = "Y";
    $CARD_IDX = "10";

    $ReturnData = array(
      'RESULT'    => $RESULT,
      'CARD_IDX'  => $CARD_IDX
    );
        
    ajaxReturn(RESULT_SUCCESS,"카드 테스트 승인이 완료되었습니다.",$ReturnData);  
    return;   
    
    //카드 승인이 실패 한 경우

    $REASON ="20"; //실패 코드

    $ReturnData = array(
      'RESULT'    => $RESULT,
      'REASON'    => $REASON
    );
    
    if($RESULT=='N'){
      ajaxReturn(RESULT_FAIL,"카드 테스트 승인이 실패하였습니다.",$ReturnData);
      return;
    }
    



  }

  public function reg() {     
    $request = \Config\Services::request();
    $encrypter = \Config\Services::encrypter();

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
        $REFRESH_TOKEN  = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 

    if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
      echo "--> 사무실 IP에서만 보임 (디버깅)<br>";
      echo "result <br>";
      print_r($USER_IDX);
      echo "<br>";
      echo "사무실 IP에서만 보임 (디버깅) <--<br>";
    } 



    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"조회할 사용자 정보가 넘어오지 않았습니다.",$ACCESS_DATA);
      return;
    }

    //$rand=generateRandomString('n',8);

    $CARD_NAME  = esc($request->getPost('card_name'));
    $CARD_NUM   = esc($request->getPost('card_num')); //카드번호
    $MONTH      = esc($request->getPost('month'));
    $YEAR       = esc($request->getPost('year'));
    $CVC        = esc($request->getPost('cvc'));
    $PASSWD     = esc($request->getPost('passwd'));
    $OWNER_NUM  = esc($request->getPost('jumin_number'));

      

    //count
    $builder = $this->db->table("MEMBER_CARD as member_card");
    $builder->select('member_card.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = member_card.USER_IDX', "left inner"); // added left here
    $builder->where('member_card.CARD_NUM', $CARD_NUM); 
    $builder->where('member_card.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();

    if($total>0) {        
      //중복확인을 한다.







      ajaxReturn(RESULT_FAIL,"이미 등록된 카드입니다.","");
      return;        
    }


    //카드 시험 결제를 한다.

    $rand=generateRandomString('n',8);

    $orderid    = $USER_IDX.$rand;
    $itemname   = '카드테스트 결제';
    $amount     = '';
    // $userphone  = esc($request->getPost('userphone'));
    // $useremail  = esc($request->getPost('useremail'));
    // $userid     = esc($request->getPost('userid'));
    $useragent  = 'ONLINE';

    //pg사에 결제를 요청하고 승인 값을 받아 온후 진행 한다.


    $OWNER_NUM = substr($OWNER_NUM, 0, 8)."******"; //주민번호 뒷자리 암호화

    $encrypt_OWNER_NUM = base64_encode($encrypter->encrypt($OWNER_NUM)); 
    $encrypt_CARD_NUM = base64_encode($encrypter->encrypt($CARD_NUM)); 
      
// print_r($encrypt_CARD_NUM);
// exit;

    $std = new Member_cardModel();
    $std->transBegin();

//실제 데이타  
    // $newData = ['USER_IDX'=>$USER_IDX, 'CARD_NAME'=>$CARD_NAME,'TYPE'=>$TYPE,'CARD_NUM'=>$CARD_NUM,'MONTH'=>$MONTH,'YEAR'=>$YEAR,'CVS'=>$CVC,'OWNER_NUM'=>$OWNER_NUM];
//실제데이타

//가상 테스트용
    $newData = ['USER_IDX'=>$USER_IDX, 'CARD_NAME'=>'국민카드','TYPE'=>'마스터','CARD_NUM'=>$CARD_NUM,'MONTH'=>$MONTH,'YEAR'=>$YEAR,'CVS'=>$CVC,'OWNER_NUM'=>$encrypt_OWNER_NUM];
//가상 테스트용

    $std->insert($newData);

    if ($std->transStatus() === FALSE) {
      $std->transRollback();
      $message='처리도중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
    } else {
      $std->transCommit();
  
      $CARD_IDX = $std->getInsertID();
      //select
      $builder->select('member_card.*, member.*');
      $builder->join('MEMBER as member', 'member.USER_IDX = member_card.USER_IDX', "left inner"); // added left here
      $builder->where('member_card.IDX', $CARD_IDX);  
      $builder->where('member_card.USER_IDX', $USER_IDX); 

      $ReturnData['member_card'] = $builder->get()->getResult('array');   
      $ReturnData['tcount']= $total;
              
      ajaxReturn(RESULT_SUCCESS,"카드 테스트 승인이 완료되었습니다.",$ReturnData);  
      return;  
    } 


          
    //카드 승인이 실패 한 경우
    if ($RESULT=='N') {
      $REASON ="20"; //실패 코드

      $ReturnData = array(
        'RESULT'    => $RESULT,
        'REASON'    => $REASON
      );
      ajaxReturn(RESULT_FAIL,"카드 테스트 승인이 실패하였습니다.",$ReturnData);
      return;
    }

  }

  public function bill() {  
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
   
    $TR_IDX       = esc($request->getPost('tr_idx'));
    $coupon_list  = esc($request->getPost('coupon_list'));
    $point        = esc($request->getPost('point')); //카드번호
    $D_FEE        = esc($request->getPost('D_FEE'));
    $O_FEE        = esc($request->getPost('O_FEE'));
    $P_FEE        = esc($request->getPost('P_FEE'));
    $A_FEE        = esc($request->getPost('A_FEE'));
    $DC_FEE       = esc($request->getPost('DC_FEE'));
    $r_fee        = esc($request->getPost('r_fee'));
    $fee          = esc($request->getPost('fee'));
    $card_idx     = esc($request->getPost('card_idx'));


    $orderid    = '';
    $itemname   = 'TestItem';
  
    $userphone  = '';
    $useremail  = '';
    $userid     = '';
    $useragent  = 'ONLINE';







    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,'',"");
      return;        
    }
    //select
    $builder->select('transport.TR_IDX, transport.USER_IDX, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
   

    // 가져온 정보와 비교한다.

    // 결제 요청은 한다


    // 결제 완료시

    // 결제정보와 요청 정보를 비교한다.

      // 쿠폰은 사용으로 변경

      // 포인트 차감

      // PAYMENT에 넣는다.








  // $ReturnData = array(
  //   'APPROVED_IDX'  => $APPROVED_IDX,
  //   'PAYMENT_IDX'   => $CP_END
  // );

  $ReturnData = array(
    'APPROVED_IDX'  => '150',
    'PAYMENT_IDX'   => $CP_END
  );







    ajaxReturn(RESULT_SUCCESS,"정상적으로 결제되었습니다.",$ReturnData);  
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
    $builder = $this->db->table("MEMBER_CARD as card");
    $builder->select('card.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = card.USER_IDX', "left inner"); // added left here
    $builder->where('member.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"해당 사용자의 정보가 없습니다.","");
      return;        
    }
    //select
    $builder->select('card.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = card.USER_IDX', "left inner"); // added left here
    $builder->where('member.USER_IDX', $USER_IDX); 
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
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }
   
    $IDX   = esc($request->getPost('idx'));

    //count
    $builder = $this->db->table("MEMBER_CARD as card");
    $builder->select('card.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = card.USER_IDX', "left inner"); // added left here
    $builder->where('card.IDX', $IDX);  
    $builder->where('card.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,'',"");
      return;        
    }
    //select
    $builder->select('card.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = card.USER_IDX', "left inner"); // added left here
    $builder->where('card.IDX', $IDX);  
    $builder->where('card.USER_IDX', $USER_IDX); 
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
   
    ajaxReturn(RESULT_SUCCESS,'',$data);  
    return;       
  }





}