<?php
namespace App\Libraries;

/* google firebase 사용한 push notification */

class Pushnoti {
  private static $SERVER_KEY="AAAAmOzhuTc:APA91bGyUAmbN_hREyz97RtdXaUZBQ35hdZxdE33lFNc8J02Hs9Be0NZCVAwJZkJYQWZSnTiVCX1eomr_HO0ZaV2SrbjvIwoO-zO2Rp4-zHHOPUdYUWEOQdYRNJMqKSU0SbNm8wJJbLk";
    
  public function send($regId, $dataMessage, $type) {
    $title    = $dataMessage['title'];
    $message  = $dataMessage['message'];
    $priority = $dataMessage['priority'];
    $mtype    = $dataMessage['mtype'];
    $url      = $dataMessage['url'];
    $user_img_url    = $dataMessage['user_img_url'];
    $driver_img_url    = $dataMessage['driver_img_url'];


    $url = 'https://fcm.googleapis.com/fcm/send';

    if($type=="A") {
      $fields = array (
                'registration_ids' => array ($regId),
                'data' => array ("title" => $title,"message" => $message,"priority" => $priority,"mtype" => $mtype,"url" => $url,"user_img_url" => $user_img_url,"driver_img_url" => $driver_img_url)
          );

    } else if($type=="I"){
      $fields = array (
                'registration_ids' => array ($regId),
                'notification' => array ("title" => $title,"body" => $message,"sound" => "default", "mtype" => $mtype,"url" => $url,"user_img_url" => $user_img_url,"driver_img_url" => $driver_img_url, "priority" => $priority)
      );
    }
   
    $fields = json_encode ( $fields );

    $headers = array(
      'Authorization: key=' . static::$SERVER_KEY,
      'Content-Type: application/json',
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ($ch);
    curl_close($ch);  
 
    if($result === false) {
      return false;
    } else {
      //echo $ret;
      $retArr = json_decode($result, true); // 결과배열
      return $retArr;
    }
    // curl_close($ch);
  }
}

    //echo $result;

    //{"multicast_id":15661265652010697,"success":1,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1649736569431822%b6fde668f9fd7ecd"}]}  // 안드로이드 결과 값

    //Array ( [multicast_id] => 7842312513737679947 [success] => 0 [failure] => 1 [canonical_ids] => 0 [results] => Array ( [0] => Array ( [error] => NotRegistered ) ) )
    
    //{"multicast_id":1125520698116233616,"success":0,"failure":1,"canonical_ids":0,"results":[{"error":"InvalidRegistration"}]} //아이폰 실패

