<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\Member_couponModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\PetModel;
use App\Models\App_push_messagesModel;
use App\Models\Log_locationModel;

use App\Libraries\ValidChecker;
use App\Libraries\Pushnoti;

class Transport extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }
  
  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }

  public function mylist() {  
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
      ajaxReturn(RESULT_FAIL,"사용자 정보가 넘어오지 않았습니다.","");
      return;
    }
   
    $CALL_TYPE  = esc($request->getPost('call_type'));
    $order      = strtoupper(esc($request->getPost('order')));
    $by         = strtoupper(esc($request->getPost('by')));
    $page       = esc($request->getPost('page'));
    $perpage    = esc($request->getPost('perpage'));  

    if($order=='') {
      $order    = "transport.TR_IDX";
    } else{
      $order    = "transport.".$order;
    }

    if($by=='') {
      $by    = "DESC";
    } else {
      $by    = $by;
    }

    //$by       = "transport.".$by;
    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.CALL_TYPE', $CALL_TYPE); 
    $builder->where('transport.USER_IDX', $USER_IDX);  
    if($CALL_TYPE=='R') {
      $builder->where('transport.RESERVE_TIME >=',TIME_YMDHIS);     
    }
    // $builder->where('transport.STATUS !=', 'W');  
    // $builder->where('transport.STATUS !=', 'C'); 
    // $builder->where('transport.STATUS !=', 'E'); 
    $count_total = $builder->countAllResults();
   
    if($count_total==0) {        
      ajaxReturn(RESULT_FAIL,"선택하신 정보가 없습니다.","");
      return;        
    }

    if($CALL_TYPE=='R') {
      $where=" AND transport.RESERVE_TIME >= NOW()";     
    }

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
      transport.START_TIME,
      transport.ARRIVE_TIME,
      transport.CCTV_URL,
      transport.IS_USER_SHOW,
      driver_join_info.NAME,
      driver_join_info.CAR_NUM,
      driver_join_info.CAR_TYPE,
      member.IMAGE AS DRIVER_IMAGE
  FROM 
    TRANSPORT AS transport  
  LEFT JOIN 
    DRIVER_JOIN_INFO AS driver_join_info ON transport.DRIVER_IDX=driver_join_info.USER_IDX      
  LEFT JOIN 
    MEMBER AS member ON transport.DRIVER_IDX=member.USER_IDX     
  WHERE 
    transport.USER_IDX='{$USER_IDX}' AND transport.CALL_TYPE='{$CALL_TYPE}' $where
  ORDER BY  $order $by  ";
  // transport.USER_IDX='{$USER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";


  // if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
  //   echo "--> 사무실 IP에서만 보임 (디버깅)<br>";
  //   echo "sqldata <br>";
  //   print_r($sqldata);
  //   echo "<br>";
  //   echo "사무실 IP에서만 보임 (디버깅) <--<br>";
  // } 

     //print_r($sqldata);
    $result=$this->db->query($sqldata);
      if($result) {
        $data['transport'] = $result->getResultArray();

        //pet정보를 가져온다.
      $data['transport'][0]['PETLIST'] = array();
      
      $pet = $data['transport'][0]['PET_LIST'];

      $tmp = explode(',',$pet); 
      $total=count($tmp); 

      /// $data['transport']=['0','1','pet','3','4','5','6','7'];
      $petlist=array();
  
      //  print_r($petlist);
      //  print_r("<br>"); 
      // print_r($data);
      // print_r("<br>");
  
      $builder = $this->db->table("PET as pet");

      for( $i=0;$i<count($tmp);$i++ ) {
        $PET_IDX = trim($tmp[$i]);
        //    print_r($PET_IDX);
        //  print_r("<br>");        
  
        //select
        $builder->select('pet.IDX AS PET_IDX, pet.USER_IDX, pet.PET_NAME, pet.STATUS, pet.IMAGE, pet.PET_TYPE, pet.CHARACTER, pet.COMMENT');
        // $builder->where('pet.USER_IDX', $USER_IDX);  
        $builder->where('pet.IDX', $PET_IDX);  
        $builder->where('pet.USER_IDX', $USER_IDX);  
    
        $petdata = $builder->get()->getResult('array');   
  
        array_push($petlist, $petdata[0]);
        // print_r($petdata[0]);
        // print_r("<br>"); 

        // print_r($petlist);
        // print_r("<br>"); 
        // if(count($tmp)==2){
        //   $name = trim($tmp[0]);
        //   $value = trim($tmp[1]);
        // } else {
        //   $name = trim($tmp[0]);
        //   $value = "";
        // }      
      }
     // array_push($petlist['pet_list'], $petlist);
     $petlist[0]['total'] = $total;   
    //  print_r($petlist[0]);
    //     print_r("<br>"); 
      array_push($data['transport'][0]['PETLIST'], $petlist[0]);
  
      
  
      
        //print_r($data); 
        $data['tcount']= $count_total;     
        $message='';
        ajaxReturn(RESULT_SUCCESS,$message,$data); 
        return;
      } else {
        $data['tcount']= $count_total;     
        $message='';
        ajaxReturn(RESULT_FAIL,$message,$data); 
        return;
      }            
  }  



  public function tr_check() {  
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
        $REFRESH_TOKEN  = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 

    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"사용자 정보가 넘어오지 않았습니다.","");
      return;
    }
   
    $TR_IDX   = esc($request->getPost('tr_idx'));
    
    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX); 
    $builder->where('transport.USER_IDX', $USER_IDX);  
    // $builder->where('transport.STATUS !=', 'W');  
    // $builder->where('transport.STATUS !=', 'C'); 
    // $builder->where('transport.STATUS !=', 'E'); 
    $total = $builder->countAllResults();
    
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"선택하신 정보가 없습니다.","");
      return;        
    }
    $sqldata="SELECT 
      transport.TR_IDX,
      transport.CALL_TYPE, 
      transport.STATUS, 
      transport.USER_IDX,
      transport.DRIVER_IDX   
    FROM 
      TRANSPORT AS transport  
    WHERE 
    transport.USER_IDX='{$USER_IDX}' AND transport.TR_IDX='{$TR_IDX}'";
    // transport.USER_IDX='{$USER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";



     //print_r($sqldata);
    $result=$this->db->query($sqldata);
      if($result) {
        $data['transport'] = $result->getResultArray();
        //print_r($data); 
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


  public function order() {  
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
      ajaxReturn(RESULT_FAIL,"","");
      return;
    }
   
    $CALL_TYPE    = esc($request->getPost('call_type'));
    $PET_LIST     = esc($request->getPost('pet_idx'));
    $LOC_START_LON  = esc($request->getPost('loc_start_lon'));
    $LOC_START_LAT  = esc($request->getPost('loc_start_lat'));
    $LOC_DEST_LON = esc($request->getPost('loc_dest_lon'));
    $LOC_DEST_LAT = esc($request->getPost('loc_dest_lat'));
    $ADDR_START   = esc($request->getPost('addr_start'));
    $ADDR_DEST    = esc($request->getPost('addr_dest'));
    $E_DISTANCE   = esc($request->getPost('e_distance'));
    $E_FEE        = esc($request->getPost('e_fee'));
    $E_TIME       = esc($request->getPost('e_time'));
    $E_ARRIVE_TIME= esc($request->getPost('e_arrive_time'));
    $FEE          = esc($request->getPost('fee'));
    $USER_RIDE    = esc($request->getPost('user_ride'));
    $ROUND_TRIP   = esc($request->getPost('round_trip'));
    $USER_MEMO    = esc($request->getPost('memo'));
    $RESERVE_TIME = esc($request->getPost('reserve_time'));
    $C_IDX = esc($request->getPost('c_idx'));  //사용된 쿠폰번호

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {
      $this->db->transBegin();

      $data="INSERT INTO TRANSPORT SET ";  
      $data.="CALL_TYPE     = '".$CALL_TYPE."', ";
      $data.="STATUS        = 'W', "; 
      $data.="USER_IDX      = '".$USER_IDX."', ";  
      $data.="DRIVER_IDX    = '', ";  
      $data.="DRIVER_START  = '', ";  
      $data.="LOC_START     = POINT($LOC_START_LON, $LOC_START_LAT), ";  
      $data.="LOC_DEST      = POINT($LOC_DEST_LON, $LOC_DEST_LAT), ";  
      $data.="ADDR_START    = '".$ADDR_START."', ";  
      $data.="ADDR_DEST     = '".$ADDR_DEST."', ";  
      $data.="E_DISTANCE    = '".$E_DISTANCE."', ";  
      $data.="E_FEE         = '".$E_FEE."', ";  
      $data.="E_TIME        = '".$E_TIME."', ";  
      $data.="E_ARRIVE_TIME = '".$E_ARRIVE_TIME."', ";  
      $data.="PET_LIST      = '".$PET_LIST."', ";  
      $data.="FEE           = '".$FEE."', ";  
      $data.="USER_RIDE     = '".$USER_RIDE."', ";  
      $data.="ROUND_TRIP    = '".$ROUND_TRIP."', ";  
      $data.="RESERVE_TIME  = '".$RESERVE_TIME."', ";  
      $data.="C_IDX         = '".$C_IDX."', ";  
      $data.="USER_MEMO     = '".$USER_MEMO."' ";  

      //중복 체크할지는 나중에
      //print_r($data);
 
      $this->db->query($data);

      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $message='정상적으로 요청이 처리되지 않았습니다.';
        ajaxReturn(RESULT_FAIL,$message,$data);
        return;
      } else {
        $this->db->transCommit();
    
        //select
        $builder = $this->db->table("TRANSPORT as transport");
        $builder->select('MAX(transport.TR_IDX) as TR_IDX');
        //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
        //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
        //  $builder->where('user.*, $what)->countAllResults(),  

        //$builder->orderBy('user.USER_IDX','DESC');
        $builder->where('transport.USER_IDX', $USER_IDX);  
        $builder->where('transport.STATUS', 'W');   
        //$builder->where('transport.CREATED_AT >=', $TOKEN_EXPIRED_DATE);  

        $Tempdata['transport'] = $builder->get()->getResult('array');   
        $TR_IDX = $Tempdata['transport'][0]['TR_IDX'];

        $ReturnData=array(
            'TR_IDX'    => $TR_IDX,
            'USER_IDX'    => $USER_IDX			
          );


          //푸쉬하기
        // $USER_IDX = 13;

          $fields   = ['APP_TYPE','PUSH_TOKEN'];
          $builder = $this->db->table("MEMBER");
          $builder->select($fields);
          $builder->where('USER_IDX', $USER_IDX); 
          //$member = $builder->get()->getResult('array');   
          $member = $builder->get()->getResult();   

          // print_r($member[0]->APP_TYPE);
          // print_r("<br>");
          // print_r($member[0]->PUSH_TOKEN);

          //exit;

          //예약 배차 대화 일반 결제 공지 이벤트 기타 
          //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
          $pushNoti     = new Pushnoti();
          $dataMessage  = array(); //notification 데이터
          $dataMessage['title']     = "팻글택시의 예약되었습니다.";
          $dataMessage['priority']  = "high";
          $dataMessage['message']   = "예약이 완료되었습니다. 본문은 업데이트 예정입니다.";
          $dataMessage['mtype']     = "BOOKING"; 
          //$dataMessage['body']      = "아이폰 바디입니다.";

          // 아이폰 포멧
        // $token="de5phtxdRkYDt6E6LZsY7V:APA91bEAfHnsx8d5k0oLGhzQQdIMvXsql3NXkuMpaePDIis8k91gm6RKmSJcGOJt_nfOJMWc4LroDcZcEnrGVsnIDrJgpCPl5hasx8Nkdd_z4EoA_EEFdli8nbrEQCsHv4_g9PhMK0OS"; //아이폰 사용자 푸쉬

          //$type="I";  

          $returnData = $pushNoti->send($member[0]->PUSH_TOKEN, $dataMessage,$member[0]->APP_TYPE);



        $message= '정상적으로 요청이 처리되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
        return;
      }
      //$builder = $this->db->table("TRANSPORT");
      //$builder->insert($newData);
      //  $this->db->query('INSERT INTO LOG_LOCATION  (`USER_IDX`, `TR_IDX`,  `STATUS`, `LOC`) VALUES (4,4, "Y", POINT(126.98749271, 37.55642775))');

      //UPDATE petglet.TRANSPORT SET `LOC_START` = POINT(126.98749271, 37.55642775) where TR_IDX=1
      //SELECT AsText(LOC_START) FROM petglet.TRANSPORT;
      //exit;

    //   $std = new TransportModel();
    //   $std->transBegin();

    //   $std->insert($newData);

    //   if ($std->transStatus() === FALSE) {
    //     $std->transRollback();
    //     $message='1';
    //     ajaxReturn(RESULT_FAIL,'',$message);
    //     return;
    //   } else {
    //     $std->transCommit();
    //     $message= '2';
    //     ajaxReturn(RESULT_SUCCESS,'',$message);  
    //     return;
    //   }
    } else {
      $message='정상적으로 요청이 처리되지 않았습니다.';
      ajaxReturn(RESULT_FAIL,$message,$data); 
      return;
     }        
  }

  public function cancel() {
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
   
    $TR_IDX = esc($request->getPost('tr_idx'));
    $MEMO   = esc($request->getPost('reason'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.USER_IDX', $USER_IDX); 
    //$builder->where('transport.DRIVER_IDX',0); 
    $builder->where('transport.STATUS !=','C'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"취소하려는 호출이 없습니다.","");
      return;        
    }
   
    $UPDATED_AT = date('Y-m-d H:i:s');

    //select    
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.USER_IDX, transport.RESERVE_TIME, transport.STATUS,  transport.CALL_TYPE, transport.C_IDX, member.USER_ID');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.USER_IDX', $USER_IDX);     
   // $builder->where('transport.DRIVER_IDX',0); 
    $builder->where('transport.STATUS !=','C'); 
    $data['transport'] = $builder->get()->getResult('array');  

    //예약건의 경우 3시간이내에는 환불로 취소처리한다.

    $LIMITED_TIME    = date("Y-m-d H:i:s", strtotime("+3 hours"));

    if(($data['transport'][0]['RESERVE_TIME'] !='' && $data['transport'][0]['RESERVE_TIME']<=$LIMITED_TIME && $data['transport'][0]['CALL_TYPE']=='R') || (($data['transport'][0]['RESERVE_TIME'] !='W' && $data['transport'][0]['RESERVE_TIME'] !='G')  && $data['transport'][0]['CALL_TYPE']=='N')) {

      //취소하려는 호출이 3시간 이내로 예약금 환급이 되지 않습니다.
      //결제건을 취소 시킨다.

      $newData = ['STATUS'=>'C',];

      $std = new TransportModel();
      $std->transBegin();
  
      $result = $std->update($TR_IDX,$newData);
  
      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='호출 취소 중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,"");
        return;
      } else {
        $std->transCommit();          
  
        // 예약금은 기사의 수입으로 입력한다.
        $up_sql = "UPDATE MEMBER SET ";
        $up_sql.= "POINT  = POINT + {$data['transport'][0]['R_FEE']}";
        $up_sql.= "WHERE USER_IDX='{$data['transport'][0]['DRIVER_IDX']}' "; 
        
        $up_sql_result=$this->db->query($up_sql);
        //UPDATE MEMBER SET POINT  = POINT +10 WHERE USER_IDX=1


        //사용된 쿠폰은 원위치 시킨다.
        $CP_newData = ['IS_USED'=>'N',];
        $CP_std = new Member_couponModel();    
        $CP_result = $CP_std->update($data['transport'][0]['C_IDX'],$CP_newData);

        // 확인이 필요 없는 데이타는 TRANSPORT_END 로 이동한다.     
        $MoveData="INSERT INTO TRANSPORT_END SELECT * FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";  
        $this->db->query($MoveData);

        $DeleteData="DELETE FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";
        $this->db->query($DeleteData);
  
        $message='정상적으로 취소가 되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$data);
        return;
      }   

      
    } else {  //실시간 인 경우
   
      $newData = ['STATUS'=>'C',];

      $std = new TransportModel();
      $std->transBegin();

      $result = $std->update($TR_IDX,$newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='호출 취소 중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,"");
        return;
      } else {
        $std->transCommit();          
        
        //사용된 쿠폰은 원위치 시킨다.
        $CP_newData = ['IS_USED'=>'N',];
        $CP_std = new Member_couponModel();    
        $CP_result = $CP_std->update($data['transport'][0]['C_IDX'],$CP_newData);

        // 확인히 필요 없는 데이타는 TRANSPORT_END 로 이동한다.
        // 실시간으로 기사배정이 안된 상태
        //print_r($data['transport']);
        // if($data['transport'][0]['STATUS']=='W' && $data['transport'][0]['CALL_TYPE']=='N'  && $data['transport'][0]['DRIVER_IDX']=='0') {
        $MoveData="INSERT INTO TRANSPORT_END SELECT * FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";

        $this->db->query($MoveData);

        $DeleteData="DELETE FROM TRANSPORT WHERE TR_IDX='{$TR_IDX}'";

        $this->db->query($DeleteData);
      // }
        $message='정상적으로 취소가 되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$data);
        return;
      }   
    }
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
      ajaxReturn(RESULT_FAIL,"","");
      return;
    }
   
    $TR_IDX = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX, transport.USER_IDX, member.*');
   // $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();

    if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
    //   echo "--> 사무실 IP에서만 보임 (디버깅)<br>";
    //   print_r($TR_IDX);
    //  // exit;
    //   echo "사무실 IP에서만 보임 (디버깅) <--<br>";
    } 

    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"요청하신 정보가 없습니다.","");
      return;        
    }



    $sqldata="SELECT 
    transport.TR_IDX,
    ST_X(transport.LOC_START) AS LOC_START_LON, 
    ST_Y(transport.LOC_START) AS LOC_START_LAT,
    transport.CALL_TYPE, 
    transport.STATUS AS STATUS, 
    transport.USER_IDX AS USER_IDX,
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
    transport.C_IDX,
    driver_join_info.INFO_IDX AS DRIVER_INFO_IDX,
    driver_join_info.NAME AS DRIVER_INFO_NAME,
    driver_join_info.GENDER AS DRIVER_INFO_GENDER,
    driver_join_info.BIRTH AS DRIVER_INFO_BIRTH,
    driver_join_info.EMAIL AS DRIVER_INFO_EMAIL,
    driver_join_info.ADDR1 AS DRIVER_INFO_ADDR1,
    driver_join_info.ADDR2 AS DRIVER_INFO_ADDR2,
    driver_join_info.CAR_NUM AS DRIVER_INFO_CAR_NUM,
    driver_join_info.MYCAR AS DRIVER_INFO_MYCAR,
    driver_join_info.CAR_TYPE AS DRIVER_INFO_CAR_TYPE,
    driver_join_info.CAREER AS DRIVER_INFO_CAREER,
    driver_join_info.ALLERGY AS DRIVER_INFO_ALLERGY,
    driver_join_info.ATP_NUM AS DRIVER_INFO_ATP_NUM,
    driver_join_info.IBRC_NUM AS DRIVER_INFO_IBRC_NUM,
    driver_join_info.BANKBOOK_IMAGE AS DRIVER_INFO_BANKBOOK_IMAGE,
    driver_join_info.IDCARD_IMAGE AS DRIVER_INFO_IDCARD_IMAGE,
    driver_join_info.COMMENT AS DRIVER_INFO_COMMENT,
    driver_join_info.FEE_TOTAL AS DRIVER_INFO_FEE_TOTAL,
    driver_join_info.FEE_CURRENT AS DRIVER_INFO_FEE_CURRENT,
    driver_join_info.EXCHANGE_TOTAL AS DRIVER_INFO_EXCHANGE_TOTAL,
    driver_join_info.CCTV_IDX AS DRIVER_INFO_CCTV_IDX,
    driver_join_info.BANK_ACCOUNT AS DRIVER_INFO_BANK_ACCOUNT,
    driver_join_info.DRIVER_SECURITY AS DRIVER_INFO_DRIVER_SECURITY,
    driver_join_info.DRIVER_NUM AS DRIVER_INFO_DRIVER_NUM,
    driver_join_info.DRIVER_LICENCE_IMAGE AS DRIVER_INFO_DRIVER_LICENCE_IMAGE,
    driver_join_info.WITHDREWAL AS DRIVER_INFO_WITHDREWAL,
    driver_join_info.CREATED_AT AS DRIVER_INFO_CREATED_AT,
    driver_join_info.UPDATED_AT AS DRIVER_INFO_UPDATED_AT,
    driver_info.IMAGE AS DRIVER_INFO_IMAGE
    
