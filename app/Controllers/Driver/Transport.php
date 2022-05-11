<?php
namespace App\Controllers\Driver;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\MemberModel;
use App\Models\DriverModel;
use App\Models\TransportModel;
use App\Models\Log_locationModel;
use App\Models\Log_cctv_Model;
use App\Models\App_push_messagesModel;

use App\Libraries\ValidChecker;
use App\Libraries\Pushnoti;
use App\Libraries\Cctv;

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
    
      // if($header  == 'refresh_token') {
      //   $REFRESH_TOKEN = $value;
      // } else {
      //   $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      // } 
    }    
    //Header정리 

    if(!$DRIVER_IDX){
      ajaxReturn(RESULT_FAIL,"조회할 사용자번호가 없습니다.","");
      return;
    }
   
    $LOC_LON  = esc($request->getPost('loc_lon'));
    $LOC_LAT  = esc($request->getPost('loc_lat'));
    $dist     = esc($request->getPost('dist'));
    $order    = esc($request->getPost('order'));
    $by       = esc($request->getPost('by'));
    $page     = esc($request->getPost('page'));

    if (!$dist || $dist=="") {
      $dist  = 20000;
    }


    if (!$order || $order=="" || $order=="dist") {
      $order  = "DIST";
    } else if ($order=="fee"){
      $order  = "E_FEE";
    } else if ($order=="tr_idx"){
      $order  = "TR_IDX";
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
          count(transport.TR_IDX) as total,
          ST_X(transport.LOC_START) AS LOC_START_LON, 
          ST_DISTANCE_SPHERE(transport.LOC_START, POINT($LOC_LON, $LOC_LAT)) AS DIST 
        FROM 
          TRANSPORT AS transport                           
        WHERE 
          ST_DISTANCE_SPHERE(transport.LOC_START, POINT($LOC_LON, $LOC_LAT)) <= $dist AND transport.DRIVER_IDX=0 AND transport.STATUS='W'"; // 10km 이내
          //ST_DISTANCE_SPHERE(transport.LOC_START, POINT($LOC_LON, $LOC_LAT)) >=20000 AND DRIVER_IDX=0 AND STATUS='W'"; // 20000=20km T-map과 거의 비슷,  m 단위임
      //print_r($sqldata);
      $query = $this->db->query($sqldata);
      // print_r($sqldata);
      // exit;
      if($query){
        foreach ($query->getResult() as $row) {
          $total=$row->total;
        }
      } else {
        $total=0;
      }

      if(!$total || $total==0) {        
        ajaxReturn(RESULT_FAIL,"현재 대기중인 호출이 없습니다.","");
        return;        
      }
       
      if($total-$from>9) {
        $to=9;      
      } else {
        $to=$total-$from;
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
          ST_DISTANCE_SPHERE(transport.LOC_START, POINT($LOC_LON, $LOC_LAT)) AS DIST
        FROM 
          TRANSPORT AS transport         
        WHERE 
          ST_DISTANCE_SPHERE(transport.LOC_START, POINT($LOC_LON, $LOC_LAT)) <= $dist AND transport.DRIVER_IDX=0 AND transport.STATUS='W' 
        ORDER BY 
          $order $by 
        LIMIT 
          $from, $to";  // 10km 이내

      // print_r($sqldata);
      $result=$this->db->query($sqldata);
      $data['transport'] = $result->getResultArray();

      if ($data['transport'][0]['TR_IDX']!="") {
        for( $k=0;$k<$to;$k++ ) {

          //USER정보를 가져온다.
          $USER_IDX=$data['transport'][$k]['USER_IDX'];
          $user_info=array();

          //select user for driver
          $user_builder = $this->db->table("MEMBER as user");
    
          $user_builder->select('user.USER_IDX, user.USER_ID, user.USER_NAME, user.PHONE, user.NICK_NAME, user.IMAGE ');  
    
          //$user_builder->select('user.USER_IDX, IFNULL(user.USER_NAME, "게스트") as user.USER_NAME, user.PHONE, IFNULL(user.NICK_NAME, "게스트") as user.NICK_NAME, user.IMAGE');
    
          $user_builder->where('user.USER_IDX', $USER_IDX);     
          $user_data = $user_builder->get()->getResult('array');   
    
          if($preg_match_result = preg_match("/p_/u", $user_data[0]['USER_ID'])){
            $user_data[0]['USER_ID']="게스트";
            $user_data[0]['USER_NAME']="게스트";
            $user_data[0]['NICK_NAME']="게스트";
          } 

          array_push($user_info, $user_data[0]);
       
          $data['transport'][$k]['USER_INFO']=array();
          array_push($data['transport'][$k]['USER_INFO'], $user_info[0]);
    
          //pet정보를 가져온다.
          $data['transport'][$k]['PETLIST'] = array();
          
          $pet = $data['transport'][$k]['PET_LIST'];
      
          $tmp = explode(',',$pet); 
          $pet_total  = count($tmp); 
      
          /// $data['transport']=['0','1','pet','3','4','5','6','7'];
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
          }
  
          $petlist[0]['total'] = $pet_total;   
          array_push($data['transport'][$k]['PETLIST'], $petlist[0]);  
        }

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
  
  public function mylist() {  
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



    
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.DRIVER_IDX', "left inner"); // added left here
   // $builder->where('transport.TR_IDX', $TR_IDX); 
    $builder->where('transport.DRIVER_IDX', $DRIVER_IDX);  
    $builder->where('transport.STATUS !=', 'W');  
    $builder->where('transport.STATUS !=', 'C'); 
    $builder->where('transport.STATUS !=', 'E'); 
    $total = $builder->countAllResults();
    
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"선택하신 정보가 없습니다.","");
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
        transport.IS_USER_SHOW
      FROM 
        TRANSPORT AS transport         
      WHERE 
        transport.DRIVER_IDX='{$DRIVER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";

      // print_r($sqldata);
      $result=$this->db->query($sqldata);
      if($result) {
        $data['transport'] = $result->getResultArray();

        for( $k=0;$k<$total;$k++ ) {
          //USER정보를 가져온다.
          $USER_IDX=$data['transport'][0]['USER_IDX'];
          $user_info=array();

          //select user for driver
          $user_builder = $this->db->table("MEMBER as user");    
          $user_builder->select('user.USER_IDX, user.USER_ID, user.USER_NAME, user.PHONE, user.NICK_NAME, user.IMAGE ');      
          //$user_builder->select('user.USER_IDX, IFNULL(user.USER_NAME, "게스트") as user.USER_NAME, user.PHONE, IFNULL(user.NICK_NAME, "게스트") as user.NICK_NAME, user.IMAGE');    
          $user_builder->where('user.USER_IDX', $USER_IDX);     
          $user_data = $user_builder->get()->getResult('array');   
    
          if($preg_match_result = preg_match("/p_/u", $user_data[0]['USER_ID'])){
            $user_data[0]['USER_ID']="게스트";
            $user_data[0]['USER_NAME']="게스트";
            $user_data[0]['NICK_NAME']="게스트";
          } 
          array_push($user_info, $user_data[0]);
          $data['transport'][$k]['USER_INFO']=array();
          array_push($data['transport'][$k]['USER_INFO'], $user_info[0]);

          $data['transport'][$k]['PETLIST'] = array();
          
          $pet = $data['transport'][$k]['PET_LIST'];
      
          $tmp = explode(',',$pet); 
          $pet_total=count($tmp); 
      
          /// $data['transport']=['0','1','pet','3','4','5','6','7'];
          $petlist=array();
        
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
          }
           // array_push($petlist['pet_list'], $petlist);
           $petlist[0]['total'] = $pet_total;   
          array_push($data['transport'][$k]['PETLIST'], $petlist[0]);  
        }
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

      $data['transport'][0]['USER_INFO']=array();
      array_push($data['transport'][0]['USER_INFO'], $user_info[0]);
  

      
      //pet정보를 가져온다.
      $data['transport'][0]['PETLIST'] = array();
      
      $pet = $data['transport'][0]['PET_LIST'];
 
      $tmp      = explode(',',$pet); 
      $total    = count($tmp);  
      $petlist  = array();

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
        //select

        $review_builder->select('review_user.IDX AS REVIEW_USER_IDX, review_user.USER_IDX, review_user.DRIVER_IDX, review_user.TR_IDX, review_user.STAR, review_user.COMMENT');
        // $builder->where('pet.USER_IDX', $USER_IDX);   
        $review_builder->where('review_user.USER_IDX', $USER_IDX);  
        $review_builder->orderBy('review_user.CREATED_AT','DESC');
        //$review_data = $review_builder->get(5, 0)->getResult('array');   
        $review_data = $review_builder->get()->getResult('array');  
        array_push($review_list, $review_data[0]);    
      }
 
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

  public function accept() {  
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
   
    $TR_IDX   = esc($request->getPost('tr_idx'));
    $loc_now_lon   = esc($request->getPost('loc_now_lon'));
    $loc_now_lat   = esc($request->getPost('loc_now_lat'));

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where(['TR_IDX' => $TR_IDX, 'STATUS' => 'W', 'DRIVER_IDX' => '0'],);
  //  $builder->where('transport.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"승락할 승인번호가 없습니다.(이미 다른기사에게 배정되었습니다.)","");
      return;        
    }

    //update
    $newData = ['STATUS'=>'G','DRIVER_IDX'=>$DRIVER_IDX,];

    $this->db->transBegin();

    $data="UPDATE TRANSPORT SET ";  
    $data.="STATUS        = 'G', ";  
    $data.="DRIVER_IDX    = '".$DRIVER_IDX."', ";  
    $data.="DRIVER_START     = POINT($loc_now_lon, $loc_now_lat) "; 
    $data.="WHERE TR_IDX='".$TR_IDX."'"; 
    //중복 체크할지는 나중에
    //print_r($data);

    $this->db->query($data);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='호출 승락 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
      return;
    } else {
      $this->db->transCommit();      
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

        //  푸쉬하기
        //  수신인 확인
        $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];
        $builder = $this->db->table("MEMBER");
        $builder->select($fields);
        $builder->where('USER_IDX', $PushData[0]['USER_IDX']); 
        //$UserMember = $builder->get()->getResult('array');   
        $UserMember = $builder->get()->getResult();   

        //예약 배차 대화 일반 결제 공지 이벤트 기타 
        //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
        $pushNoti     = new Pushnoti();
        $dataMessage  = array(); //notification 데이터
        $dataMessage['title']     = "팻글택시의 드라이버가 배정되었습니다.";
        $dataMessage['priority']  = "high";
        $dataMessage['message']   = "팻글택시의 드라이버가 배정되었습니다.";
        $dataMessage['mtype']     = "BOOKING"; 
        $dataMessage['url']       = "도착주소"; 
        $dataMessage['user_img_url']    = $UserMember[0]->IMAGE; 
        $dataMessage['driver_img_url']  = $PushData[0]['DRIVER_IMAGE']; 
        //$dataMessage['body']      = "아이폰 바디입니다.";

        $ReturnPushData = $pushNoti->send($UserMember[0]->PUSH_TOKEN, $dataMessage,$UserMember[0]->APP_TYPE);

        if($ReturnPushData['success']==1) {
          $DELIVERY = date('Y-m-d H:i:s');
          $pushResultData = ['TR_IDX'=>$TR_IDX,'USER_IDX'=>$PushData[0]['USER_IDX'],'PID'=>$ReturnPushData['results'][0]['message_id'],'APP_TYPE'=>$UserMember[0]->APP_TYPE,'DEVICE_ID'=>$UserMember[0]->DEVICE_ID,'PUSH_TOKEN'=>$UserMember[0]->PUSH_TOKEN,'MESSAGE'=>$dataMessage['message'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'Y',];
 
          $App_push_messagesModel = new App_push_messagesModel();
          $PushRecord=$App_push_messagesModel->insert($pushResultData);
        }   
      }

      $message='호출이 승락되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;
    }
  }

  public function send_eta() {  
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
      ajaxReturn(RESULT_FAIL,"드라이버 정보가 안넘어 왔습니다.","");
      return;
    }
   
    $TR_IDX         = esc($request->getPost('tr_idx'));
    $E_ARRIVE_TIME  = esc($request->getPost('eta'));
 
    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
  //  $builder->where('transport.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"호출번호가 없습니다.","");
      return;        
    }

    //update
    $newData = ['E_ARRIVE_TIME'=>$E_ARRIVE_TIME,];

    $this->db->transBegin();
    $builder = $this->db->table("TRANSPORT");   
    $builder->where('TR_IDX', $TR_IDX );
    $result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='도착시간 전송 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
      return;
    } else {
      $this->db->transCommit();     
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
      //  푸쉬하기
      //  수신인 확인
      $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];
      $builder = $this->db->table("MEMBER");
      $builder->select($fields);
      $builder->where('USER_IDX', $PushData[0]['USER_IDX']); 
      //$UserMember = $builder->get()->getResult('array');   
      $UserMember = $builder->get()->getResult();   

      //예약 배차 대화 일반 결제 공지 이벤트 기타 
      //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
      $pushNoti     = new Pushnoti();
      $dataMessage  = array(); //notification 데이터
      $dataMessage['title']     = "팻글택시의 예상 도착시간 ".$TR_IDX." 분입니다.";
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

        $App_push_messagesModel = new App_push_messagesModel();
        $PushRecord=$App_push_messagesModel->insert($pushResultData);

        // print_r($PushRecord);
        }
      }
      $message='도착시간이 전송되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;  
    }
  }

  public function change_status() {  
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
      ajaxReturn(RESULT_FAIL,"드라이버 정보가 안넘어 왔습니다.","");
      return;
    }
   
    $TR_IDX = esc($request->getPost('tr_idx'));
    $STATUS = esc($request->getPost('status'));//현재 상태. W:대기,G:기사배정  M: 출발지로 이동중, A: 출발지 도착, D:운행중, C: 운행취소, E:운행완료

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
  //  $builder->where('transport.USER_IDX', $USER_IDX); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"호출번호가 없습니다.","");
      return;        
    }

    //update
    $newData = ['STATUS'=>$STATUS,];

    $this->db->transBegin();
    $builder = $this->db->table("TRANSPORT");   
    $builder->where('TR_IDX', $TR_IDX );
    $result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='도착 전송 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
      return;
    } else {
      $this->db->transCommit();       
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

        //  푸쉬하기
        //  수신인 확인
        $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];
        $builder = $this->db->table("MEMBER");
        $builder->select($fields);
        $builder->where('USER_IDX', $PushData[0]['USER_IDX']); 
        //$UserMember = $builder->get()->getResult('array');   
        $UserMember = $builder->get()->getResult();   

        //현재 상태. W:대기,G:기사배정  M: 출발지로 이동중, A: 출발지 도착, D:운행중, C: 운행취소, E:운행완료
        if($STATUS=="G"){
          $title="팻글택시가 기사배정이 되었습니다.";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } else if($STATUS=="M"){
          $title="팻글택시가 출발지로 이동중입니다.";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } else if($STATUS=="A"){
          $title="팻글택시가 출발지에 도착하였습니다.";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } else if($STATUS=="D"){
          $title="팻글택시가 운행중입니다.";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } else if($STATUS=="C"){
          $title="팻글택시가 운행취소되었습니다..";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } else if($STATUS=="E"){
          $title="팻글택시가 운행완료되었습니다..";
          $message="차량번호  ".$PushData[0]['DRIVER_CAR_NUM']." 입니다.";
        } 

        //예약 배차 대화 일반 결제 공지 이벤트 기타 
        //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
        $pushNoti     = new Pushnoti();
        $dataMessage  = array(); //notification 데이터
        $dataMessage['title']     = $title;
        $dataMessage['priority']  = "high";
        $dataMessage['message']   = $message;
        $dataMessage['mtype']     = "BOOKING"; 
        $dataMessage['url']       = "도착주소"; 
        $dataMessage['user_img_url']    = $UserMember[0]->IMAGE; 
        $dataMessage['driver_img_url']  = $PushData[0]['DRIVER_IMAGE']; 
        //$dataMessage['body']      = "아이폰 바디입니다.";

        $ReturnPushData = $pushNoti->send($UserMember[0]->PUSH_TOKEN, $dataMessage,$UserMember[0]->APP_TYPE);

      if($ReturnPushData['success']==1){
        $DELIVERY = date('Y-m-d H:i:s');
        $pushResultData = ['TR_IDX'=>$TR_IDX,'USER_IDX'=>$PushData[0]['USER_IDX'],'PID'=>$ReturnPushData['results'][0]['message_id'],'APP_TYPE'=>$UserMember[0]->APP_TYPE,'DEVICE_ID'=>$UserMember[0]->DEVICE_ID,'PUSH_TOKEN'=>$UserMember[0]->PUSH_TOKEN,'MESSAGE'=>$dataMessage['message'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'Y',];
 
        $App_push_messagesModel = new App_push_messagesModel();
        $PushRecord=$App_push_messagesModel->insert($pushResultData);

        }
      }
            
      //사용자에게 푸쉬 종료
      $message='도착이 전송되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;  
    }
  }

  public function start() {  
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
      ajaxReturn(RESULT_FAIL,"드라이버 정보가 넘어오지 않았습니다.","");
      return;
    }
   
    $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS', 'A'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"운행을 시작할 호출이 없습니다.","");
      return;        
    }


    //드라이버 정보중 CCTV url을 가져온다.

    //select
    $builder = $this->db->table("MEMBER as member");
    $builder->select('member.*, driver_join_info.*');
    $builder->join('DRIVER_JOIN_INFO as driver_join_info', 'member.USER_IDX = driver_join_info.USER_IDX', "left inner"); // added left here
    //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
    //  $builder->where('user.*, $what)->countAllResults(),  

    //$builder->orderBy('user.USER_IDX','DESC');
    $builder->where('member.USER_IDX', $DRIVER_IDX);  
    $data['driver'] = $builder->get()->getResult('array');   

    //print_r($data['driver'][0]['CCTV_IDX']);

 
    //CCTV를 시작한다.
    $RECORD_NUMBER = date('YmdHis');  
    $post['company'] = "petgle"; 
    $post['source'] = "rtsp://".$data['driver'][0]['CCTV_IDX']."/stream2"; 
    $post['playback'] = $DRIVER_IDX."_".$RECORD_NUMBER;  

    //$url='https://m-stream-api-test.bbidc-cdn.com/api/source/0';

    $url='https://m-stream-api-test.bbidc-cdn.com/api/playback/0';

    $host_info = explode("/", $url);
    $port = $host_info[0] == 'https:' ? 443 : 80;

    $oCurl = curl_init();
    curl_setopt($oCurl, CURLOPT_PORT, $port);
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_POST, 1); // POST 전송 여부
    curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($oCurl, CURLOPT_POSTFIELDS,  $post);                       
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $ret = curl_exec($oCurl);
    curl_close($oCurl);

    $retArr = json_decode($ret, true); // 결과배열

    // print_r($retArr);
    // exit;
    if($retArr['success']!=''){
    // $retArr['domain'] && !empty($retArr['domain'])){
      $playback  = $retArr['playback'];
      // print_r($playback);
      // echo $data['driver'][0]['TR_IDX'];
      // exit;
      if (!empty($playback)) {
                    
    
      }

    }  else {
      // $message='CCTV 변경 중 오류가 발생했습니다.';
      // ajaxReturn(RESULT_FAIL,$message,$newData);
      // return;
    }   
    //cctv시작 종료

   //update
        $UPDATED_AT = date('Y-m-d H:i:s');
        $newData = ['STATUS'=>'D','CCTV_URL'=>$playback,'START_TIME'=>$UPDATED_AT,'UPDATED_AT'=>$UPDATED_AT,];

        $this->db->transBegin();
        $builder = $this->db->table("TRANSPORT");   
        $builder->where('TR_IDX', $TR_IDX );
        $result = $builder->update($newData);

        if ($this->db->transStatus() === FALSE) {
          $this->db->transRollback();
          $message='운행 중으로 변경 중 오류가 발생했습니다.';
          ajaxReturn(RESULT_FAIL,$message,$newData);
          return;
        } else {
          $this->db->transCommit();    
        }   
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
            $pushNoti     = new Pushnoti();
            $dataMessage  = array(); //notification 데이터
            $dataMessage['title']     = "팻글택시가 운행을 시작하였습니다.";
            $dataMessage['priority']  = "high";
            $dataMessage['message']   = "차량번호  ".$PushData[0]['DRIVER_CAR_NUM']."의 운행이 시작되었습니다.";
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
                
    
          //cctv_log에 넣기
          $Cctv_Log_NewData = array(
            'USER_IDX'    => $PushData[0]['USER_IDX'],
            'STATUS'      => 'S',
            'DRIVER_IDX'  => $DRIVER_IDX,
            'RTSB'        => $post['source'],
            'URL'         => $playback
            );
          
    // insert
    $Log_cctv_std = new Log_cctv_Model();
    $Log_cctv_std->insert($Cctv_Log_NewData);          

    //사용자에게 푸쉬 종료
    $message='운행 중으로 변경되었습니다.';
    ajaxReturn(RESULT_SUCCESS,$message,$newData);
    return; 
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
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }
   
    $TR_IDX         = esc($request->getPost('tr_idx'));
    $loc_long_now   = esc($request->getPost('loc_long_now'));
    $loc_lat_now    = esc($request->getPost('loc_lat_now'));
    $DEVICE_STATUS  = esc($request->getPost('device_status'));
    
    $FEE      = esc($request->getPost('fee'));
    $METERS   = esc($request->getPost('meters'));
    $MINUTES  = esc($request->getPost('minutes'));

    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.DRIVER_IDX', $USER_IDX); 
    $builder->where('transport.STATUS!=', 'W'); 
    $builder->where('transport.STATUS!=', 'C'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"운행 중인 호출이 없습니다.","");
      return;        
    }

    //select
    $builder->select('transport.USER_IDX AS USER_IDX, transport.DRIVER_IDX AS DRIVER_IDX, transport.STATUS AS STATUS');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $builder->where('transport.DRIVER_IDX', $USER_IDX); 
    $builder->where('transport.STATUS!=', 'W'); 
    $builder->where('transport.STATUS!=', 'C');  

    $data['transport'] = $builder->get()->getResult('array'); 

    $USER_IDX=$data['transport'][0]['USER_IDX'];
    $DRIVER_IDX=$data['transport'][0]['DRIVER_IDX'];
    $STATUS=$data['transport'][0]['STATUS'];

    $this->db->transBegin();
    $data="INSERT INTO LOG_LOCATION SET ";  
    $data.="USER_IDX  = '".$USER_IDX."', ";  
    $data.="DRIVER_IDX  = '".$DRIVER_IDX."', ";  
    $data.="TR_IDX    = '".$TR_IDX."', ";
    $data.="STATUS    = '".$STATUS."', "; 
    $data.="DEVICE_STATUS = '".$DEVICE_STATUS."', ";
    $data.="LOC       = POINT($loc_long_now, $loc_lat_now), "; 
    $data.="FEE       = '".$FEE."', ";  
    $data.="METERS    = '".$METERS."', ";
    $data.="MINUTES   = '".$MINUTES."' ";
    
    $this->db->query($data);   

    $ReturnData = array(
      'USER_IDX'      => $USER_IDX,
      'DRIVER_IDX'    => $DRIVER_IDX,
      'TR_IDX'        => $TR_IDX,
      'STATUS'        => $STATUS,
      'LOG_LONG_NOW'  => $loc_long_now,
      'LOC_LAT_NOW'   => $loc_lat_now,
      'FEE'           => $FEE,
      'METERS'        => $METERS,
      'MINUTES'       => $MINUTES
    );

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='현재 위치가 저장되지 않았습니다.';
      ajaxReturn(RESULT_FAIL,$message,$ReturnData);
      return;
    } else {
      $this->db->transCommit();
      $message= '정상적으로 현재 위치가 저장되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
      return;
    }
  }

  public function arrived() {  
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
   
    $TR_IDX   = esc($request->getPost('tr_idx'));
    $LOC_LONG = esc($request->getPost('loc_lon_now'));
    $LOC_LAT  = esc($request->getPost('loc_lat_now'));

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS', 'D'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"운행을 종료할 호출이 없습니다.","");
      return;        
    }


    //드라이버 정보중 CCTV url을 가져온다.

    //select
    $builder->select('CCTV_URL,');
    //$builder->join('DRIVER_JOIN_INFO as driver_join_info', 'member.USER_IDX = driver_join_info.USER_IDX', "left inner"); // added left here
    //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
    //  $builder->where('user.*, $what)->countAllResults(),  

    //$builder->orderBy('user.USER_IDX','DESC');
    $builder->where('TR_IDX', $TR_IDX);  
    $data['driver'] = $builder->get()->getResult('array');   
    
