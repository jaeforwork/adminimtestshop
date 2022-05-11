<?php
namespace App\Controllers\Driver;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\WithdrawalModel;
use App\Models\Log_locationModel;
use App\Models\App_push_messagesModel;

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

  public function list() {  
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
        $DRIVER_IDX     = $ACCESS_DATA['user_idx'];
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

    if(!$DRIVER_IDX){
      ajaxReturn(RESULT_FAIL,"조회할 사용자번호가 넘어오지 않았습니다.","");
      return;
    }
   
    $order  = esc($request->getPost('order'));
    $by     = esc($request->getPost('by'));
    $page   = esc($request->getPost('page'));

    if (!$order || $order=="" || $order=="created_at") {
      $order  = "CREATED_AT";
    } else if ($order=="idx"){
      $order  = "IDX";
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
    
    if (!empty($ACCESS_TOKEN) && !empty($DRIVER_IDX)) {
      $sqldata="SELECT 
          count(withdrawal.IDX) as total,
          USER_IDX
        FROM 
          WITHDRAWAL AS withdrawal                           
        WHERE 
          USER_IDX='{$DRIVER_IDX}'";
      $query = $this->db->query($sqldata);

      if($query){
        foreach ($query->getResult() as $row) {
          $total=$row->total;
        }
      } else {
        $total=0;
      }

      if(!$total || $total==0) {        
        ajaxReturn(RESULT_FAIL,"현재 정산내역이 없습니다.","");
        return;        
      } 
      
      if($total-$from>9) {
        $to=9;      
      } else {
        $to=$total-$from;
      }

      $sqldata="SELECT 
          withdrawal.IDX,
          withdrawal.BANK, 
          withdrawal.ACCOUNT,
          withdrawal.ACCOUNT_NAME,
          withdrawal.PHONE,
          withdrawal.AMOUNT,
          withdrawal.AMOUNT_EX,  
          withdrawal.FEE_PLATFORM,
          withdrawal.TAX,
          withdrawal.STATUS,
          withdrawal.ADMIN_IDX,
          withdrawal.COMPLETED_AT,
          withdrawal.REJECT_MSG,
          withdrawal.CREATED_AT,
          withdrawal.UPDATED_AT,
          withdrawal.DELETED_AT
        FROM 
          WITHDRAWAL AS withdrawal            
        WHERE 
          USER_IDX='{$DRIVER_IDX}' STATUS='W' ORDER BY $order $by LIMIT $from, $to";  //2km 이내

      // print_r($sqldata);
      $result=$this->db->query($sqldata);
      $data['withdrawal'] = $result->getResultArray();

      if ($data['withdrawal'][0]['IDX']!="") {
        //print_r($data); 
        $data['page']   = $page;
        $data['tcount'] = $total;     
        $message='';
        ajaxReturn(RESULT_SUCCESS,$message,$data); 
        return;
      } else {
        ajaxReturn(RESULT_FAIL,"현재 대기중인 호출이 없습니다.","");
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
      ajaxReturn(RESULT_FAIL,"사용자 IDX가 넘어오지 않았습니다.","");
      return;
    }
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,'선택하신 정보가 없습니다.',"");
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
                transport.TR_IDX='{$TR_IDX}'";

              // print_r($sqldata);
              $result=$this->db->query($sqldata);


   
    // print_r($sqldata);
    $result=$this->db->query($sqldata);
    if($result) {
      $data['transport'] = $result->getResultArray();
      //print_r($data); 

      
     

      //USER정보를 가져온다.
      $USER_IDX=$data['transport'][0]['USER_IDX'];

      $user_info=array();

      //select user for driver
      $user_builder = $this->db->table("MEMBER as user");

      $user_builder->select('user.USER_IDX, user.USER_ID, user.USER_NAME, user.PHONE, user.NICK_NAME, user.IMAGE ');  

      //$user_builder->select('user.USER_IDX, IFNULL(user.USER_NAME, "게스트") as user.USER_NAME, user.PHONE, IFNULL(user.NICK_NAME, "게스트") as user.NICK_NAME, user.IMAGE');

      $user_builder->where('user.USER_IDX', $USER_IDX);     
      $user_data = $user_builder->get()->getResult('array');   

      if($preg_match_result = preg_match("/p_/u", $user_data[0]['USER_ID'])) {
        $user_data[0]['USER_ID']="게스트";
        $user_data[0]['USER_NAME']="게스트";
        $user_data[0]['NICK_NAME']="게스트";
      }  


        array_push($user_info, $user_data[0]);
   
    // $user_info = $user_total;   
    //  print_r($user_info);
    //  exit;
    //     print_r("<br>"); 
        $data['transport'][0]['USER_INFO']=array();
      array_push($data['transport'][0]['USER_INFO'], $user_info[0]);
  

      
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





      //사용자에대한 후기를 5개만 가져온다.
      $review_list=array();
      $data['transport'][0]['REVIEWLIST']=array();
      //  print_r($petlist);
      //  print_r("<br>"); 
      // print_r($data);
      // print_r("<br>");
  
     

  //count
  
  $review_builder = $this->db->table("REVIEW_USER as review_user");
  $review_builder->select('review_user.* ');
  $review_builder->where(['review_user.USER_IDX'=> $USER_IDX,]);  

  $review_total = $review_builder->countAllResults();
  if($review_total>0) {        
  






      for( $i=0;$i<$review_total;$i++ ) {
        //$PET_IDX = trim($tmp[$i]);
        //    print_r($PET_IDX);
        //  print_r("<br>");        
  
        //select

        $review_builder->select('review_user.IDX AS REVIEW_USER_IDX, review_user.USER_IDX, review_user.DRIVER_IDX, review_user.TR_IDX, review_user.STAR, review_user.COMMENT');
        // $builder->where('pet.USER_IDX', $USER_IDX);   
        $review_builder->where('review_user.USER_IDX', $USER_IDX);  
        $review_builder->orderBy('review_user.CREATED_AT','DESC');
        //$review_data = $review_builder->get(5, 0)->getResult('array');   
        $review_data = $review_builder->get()->getResult('array');  
        array_push($review_list, $review_data[0]);
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
  
    //  print_r($petlist[0]);
    //     print_r("<br>"); 

  
  
    $review_list[0]['review_total'] = $review_total; 
      array_push($data['transport'][0]['REVIEWLIST'], $review_list[0]);
    } else {    
    $review_list[0]['review_total'] = 0; 
    array_push($data['transport'][0]['REVIEWLIST'], $review_list[0]);
    }


















   
      $data['tcount']= $total;     
      $message='';
      ajaxReturn(RESULT_SUCCESS,$message,$data); 
      return;
    } else {
      $message='';
      ajaxReturn(RESULT_FAIL,$message,''); 
      return;
    }            
  }




}//class