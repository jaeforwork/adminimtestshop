<?php
namespace App\Controllers\Driver;

use App\Controllers\BaseController;
use App\Models\Chat_singleModel;
use App\Models\Chat_room_multiModel;
use App\Models\Chat_multiModel;
use App\Models\App_push_messagesModel;

//use App\Libraries\ValidChecker;
use App\Libraries\Pushnoti;

class Chat extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }  
  
	public function test() {
  
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }
       

    if(!$ACCESS_TOKEN && $ACCESS_TOKEN=='') {
      $message= '로그인 후 이용 하세요.';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    }



    // $ROOM_IDX = esc($request->getPost('tr_idx'));
    $TR_IDX   = esc($request->getPost('tr_idx'));

    $CREATED_AT = date('Y-m-d H:i:s');

    //ROOM_IDX가 없으면 만든다.  

    $builder = $this->db->table("CHAT_ROOM_MULTI");
    $builder->select('*');
    $builder->where(['TR_IDX'=> $TR_IDX,'STATUS'=> 'Y',]); 

    $total = $builder->countAllResults();

    $NewData = ['TR_IDX'=>$TR_IDX,'OWNER_IDX'=>$USER_IDX,'LAST_AT'=>$CREATED_AT,'STATUS'=>'Y','LAST_MESSAGE'=>'채팅방이 개설되었습니다.'];

    if($total==0) {  
      $std = new Chat_room_multiModel();  
      $std->transBegin();
    
      
      // print_r($newData);
      // exit;
      
      $ROOM_IDX=$std->insert($NewData);

      $ReturnData = array(
        'ROOM_IDX'      => $ROOM_IDX,
        'TR_IDX'        => $TR_IDX,
        'OWNER_IDX'     => $USER_IDX,
        'LAST_AT'       => $CREATED_AT,
        'STATUS'        => 'Y',
        'LAST_MESSAGE'  => '채팅방이 개설되었습니다.'
      );
              
      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='채팅방 개설 처리 중 오류가 발생했습니다.';
        ajaxReturn(RESULT_FAIL,$message,$NewData);
      } else {
        $std->transCommit();
        $message='채팅방이 생성되었습니다.';
        ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);
        $tranResult='Y'; 
      }
    } else {
      $message='이미 있는 채팅방이거나 채팅을 할 수 없는 상태입니다.';
      ajaxReturn(RESULT_FAIL,$message,$NewData);
    }
  }

	public function send() {   
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }
       

    if(!$ACCESS_TOKEN && $ACCESS_TOKEN=='') {
      $message= '로그인 후 이용 하세요.';
      ajaxReturn(RESULT_FAIL,$message,""); 
      return;
    }
 
    $TR_IDX   = esc($request->getPost('tr_idx'));
    $to_idx   = esc($request->getPost('to_idx')); //수신자

    $MESSAGE  = esc($request->getPost('message'));
    $CREATED_AT = date('Y-m-d H:i:s');    

    if (!empty($ACCESS_TOKEN) && !empty($USER_IDX)) {
    }


    //ROOM_IDX가 없으면 만든다.  

    $builder = $this->db->table("CHAT_ROOM_MULTI");
    $builder->select('*');
    $builder->where(['TR_IDX'=> $TR_IDX,'STATUS'=> 'Y',]); 

    $total = $builder->countAllResults();

    $NewData = ['TR_IDX'=>$TR_IDX,'OWNER_IDX'=>$USER_IDX,'LAST_AT'=>$CREATED_AT,'STATUS'=>'Y','LAST_MESSAGE'=>'채팅방이 개설되었습니다.'];

    if($total==0) {  
      $std = new Chat_room_multiModel();  
      $std->transBegin();
      
      $ROOM_IDX=$std->insert($NewData);
              
      if ($std->transStatus() === FALSE) {
        $std->transRollback();
      } else {
        $std->transCommit(); 
      }
    } else{ //기존 채팅방의 정보를 가져온다.
      $selectsql="SELECT 
        chat_room_multi.ROOM_IDX,
        chat_room_multi.TR_IDX, 
        chat_room_multi.OWNER_IDX, 
        chat_room_multi.STATUS,
        chat_room_multi.LAST_MESSAGE 
      FROM 
        CHAT_ROOM_MULTI AS chat_room_multi  
      WHERE 
      chat_room_multi.TR_IDX='{$TR_IDX}'";

      //print_r($sqldata);
      $selectresult=$this->db->query($selectsql);
      if($selectresult) {
        $chat_data['chat'] = $selectresult->getResultArray();
        $ROOM_IDX=$chat_data['chat'][0]['ROOM_IDX'];
      }
    }

    // print_r($NewData);
    // print_r("<br>");
      unset($NewData);


      $NewData = array(
        'ROOM_IDX'  => $ROOM_IDX,
        'USER_IDX'  => $USER_IDX,
        'TYPE'      => $TYPE,
        'MESSAGE'   => $MESSAGE,
        'MILISEC'   => $MILISEC
      );
      // print_r($NewData);
      // print_r("<br>");
      //중복 체크할지는 나중에

      $std = new Chat_multiModel();
      $std->transBegin();

      $CHAT_IDX = $std->insert($NewData);
     // $partner_idx = $std->insert($NewData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='';
        ajaxReturn(RESULT_FAIL,$message,$NewData);
      } else {
        $std->transCommit();

        //CHAT_ROOM_MULTI 정보 업데이트

        $NewData = array(
          'LAST_AT'  => $CREATED_AT,
          'LAST_MESSAGE'   => $MESSAGE,
          'UPDATED_AT'  => $CREATED_AT,
        );


        $std = new Chat_room_multiModel();
        $std->transBegin();
    
        $result = $std->update($ROOM_IDX,$NewData);
    
        if ($std->transStatus() === FALSE) {
          $std->transRollback();

        } else {
          $std->transCommit();  
        }  

        $ReturnData = array(
          'CHAT_IDX'      => $CHAT_IDX,
          'ROOM_IDX'      => $ROOM_IDX,
          'USER_IDX'      => $to_idx,
          'DRIVER_IDX'    => $USER_IDX,
          'MESSAGE'       => $MESSAGE,
          'CREATED_AT'    => $CREATED_AT
        );

        //From
        $fields   = ['USER_IDX','NICK_NAME','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];

        $builder = $this->db->table("MEMBER");
        $builder->select($fields);
        $builder->where('USER_IDX', $USER_IDX); 
        $Driver_Member = $builder->get()->getResult('array');  
// print_r($User_Member);
// print_r("<br>");
      //to_idx //수신자 USER_IDX

      $fields   = ['USER_IDX','APP_TYPE','PUSH_TOKEN','DEVICE_ID','IMAGE'];

      $builder = $this->db->table("MEMBER");
      $builder->select($fields);
      $builder->where('USER_IDX', $to_idx); 
      $User_Member = $builder->get()->getResult('array');   
   
      // print_r($Driver_Member[0]['PUSH_TOKEN']);
      // print_r("<br>");
      // print_r($member);
      //   print_r("<br>");
      //   print_r($member['APP_TYPE']);
      //   print_r("<br>");
      //   print_r($member['PUSH_TOKEN']);
      //   print_r("<br>");


      //   $PUSH_USER_IDX=$transport_data['transport'][0]['USER_IDX'];
      //   $PUSH_DRIVER_IDX=$transport_data['transport'][0]['DRIVER_IDX'];
  
      // if($USER_IDX==$PUSH_USER_IDX) {
      //   $PUSH_TOKEN_USER_IDX=$PUSH_DRIVER_IDX;
      // } else if($USER_IDX==$PUSH_DRIVER_IDX) {
      //   $PUSH_TOKEN_USER_IDX=$PUSH_USER_IDX;
      // }
      //   $fields = ['APP_TYPE','PUSH_TOKEN'];
      //   $builder = $this->db->table("MEMBER");
      //   $builder->select($fields);
      //   $builder->where('USER_IDX', $PUSH_TOKEN_USER_IDX); 
      //   $USER = $builder->get()->getResult('array');   
  
        $pushNoti     = new Pushnoti();
        $dataMessage  = array(); //notification 데이터
        $dataMessage['title']     = $Driver_Member[0]['NICK_NAME']."로부터 CHAT 메세지가 도착했습니다.";
        $dataMessage['priority']  = "high";
        $dataMessage['message']   = $MESSAGE;
        $dataMessage['mtype']     = "CHAT"; 
        $dataMessage['url']       = "도착주소"; 
        $dataMessage['user_img_url']    = $User_Member[0]['IMAGE']; 
        $dataMessage['driver_img_url']  = $Driver_Member[0]['IMAGE']; 
  
        //예약 배차 대화 일반 결제 공지 이벤트 기타 
        //BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC
    
        $ReturnPushData = $pushNoti->send($User_Member[0]['PUSH_TOKEN'], $dataMessage,$User_Member[0]['APP_TYPE']);
 // print_r($ReturnPushData);

        //푸쉬 실패시
        $DELIVERY = date('Y-m-d H:i:s');
        if($ReturnPushData['success']==1){
          $DELIVERY = date('Y-m-d H:i:s');
          $pushResultData = ['TR_IDX'=>$TR_IDX, 'USER_IDX'=>$USER_IDX,'PID'=>$ReturnPushData['results'][0]['message_id'],'APP_TYPE'=>$User_Member[0]['APP_TYPE'],'DEVICE_ID'=>$User_Member[0]['DEVICE_ID'],'PUSH_TOKEN'=>$User_Member[0]['PUSH_TOKEN'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'Y',];  
       } else {   
        $pushResultData = ['TR_IDX'=>$TR_IDX, 'USER_IDX'=>$USER_IDX,'PID'=>'0','APP_TYPE'=>$Driver_Member[0]['APP_TYPE'],'DEVICE_ID'=>$Driver_Member[0]['DEVICE_ID'],'PUSH_TOKEN'=>$Driver_Member[0]['PUSH_TOKEN'],'MESSAGE'=>$dataMessage['message'],'DELIVERY'=>$DELIVERY,'STATUS'=>'F','ERROR_TEXT' => $ReturnPushData['results'][0]['error']];

        //Array ( [multicast_id] => 5454239453849240741 [success] => 0 [failure] => 1 [canonical_ids] => 0 [results] => Array ( [0] => Array ( [error] => NotRegistered ) ) )

       // print_r($pushResultData);
        

       }

       $App_push_messagesModel = new App_push_messagesModel();
       $PushRecord=$App_push_messagesModel->insert($pushResultData);

        $message= '';
        ajaxReturn(RESULT_SUCCESS,$message,$ReturnData);  
      }


































		
	}





  public function get() {
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
      // ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      // return;
    }
    $ROOM_IDX  = esc($request->getPost('tr_idx'));

    //count
    $builder = $this->db->table('CHAT_SINGLE AS chat');
    $builder->select('chat.*');
    $builder->where('chat.IS_VIEW', 'N'); 
    $builder->where('chat.ROOM_IDX', $ROOM_IDX); 
    $total = $builder->countAllResults();

    if($total==0){
       ajaxReturn(RESULT_EMPTY,"글이 없습니다.","");
      return;  
    }
    //select
    $builder = $this->db->table('CHAT_SINGLE AS chat');
    $builder->select('chat.*');
    $builder->where('chat.IS_VIEW', 'N'); 
    $builder->where('chat.ROOM_IDX', $ROOM_IDX); 

    $data['chat'] = $builder->get()->getResult('array');  
    
    
    $newData = ['IS_VIEW'=>'Y',];

    $this->db->transBegin();
    //$builder = $this->db->table("MEMBER as member");   
    $builder->where('chat.IS_VIEW', 'N'); 
    $builder->where('chat.ROOM_IDX', $ROOM_IDX); 
    $result = $builder->update($newData);
  
    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      $message='업데이트 중 오류가 발생했습니다.';
      ajaxReturn(RESULT_FAIL,$message,'');
      return;
    } else {
      $this->db->transCommit();          
      $message='업데이트 되었습니다.';
      $data['total']=$total;
      ajaxReturn(RESULT_SUCCESS,$message,$data);    
      return;
    }  
        
  }













}