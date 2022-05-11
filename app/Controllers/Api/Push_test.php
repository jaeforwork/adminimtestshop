<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Member_couponModel;
use App\Models\MemberModel;
use App\Models\App_push_messagesModel;
//use App\Libraries\ValidChecker;
use App\Libraries\Pushnoti;

class Push_test extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }

	public function send() {

    exit;

    //푸쉬하기
    $USER_IDX = 13;

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
    $dataMessage['title']     = "안드로이드 아이폰 동시발송 입니다.";
    $dataMessage['priority']  = "high";
    $dataMessage['message']   = "메세지입니다.";
    $dataMessage['mtype']     = "BOOKING DISPATCH CHAT PAYMENT NOTICE EVENT ETC"; 
    //$dataMessage['body']      = "아이폰 바디입니다.";

    // 아이폰 포멧
   // $token="de5phtxdRkYDt6E6LZsY7V:APA91bEAfHnsx8d5k0oLGhzQQdIMvXsql3NXkuMpaePDIis8k91gm6RKmSJcGOJt_nfOJMWc4LroDcZcEnrGVsnIDrJgpCPl5hasx8Nkdd_z4EoA_EEFdli8nbrEQCsHv4_g9PhMK0OS"; //아이폰 사용자 푸쉬

    //$type="I";  

    $returnData = $pushNoti->send($member[0]->PUSH_TOKEN, $dataMessage,$member[0]->APP_TYPE);
    print_r($returnData);
    print_r("<br>");
    print_r($returnData['success']);
    print_r("<br>");
    print_r($returnData['multicast_id']);
    print_r("<br>");
    print_r($returnData['results'][0]['message_id']);


    //     Array ( [multicast_id] => 3532572454309300915 [success] => 1 [failure] => 0 [canonical_ids] => 0 [results] => Array ( [0] => Array ( [message_id] => 0:1650008644359578%1e96145ef9fd7ecd ) ) )
    // 1
    // 3532572454309300915
    // 0:1650008644359578%1e96145ef9fd7ecd

    //multicast_id":15661265652010697,"success":1,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1649736569431822%b6fde668f9fd7ecd"


    // 안드로이드 포멧

    //$token="fUtSAGMmSOqfc7KKRBsIQW:APA91bGwaklMMWJMLUUDXN_uzSwKzU8gDwt2rzj9fe2_sSbKIj2J04WtSWRVYIHjLY8Rqip9rBu1NBZcCxSnlYioZpx2cCP5E_cLZSfxDJk65jrhtloBtKYcOUhO7uq5Gh3u4O5VHLPQ"; //안드로이드 드라이버 푸쉬

    //$token="cS0vhJUXSCebSsAijz_Mz7:APA91bHCsn31IhIhHK45j0r349Ql7-U_WL7ivgbzfYB4f81kVWPT9NBh-qTS809kzfrGU4vX1XAUw9XkOYyr7paxlpcF_wgrhbFmWIPGFKYCM6n1ZHZqiYxI2ce4rmnKsCzFyBHEPX75"; //안드로이드 사용자 푸쉬

    //$type="A"; // 안드로이드

    //$result=$pushNoti->send($token, $dataMessage,$type);
    //$pushNoti->send($memberInfo->push_token, $dataMessage);
    //print_r($result);

	}

}