<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\PaymentModel;
use App\Models\Member_cardModel;


use App\Libraries\Danal;

class Payment extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }
  
  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }


  public function getbillkey() {  
    $Danal     = new Danal();
    $dataMessage  = array(); //notification 데이터
    $dataMessage['ISBILL']      = "Y";
    $dataMessage['ORDERID']     = "ORDERID";
    $dataMessage['ITEMNAME']    = "ITEMNAME";
    $dataMessage['AMOUNT']      = "1000"; 
    $dataMessage['CARDCODE']    = "CARDCODE";
    $dataMessage['CARDNAME']    = "CARDNAME";
    $dataMessage['CARDNO']      = "CARDNO"; 
    $dataMessage['QUOTA']       = "00";
    $dataMessage['CARDAUTHNO']  = "CARDAUTHNO"; 
    $dataMessage['USERNAME']    = "USERNAME"; 
    $dataMessage['USERPHONE']   = "USERPHONE"; 
    $dataMessage['USERID']      = "USERID"; 

    // print_r($returnData['results'][0]['message_id']);
    $returnData = $Danal->getbillkey("test",  $dataMessage,"dsafsaf");


    print_r($returnData);
    print_r("<br>");
    print_r($returnData['RETURNCODE']);
    print_r("<br>");
    print_r($returnData['RETURNMSG']);
    print_r("<br>");
    print_r($returnData['TID']);
    print_r("<br>");
    print_r($returnData['ISBILL']);
    print_r("<br>");
    print_r($returnData['BILLKEY']);
    print_r("<br>");
    print_r($returnData['ORDERID']);
    print_r("<br>");
    print_r($returnData['ITEMNAME']);
    print_r("<br>");
    print_r($returnData['AMOUNT']);
    print_r("<br>");
    print_r($returnData['TRANDATE']);
    print_r("<br>");
    print_r($returnData['TRANTIME']);
    print_r("<br>");
    print_r($returnData['CARDCODE']);
    print_r("<br>");
    print_r($returnData['CARDNAME']);
    print_r("<br>");
    print_r($returnData['CARDNO']);
    print_r("<br>");
    print_r($returnData['QUOTA']);
    print_r("<br>");
    print_r($returnData['CARDAUTHNO']);
    print_r("<br>");
    print_r($returnData['USERNAME']);
    print_r("<br>");
    print_r($returnData['USERPHONE']);
    print_r("<br>");
    print_r($returnData['USERID']);
    // print_r("<br>");
    // print_r($returnData['multicast_id']);
    // print_r("<br>");
    // print_r($returnData['results'][0]['message_id']);

//Array ( [RETURNCODE] => 1234 [RETURNMSG] => RETURNMSG [TID] => TID [ISBILL] => Y [BILLKEY] => BILLKEY [ORDERID] => ORDERID [ITEMNAME] => ITEMNAME [AMOUNT] => 1000 [TRANDATE] => 20220411 [TRANTIME] => HHmmss [CARDCODE] => CARDCODE [CARDNAME] => CARDNAME [CARDNO] => 1111-11**-****-1111 [QUOTA] => 00 [CARDAUTHNO] => CARDAUTHNO [USERNAME] => USERNAME [USERPHONE] => USERPHONE [USERID] => USERID )




  if (!empty($returnData['RETURNCODE']) && $returnData['RETURNCODE']==1234) {
    $newData = array(
      'USER_IDX'      => $USER_IDX,
      'TR_ID'         => $TR_ID,
      'TR_IDX'        => $TR_IDX,	
      'METHOD_IDX'    => $METHOD_IDX,
      'PRICE'         => $PRICE,
      'NET_PRICE'     => $NET_PRICE,
      'FEE_PG'        => $FEE_PG,
      'STATUS'        => $STATUS,
      'PG_IDX'        => $PG_IDX,	
      'REF_PGIDX'     => $REF_PGIDX,
      'RBANK_NAME'    => $RBANK_NAME,
      'RBANK_ACCOUNT' => $RBANK_ACCOUNT,	
      'PAYDATE'       => $PAYDATE,
      'CANCELDATE'    => $CANCELDATE,	
      'PG_DATA'       => $PG_DATA,
      'PAY_TYPE'      => $PAY_TYPE,
      'UPDATED_AT'    => $UPDATED_AT	
    );

      //중복 체크할지는 나중에


      $std = new Member_cardModel();
      $std->transBegin();

      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
          $std->transRollback();
        
          $msg= '로그인 후 이용 하세요.';
          echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
      } else {
          $std->transCommit();

      //MEMBER_CARD 카드 부분을 입력 또는 업데이트 한다.



    //   `IDX` int(11) unsigned NOT NULL AUTO_INCREMENT,
    //   `USER_IDX` int(11) unsigned NOT NULL,
    //   `CARD_NAME` varchar(45) DEFAULT NULL COMMENT '발행사',
    //   `TYPE` varchar(45) DEFAULT NULL COMMENT '법인 C / 개인 P',
    //   `CARD_NUM` varchar(20) NOT NULL COMMENT '카드번호',
    //   `MONTH` char(2) NOT NULL COMMENT '만료월',
    //   `YEAR` char(2) NOT NULL COMMENT '만료년도',
    //   `CVS` varchar(4) DEFAULT NULL COMMENT 'CVS 번호',
    //   `OWNER_NUM` varchar(45) DEFAULT NULL COMMENT '주민번호 또는 법인번호',
    //   `BILLKEY` varchar(45) DEFAULT NULL COMMENT '자동 결제시 고유키 값',
    //   `DISP` char(1) NOT NULL DEFAULT 'Y' COMMENT '표시 여부',
    //   `CREATED_AT` datetime NOT NULL DEFAULT current_timestamp(),
    //   `UPDATED_AT` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    //   PRIMARY KEY (`IDX`) USING BTREE,
    //   KEY `IDX_USER_IDX` (`USER_IDX`) USING BTREE
    // ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='고객 카드 정보로 암호화필요 '




          $msg= '';
          echo(json_encode(array("result" => 'succ', "msg" => $msg)));  
      }


    } else {
      $msg= '';
      echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
    }


  }


  public function result_ok() {  
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
   
    $orderid    = '';
    $itemname   = 'TestItem';
    $amount     = '';
    $userphone  = esc($request->getPost('userphone'));
    $useremail  = esc($request->getPost('useremail'));
    $userid     = esc($request->getPost('userid'));
    $useragent  = 'ONLINE';

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
   

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {

      // $newData = array(
			//   'USER_IDX'  => esc($request->getPost('USER_IDX')),
			//   'NICK_NAME' => esc($request->getPost('NICK_NAME')),
			//   'STATUS'    => esc($request->getPost('STATUS'))			
	  	// );

      $newData = array(
			  'USER_IDX'      => $USER_IDX,
			  'TR_ID'         => $TR_ID,
			  'TR_IDX'        => $TR_IDX,	
        'METHOD_IDX'    => $METHOD_IDX,
			  'PRICE'         => $PRICE,
			  'NET_PRICE'     => $NET_PRICE,
        'FEE_PG'        => $FEE_PG,
			  'STATUS'        => $STATUS,
			  'PG_IDX'        => $PG_IDX,	
        'REF_PGIDX'     => $REF_PGIDX,
			  'RBANK_NAME'    => $RBANK_NAME,
			  'RBANK_ACCOUNT' => $RBANK_ACCOUNT,	
        'PAYDATE'       => $PAYDATE,
			  'CANCELDATE'    => $CANCELDATE,	
        'PG_DATA'       => $PG_DATA,
			  'PAY_TYPE'      => $PAY_TYPE,
			  'UPDATED_AT'    => $UPDATED_AT	
	  	);

      //중복 체크할지는 나중에
 

      $std = new PaymentModel();
      $std->transBegin();

      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
          $std->transRollback();
        
          $msg= '로그인 후 이용 하세요.';
          echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
      } else {
          $std->transCommit();
          $msg= '';
          echo(json_encode(array("result" => 'succ', "msg" => $msg)));  
      }
    } else {
      $msg= '';
      echo(json_encode(array("result" => 'fail', "msg" => $msg)));  
    }
  }

  public function result_fail() 
  {  




  }




}