//   print_r( $data);
//  exit;
    //도착지 정보 입력  
    $this->db->transBegin();
  //   $sqldata="INSERT INTO LOG_LOCATION SET ";  
  //   $sqldata.="USER_IDX  = '".$USER_IDX."', ";  
  //  // $data.="DRIVER_IDX  = '".$data['driver'][0]['DRIVER_IDX']."', ";  
  //  $sqldata.="DRIVER_IDX  = '".$USER_IDX."', ";  
  // $sqldata.="TR_IDX    = '".$TR_IDX."', ";
  //   $sqldata.="STATUS    = 'E', "; 
  //   $sqldata.="LOC       = POINT($LOC_LONG, $LOC_LAT) ";  
  
  //   $this->db->query($sqldata);   

  //   if ($this->db->transStatus() === FALSE) {
  //     $this->db->transRollback();
  //     // $message='정상적으로 요청이 처리되지 않았습니다.';
  //     // ajaxReturn(RESULT_FAIL,$message,'');
  //     // return;
  //   } else {
  //     $this->db->transCommit();
  //     // $message= '정상적으로 요청이 처리되었습니다.';
  //     // ajaxReturn(RESULT_SUCCESS,$message,'');  
  //     // return;
  //  }
      //Cctv를 종료한다.
      $post['company'] = "petgle";  

      //$post['playback'] = $data['driver'][0]['CCTV_URL'];
      //$post['playback'] = $USER_IDX."_20220210007";  

      //petgle/_definst_/15_20220425183632.stream
      //이걸 잘라야 함 숫자만 가져와야 함
      $cctv_info  = explode("/", $data['driver'][0]['CCTV_URL']);
      $cctv_url   = explode(".", $cctv_info[2]);
      $post['playback'] = $cctv_url[0];
      // print_r($data['driver'][0]['CCTV_URL']);
      // print_r("<br>");
      // print_r($cctv_url[0]);

      $url='https://m-stream-api-test.bbidc-cdn.com/api/playback/0';

      $host_info = explode("/", $url);
      $port = $host_info[0] == 'https:' ? 443 : 80;
      $post = http_build_query($post, '', '&');

      $oCurl = curl_init();
      curl_setopt($oCurl, CURLOPT_PORT, $port);
      curl_setopt($oCurl, CURLOPT_URL, $url);
      curl_setopt($oCurl, CURLOPT_POST, 0);
      curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
      curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($oCurl, CURLOPT_POSTFIELDS,  $post);                       
      curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
      $ret = curl_exec($oCurl);
      curl_close($oCurl);

      $retArr = json_decode($ret, true); // 결과배열
      // print_r($retArr);

      if ($retArr['success']!=''){
        // print_r('CCTV 정상 종료');  //임시     

      } else {
        // print_r('CCTV 비정상 종료'); //임시
      }   
      //CCTV 종료

      //update
      $UPDATED_AT = date('Y-m-d H:i:s');

      $newData = ['STATUS'=>'E','ARRIVE_TIME'=>$UPDATED_AT,'UPDATED_AT'=>$UPDATED_AT,];

      $this->db->transBegin();
      $builder = $this->db->table("TRANSPORT");   
      $builder->where('TR_IDX', $TR_IDX );
      $result = $builder->update($newData);

      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $message='운행 종료 변경 중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,$newData);
        return;
      } else {
        $this->db->transCommit(); 
      
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

      //print_r($PSqlSel);
      $PResult=$this->db->query($PSqlSel);
      if($PResult) {
        $PushData = $PResult->getResultArray();
        //  수신인 확인
        $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];
        $builder = $this->db->table("MEMBER");
        $builder->select($fields);
        $builder->where('USER_IDX', $PushData[0]['USER_IDX']); 
        //$UserMember = $builder->get()->getResult('array');   
        $UserMember = $builder->get()->getResult();   
        // print_r($UserMember[0]->USER_IDX);
        // print_r("<br>");

        //예약 배차 대화 일반 결제 공지 이벤트 기타 
        //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
        $pushNoti     = new Pushnoti();
        $dataMessage  = array(); //notification 데이터
        $dataMessage['title']     = "운행 종료로 변경되었습니다.";
        $dataMessage['priority']  = "high";
        $dataMessage['message']   = "차량번호  ".$PushData[0]['DRIVER_CAR_NUM']."의 운행 종료로 변경되었습니다.";
        $dataMessage['mtype']     = "BOOKING"; 
        $dataMessage['url']       = "도착주소"; 
        $dataMessage['user_img_url']    = $UserMember[0]->IMAGE; 
        $dataMessage['driver_img_url']  = $PushData[0]['DRIVER_IMAGE']; 
        //$dataMessage['body']      = "아이폰 바디입니다.";

        $ReturnPushData = $pushNoti->send($UserMember[0]->PUSH_TOKEN, $dataMessage,$UserMember[0]->APP_TYPE);

        if ($ReturnPushData['success']==1) {
          $DELIVERY = date('Y-m-d H:i:s');
          $pushResultData = ['TR_IDX'=>$TR_IDX,'USER_IDX'=>$PushData[0]['USER_IDX'],'PID'=>$ReturnPushData['results'][0]['message_id'],'APP_TYPE'=>$UserMember[0]->APP_TYPE,'DEVICE_ID'=>$UserMember[0]->DEVICE_ID,'PUSH_TOKEN'=>$UserMember[0]->PUSH_TOKEN,'MESSAGE'=>$dataMessage['message'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'Y',];

          $App_push_messagesModel = new App_push_messagesModel();
          $PushRecord=$App_push_messagesModel->insert($pushResultData);
        }
      }
      //사용자에게 푸쉬 종료
      $message='운행 종료로 변경되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;
    }
  }

  public function add_charge() {  
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
   
    $TR_IDX     = esc($request->getPost('tr_idx'));
    $A_FEE_MEMO = esc($request->getPost('a_fee_memo'));
    $A_FEE      = esc($request->getPost('a_fee'));

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS!=', 'W'); 
    $builder->where('STATUS!=', 'G'); 
    $builder->where('STATUS!=', 'M'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"추가요금 처리할 호출이 없거나 추가 할 수 없는 운행상태입니다.","");
      return;        
    }

    //드라이버 정보중 CCTV url을 가져온다.

    //select
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS!=', 'W'); 
    $builder->where('STATUS!=', 'G'); 
    $builder->where('STATUS!=', 'M'); 
    $data['driver'] = $builder->get()->getResult('array');   

    //print_r($data['driver'][0]['CCTV_IDX']);

    //update
    $UPDATED_AT = date('Y-m-d H:i:s');

    $newData = ['A_FEE_MEMO'=>$A_FEE_MEMO,'A_FEE'=>$A_FEE,'UPDATED_AT'=>$UPDATED_AT,];

    $this->db->transBegin();
    $builder = $this->db->table("TRANSPORT");   
    $builder->where('TR_IDX', $TR_IDX );
    $result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='추가요금 처리 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
      return;
    } else {
      $this->db->transCommit();      
      //사용자에게 푸쉬 시작

      //사용자에게 푸쉬 종료

      $message='추가요금이 처리되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;
    }

  }

  public function dis_charge() {  
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
   
    $TR_IDX      = esc($request->getPost('tr_idx'));
    $DC_FEE_MEMO = esc($request->getPost('dc_fee_memo'));
    $DC_FEE      = esc($request->getPost('dc_fee'));

    //count
    $builder = $this->db->table("TRANSPORT");
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS', 'E'); 
    $total = $builder->countAllResults();
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"할인요금 처리 중 오류가 발생했습니다.","");
      return;        
    }


    //드라이버 정보중 CCTV url을 가져온다.

    //select
    $builder->select('*');
    //$builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('TR_IDX', $TR_IDX);  
    $builder->where('STATUS', 'E'); 
    $data['driver'] = $builder->get()->getResult('array');   

    //print_r($data['driver'][0]['CCTV_IDX']);

    //추후 할인요금은 전체 금액을 넘을 수 없게 확인 한다.22-03-28

    //update
    $UPDATED_AT = date('Y-m-d H:i:s');

    $newData = ['DC_FEE_MEMO'=>$DC_FEE_MEMO,'DC_FEE'=>$DC_FEE,'UPDATED_AT'=>$UPDATED_AT,];

    $this->db->transBegin();
    $builder = $this->db->table("TRANSPORT");   
    $builder->where('TR_IDX', $TR_IDX );
    $result = $builder->update($newData);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='할인요금 처리 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,$newData);
      return;
    } else {
      $this->db->transCommit();    
           //사용자에게 푸쉬 시작

          //사용자에게 푸쉬 종료

      $message='할인요금이 처리되었습니다.';
      ajaxReturn(RESULT_SUCCESS,$message,$newData);
      return;
    }
  }




  public function endlist() {  
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
   
   // $TR_IDX   = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table("TRANSPORT_END as transport");
    $builder->select('transport.TR_IDX');
    $builder->join('MEMBER as member', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
   // $builder->where('transport.TR_IDX', $TR_IDX); 
    $builder->where('transport.DRIVER_IDX', $USER_IDX);  
    // $builder->where('transport.STATUS !=', 'W');  
    // $builder->where('transport.STATUS !=', 'C'); 
    // $builder->where('transport.STATUS !=', 'E'); 
    $total = $builder->countAllResults();
    
    if($total==0) {        
      ajaxReturn(RESULT_FAIL,"선택하신 정보가 없습니다.","");
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
                transport.IS_USER_SHOW
              FROM 
                TRANSPORT_END AS transport         
              WHERE 
              transport.DRIVER_IDX='{$USER_IDX}'";
              // transport.DRIVER_IDX='{$USER_IDX}' AND transport.STATUS!='W' AND transport.STATUS!='C' AND transport.STATUS!='E'";




              
    // print_r($sqldata);
    $result=$this->db->query($sqldata);
      if($result) {
        $data['transport'] = $result->getResultArray();




      //USER정보를 가져온다.
      $USER_IDX=$data['transport'][0]['USER_IDX'];

      $user_info=array();

      //select user for driver
      $user_builder = $this->db->table("MEMBER as user");

      $user_builder->select('user.USER_IDX, user.USER_ID, user.USER_NAME, user.PHONE, user.NICK_NAME, user.IMAGE ');  

      //$user_builder->select('user.USER_IDX, IFNULL(user.USER_NAME, "게스트") as user.USER_NAME, user.PHONE, IFNULL(user.NICK_NAME, "게스트") as user.NICK_NAME, user.IMAGE');

      $user_builder->where('user.USER_IDX', $USER_IDX);     
      $user_data = $user_builder->get()->getResult('array');   

      if($preg_match_result = preg_match("/p_/u", $user_data[0]['USER_ID'])){
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




}//class