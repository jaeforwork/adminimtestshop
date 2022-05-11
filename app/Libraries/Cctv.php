<?php
namespace App\Libraries;

class Cctv {
  public function source_make($source) {
    $post['company'] = "petgle"; 
    //$post['source'] = "rtsp://petgle2:eoqkrpetgle@223.171.128.40:554/stream2";  
    $post['source'] = "rtsp://".$source."/stream2"; 
    $url='https://m-stream-api-test.bbidc-cdn.com/api/source/0';

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
    $result = curl_exec($oCurl);
    curl_close($oCurl);

    return $result;
  }

  
  public function start_cctv($post) {   
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

    return $result;
  }


  
  public function end_cctv($post) {   
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

    return $result;
  }
