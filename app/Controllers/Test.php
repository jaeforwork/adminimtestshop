<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;
use App\Models\Log_db_error_Model;

//use App\Models\DriverModel;
use App\Models\MemberModel;
use App\Models\TransportModel;
use App\Models\App_push_messagesModel;
use App\Libraries\ValidChecker;
//use App\Libraries\Sms;
use App\Models\PetModel;

class Test extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();       
  }

  public function valid() {

    $time=TIME_YMDHIS;
    
    print_r(TIME_YMDHIS);

    $lib = new ValidChecker();
    //$check = $lib->check_csfs('&*%');
    $check = $lib->array_test();
    
    if(!$check) {
      $message= '특수문자는 포함될 수 없습니다.';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    } else {
      print_r($check);
    }

  }

  public function interval() {
    $to_time = strtotime("2022-02-02 04:09:37");
    $from_time = strtotime("2022-02-01 04:06:37");
    $time_diff = $to_time - $from_time;
    
    echo date('H:i:s', $time_diff);
    echo "현재로부터 1시간 뒤 : ".date("Y-m-d H:i:s", strtotime("+3 hours"))."<br/>";
  }
  
  public function array1() {
    $data = array();
    $data=['0','1','pet','3','4','5','6','7'];
    
    ajaxReturn(RESULT_SUCCESS,"",$data); 
    return;
  }

  public function array2() {
    $pet="70,71,72,73";
    $petArray = explode(',',$pet); 
    $data = array();

    for( $i=0;$i<count($petArray);$i++ ) {
      array_push($data, $petArray[$i]);
    }

// print_r($data);
// print_r("<br>");
// print_r($data[0]);
// print_r("<br>");
// print_r($data['pet']);
// print_r("<br>");

    // $total=count($data['pet']); 

      $data['PETLIST'] = array();
      $Newdata1=['0','1','pet','3','4','5','6','7'];
      $Newdata2=['9','10','pet0','30','40','50','60','70'];
   
     array_push($data['PETLIST'], $Newdata1);
     array_push($data['PETLIST'], $Newdata2);
      
    ajaxReturn(RESULT_SUCCESS,"",$data); 
    return;

  }

  public function array_test() {
    $pet="70,71,72,73";
    $tmp = explode(',',$pet); 
    $total=count($tmp); 

    $data['transport'][0]=['0','1','pet','3','4','5','6','7'];
    $data['transport'][0]['PETLIST'] = array();
    $USER_IDX = 13;

    $builder = $this->db->table("PET as pet");

    $petlist=array();
 
    $builder = $this->db->table("PET as pet");

    for( $i=0;$i<count($tmp);$i++ ) {
      $PET_IDX = trim($tmp[$i]);

      //select
      $builder->select('pet.IDX AS PET_IDX, pet.USER_IDX, pet.PET_NAME, pet.STATUS, pet.IMAGE, pet.PET_TYPE, pet.CHARACTER, pet.COMMENT');
       // $builder->where('pet.USER_IDX', $USER_IDX);  
      $builder->where('pet.IDX', $PET_IDX);  
      $builder->where('pet.USER_IDX', $USER_IDX);  
   
      $petdata = $builder->get()->getResult('array');   
 
       array_push($petlist, $petdata[0]);
      
      $petlist[0]['total'] = $total;   

      array_push($data['transport'][0]['PETLIST'], $petlist[0]);
 
      ajaxReturn(RESULT_SUCCESS,"",$data); 
      return;
    }
  }

  public function current_url() {

    $Log_db_error_NewData  = array(
      'TABLE_NAME'  => 'table',
      'URL'         => current_url(),
      'ERROR'       => 'ERROR',
      'IP'          => $_SERVER['REMOTE_ADDR'],
      'UPDATED_AT'  => ''
    );

    $Log_db_error_ReturnData  = array(
      'TABLE_NAME'  => 'table',
      'URL'         => current_url(),
      'ERROR'       => 'ERROR',
      'IP'          => $_SERVER['REMOTE_ADDR'],
      'UPDATED_AT'  => ''
    );

    $Log_db_error_std = new Log_db_error_Model();
    $Log_db_error_std->insert($Log_db_error_NewData);
    $Log_db_error_std->transCommit();
    $Log_db_error_IDX = $Log_db_error_std->getInsertID();
    print_r($Log_db_error_IDX);

    // $Log_db_error_std = new Log_db_error_Model();
    // //$Log_db_error_std->transBegin();
    // $Log_db_error_std->insert($Log_db_error_NewData);

    // if ($Log_db_error_std->transStatus() === FALSE) {
    //   $Log_db_error_std->transRollback();
    //   $message='링크생성 실패';
    //   ajaxReturn(RESULT_FAIL,$message,$Log_db_error_NewData);
    //   return;
    // } else { 
    //  $Log_db_error_std->transCommit(); 
    //   $message= '링크생성 완료';
    //   ajaxReturn(RESULT_SUCCESS,$message,$Log_db_error_ReturnData);  
    //   return;
    // }

  }

  public function transport_dbtest() {

    $TR_IDX=168;

    //사용자에게 푸쉬 시작

    $PSqlSel="SELECT 
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
              transport.START_TIME,
              transport.ARRIVE_TIME,
              transport.CCTV_URL,
              transport.IS_USER_SHOW,
              driver_join_info.NAME AS DRIVER_NAME,
              driver_join_info.CAR_NUM AS DRIVER_CAR_NUM,
              driver_member.IMAGE AS DRIVER_IMAGE
            FROM 
              TRANSPORT AS transport  
            LEFT JOIN 
              DRIVER_JOIN_INFO AS driver_join_info ON transport.DRIVER_IDX=driver_join_info.USER_IDX       
            LEFT JOIN 
              MEMBER AS driver_member ON transport.DRIVER_IDX=driver_member.USER_IDX      
            WHERE 
              transport.TR_IDX='{$TR_IDX}'";
              // transport.USER_IDX='{$USER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";

    //print_r($PSqlSel);
    $PResult=$this->db->query($PSqlSel);
    if($PResult) {
      $PushData = $PResult->getResultArray();

      // print_r($PushData);
      // exit;

      //  푸쉬하기
      //  수신인 확인
      $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];
      $builder = $this->db->table("MEMBER");
      $builder->select($fields);
      $builder->where('USER_IDX', $PushData[0]['USER_IDX']); 
      //$UserMember = $builder->get()->getResult('array');   
      $UserMember = $builder->get()->getResult();   
      // print_r($UserMember[0]->USER_IDX);
      // print_r("<br>");
      // print_r($UserMember[0]->APP_TYPE);
      // print_r("<br>");
      // print_r($UserMember[0]->PUSH_TOKEN);

      // exit;

      //예약 배차 대화 일반 결제 공지 이벤트 기타 
      //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
     // $pushNoti     = new Pushnoti();
      $dataMessage  = array(); //notification 데이터
      $dataMessage['title']     = "팻글택시의 드라이버가 배정되었습니다.";
      $dataMessage['priority']  = "high";
      $dataMessage['message']   = "드라이버의 예상 도착시간은  ".$TR_IDX." 분입니다.";
      $dataMessage['mtype']     = "BOOKING"; 
      $dataMessage['url']       = "도착주소"; 
      $dataMessage['user_img_url']    = $UserMember[0]->IMAGE; 
      $dataMessage['driver_img_url']  = $PushData[0]['DRIVER_IMAGE']; 
      //$dataMessage['body']      = "아이폰 바디입니다.";
      
      $ReturnPushData = $pushNoti->send($UserMember[0]->PUSH_TOKEN, $dataMessage,$UserMember[0]->APP_TYPE);

      if($ReturnPushData['success']==1){
        $DELIVERY = date('Y-m-d H:i:s');
        $pushResultData = ['TR_IDX'=>$TR_IDX,'USER_IDX'=>$PushData[0]['USER_IDX'],'PID'=>$ReturnPushData['results'][0]['message_id'],'APP_TYPE'=>$UserMember[0]->APP_TYPE,'DEVICE_ID'=>$UserMember[0]->DEVICE_ID,'PUSH_TOKEN'=>$UserMember[0]->PUSH_TOKEN,'MESSAGE'=>$dataMessage['message'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'Y',];
        
      // $pushResultData = ['USER_IDX'=>$PushData[0]['USER_IDX'],'PID'=>'12312312321','APP_TYPE'=>$UserMember[0]->APP_TYPE,'DEVICE_ID'=>$UserMember[0]->DEVICE_ID,'PUSH_TOKEN'=>$UserMember[0]->PUSH_TOKEN,'MESSAGE'=>$dataMessage['message'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'1',];
        
        
      // print_r($pushResultData);
      // exit;
        
        $App_push_messagesModel = new App_push_messagesModel();
        $PushRecord=$App_push_messagesModel->insert($pushResultData);

      // print_r($PushRecord);
      }
    }
  }
}