FROM 
  TRANSPORT AS transport  
LEFT JOIN 
  DRIVER_JOIN_INFO AS driver_join_info ON transport.DRIVER_IDX=driver_join_info.USER_IDX      
LEFT JOIN 
  MEMBER AS driver_info ON transport.DRIVER_IDX=driver_info.USER_IDX   
WHERE 
transport.USER_IDX='{$USER_IDX}' AND transport.TR_IDX='{$TR_IDX}'";
// transport.USER_IDX='{$USER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";



  //print_r($sqldata);
  $result=$this->db->query($sqldata);
    if($result) {
      $data['transport'] = $result->getResultArray();
  
      //pet정보를 가져온다.
      $data['transport'][0]['PETLIST'] = array();
      
      $pet = $data['transport'][0]['PET_LIST'];

      $tmp = explode(',',$pet); 
      $total=count($tmp); 

      /// $data['transport']=['0','1','pet','3','4','5','6','7'];
      $petlist=array();
  
      //  print_r($petlist);
      //  print_r("<br>"); 
      // print_r($data);
      // print_r("<br>");
  
      $builder = $this->db->table("PET as pet");

      for( $i=0;$i<count($tmp);$i++ ) {
        $PET_IDX = trim($tmp[$i]);
        //    print_r($PET_IDX);
        //  print_r("<br>");        
  
        //select
        $builder->select('pet.IDX AS PET_IDX, pet.USER_IDX, pet.PET_NAME, pet.STATUS, pet.IMAGE, pet.PET_TYPE, pet.CHARACTER, pet.COMMENT');
        // $builder->where('pet.USER_IDX', $USER_IDX);  
        $builder->where('pet.IDX', $PET_IDX);  
        $builder->where('pet.USER_IDX', $USER_IDX);  
    
        $petdata = $builder->get()->getResult('array');   
  
        array_push($petlist, $petdata[0]);
        // print_r($petdata[0]);
        // print_r("<br>"); 

        // print_r($petlist);
        // print_r("<br>"); 
        // if(count($tmp)==2){
        //   $name = trim($tmp[0]);
        //   $value = trim($tmp[1]);
        // } else {
        //   $name = trim($tmp[0]);
        //   $value = "";
        // }      
      }
     // array_push($petlist['pet_list'], $petlist);
     $petlist[0]['total'] = $total;   
    //  print_r($petlist[0]);
    //     print_r("<br>"); 
      array_push($data['transport'][0]['PETLIST'], $petlist[0]);




      //사용되는 쿠폰 정보를 가져온다.
      $data['transport'][0]['COUPONLIST'] = array();
            
      $coupon = $data['transport'][0]['C_IDX'];

      $coupon_tmp = explode(',',$coupon); 
      $coupon_total=count($coupon_tmp); 

      /// $data['transport']=['0','1','pet','3','4','5','6','7'];
      $coupon_list=array();

      //  print_r($petlist);
      //  print_r("<br>"); 
      // print_r($data);
      // print_r("<br>");

      $builder_coupon = $this->db->table("MEMBER_COUPON as member_coupon");

      for( $k=0;$k<count($coupon_tmp);$k++ ) {
        $CP_IDX = trim($coupon_tmp[$k]);
        //    print_r($PET_IDX);
        //  print_r("<br>");        

        //select
        $builder_coupon->select('member_coupon.CP_IDX AS CP_IDX, member_coupon.CP_ID, member_coupon.CP_SUBJECT, member_coupon.CP_PRICE, member_coupon.IS_USED');
        // $builder->where('pet.USER_IDX', $USER_IDX);  
        $builder_coupon->where('member_coupon.CP_IDX', $CP_IDX);  
        $builder_coupon->where('member_coupon.USER_IDX', $USER_IDX);  

        $coupon_data = $builder_coupon->get()->getResult('array');   

        array_push($coupon_list, $coupon_data[0]);
        // print_r($petdata[0]);
        // print_r("<br>"); 

        // print_r($petlist);
        // print_r("<br>"); 
        // if(count($tmp)==2){
        //   $name = trim($tmp[0]);
        //   $value = trim($tmp[1]);
        // } else {
        //   $name = trim($tmp[0]);
        //   $value = "";
        // }      
      }
      // array_push($petlist['pet_list'], $petlist);
      $coupon_list[0]['total'] = $coupon_total;   
      //  print_r($petlist[0]);
      //     print_r("<br>"); 
      array_push($data['transport'][0]['COUPONLIST'], $coupon_list[0]);

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

  public function driver_loc() {  
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
      ajaxReturn(RESULT_FAIL,"","");
      return;
    }
   
    $TR_IDX = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("LOG_LOCATION as log_location");
    $builder->select('log_location.*, member.*');
    $builder->join('MEMBER as member', 'member.USER_IDX = log_location.USER_IDX', "left inner"); // added left here
    $builder->where('log_location.TR_IDX', $TR_IDX);  
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"요청하신 정보가 없습니다.","");
      return;        
    }

    //print_r($total);
    //exit;

    //select
  //  $builder = $this->db->table("LOG_LOCATION as log_location");
  //   $builder->select('log_location.IDX, ST_AsText(log_location.LOC) as loc,log_location.CREATED_AT, ');
  //   $builder->join('MEMBER as member', 'member.USER_IDX = log_location.USER_IDX', "left inner"); // added left here
  //   $builder->where('log_location.TR_IDX', $TR_IDX);  

  $sqldata="SELECT 
  log_location.IDX,
  ST_X(log_location.LOC) AS LOC_START_LON, 
  ST_Y(log_location.LOC) AS LOC_START_LAT,
  member.*
  FROM 
    LOG_LOCATION as log_location  
  LEFT JOIN 
    MEMBER AS member ON log_location.USER_IDX=member.USER_IDX      
  WHERE 
    log_location.USER_IDX='{$USER_IDX}' AND log_location.TR_IDX='{$TR_IDX}'";


  //print_r($sqldata);
  $result=$this->db->query($sqldata);
  if($result) {
      $data['driver_loc'] = $result->getResultArray();
      $data['tcount']= $total;
    
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return;   
    }
        
  }







  public function realtime_fee() {  //실시간 요금 조회
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
      ajaxReturn(RESULT_FAIL,"","");
      return;
    }
   
    $TR_IDX = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("LOG_LOCATION as log_location");
    $builder->select('log_location.*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = log_location.USER_IDX', "left inner"); // added left here
    $builder->where('log_location.TR_IDX', $TR_IDX);  
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"요청하신 정보가 없습니다.","");
      return;        
    }

    //print_r($total);
    //exit;

    //select
  //  $builder = $this->db->table("LOG_LOCATION as log_location");
  //   $builder->select('log_location.IDX, ST_AsText(log_location.LOC) as loc,log_location.CREATED_AT, ');
  //   $builder->join('MEMBER as member', 'member.USER_IDX = log_location.USER_IDX', "left inner"); // added left here
  //   $builder->where('log_location.TR_IDX', $TR_IDX);  

  $sqldata="SELECT 
    log_location.IDX,
    ST_X(log_location.LOC) AS LOC_START_LON, 
    ST_Y(log_location.LOC) AS LOC_START_LAT,
    log_location.FEE,
    log_location.METERS,
    log_location.MINUTES
  FROM 
    LOG_LOCATION as log_location  
  -- //LEFT JOIN 
  --   MEMBER AS member ON log_location.USER_IDX=member.USER_IDX      
  WHERE 
    log_location.USER_IDX='{$USER_IDX}' AND log_location.TR_IDX='{$TR_IDX}' 
  ORDER BY CREATED_AT DESC limit 1";


  //print_r($sqldata);
  $result=$this->db->query($sqldata);
  if($result) {
      $data['realtime_fee'] = $result->getResultArray();
      $data['tcount']= $total;
    
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return;   
    }
        
  }







 





}//class