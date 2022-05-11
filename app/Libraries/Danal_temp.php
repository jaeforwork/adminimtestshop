<?php
namespace App\Libraries;

/* 다날 결제 */

class Danal {
  private static $SERVER_KEY="AAAAmOzhuTc:APA91bGyUAmbN_hREyz97RtdXaUZBQ35hdZxdE33lFNc8J02Hs9Be0NZCVAwJZkJYQWZSnTiVCX1eomr_HO0ZaV2SrbjvIwoO-zO2Rp4-zHHOPUdYUWEOQdYRNJMqKSU0SbNm8wJJbLk";
    
  public function getbillkey($regId, $dataMessage, $type) {
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

    $ISBILL = esc($request->getPost('ISBILL'));  



    $ORDERID    = $dataMessage['ORDERID'];
    $ITEMNAME   = $dataMessage['ITEMNAME'];
    $AMOUNT     = $dataMessage['AMOUNT'];
    $CARDCODE   = $dataMessage['CARDCODE'];
    $CARDNAME   = $dataMessage['CARDNAME'];
    $CARDNO     = $dataMessage['CARDNO'];
    $QUOTA      = $dataMessage['QUOTA'];
    $CARDAUTHNO = $dataMessage['CARDAUTHNO'];
    $USERNAME   = $dataMessage['USERNAME'];
    $USERPHONE  = $dataMessage['USERPHONE'];
    $USERID     = $dataMessage['USERID'];




   // $url = 'https://fcm.googleapis.com/fcm/send';
    $url = 'http://www.imchoi.shop/theme/responsive/api/card_otissuebillkey_result.php';

    $fields = array (
                   'data' => array ("ISBILL" => $ISBILL,"ORDERID" => $ORDERID,"ITEMNAME" => $ITEMNAME, "AMOUNT" => $AMOUNT, "CARDCODE" => $CARDCODE, "CARDCODE" => $CARDCODE, "QUOTA" => $QUOTA, "CARDAUTHNO" => $CARDAUTHNO, "USERNAME" => $USERNAME, "USERPHONE" => $USERPHONE, "USERID" => $USERID)
      );

   
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
 
    if($result === false) {
      return false;
    } else {
      //echo $ret;
      $retArr = json_decode($result, true); // 결과배열
      return $retArr;
    }
    curl_close($ch);
  }
}

   // echo $result;
