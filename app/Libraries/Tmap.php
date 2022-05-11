<?php
namespace App\Libraries;

class Tmap {
  private static $APP_KEY="l7xx9f4e3034dbb04ca39269f054f2923070";
  private static $SECRET_KEY="c73e2619f6a04b3a9c7af3523f3ebcc5";

  public function route($param=array()){
    return $this->request("POST","/tmap/routes?version=1", $param);
  }

  public function geotoadd($param=array()){
    return $this->request("GET","/tmap/geo/reversegeocoding?version=1&format=json&callback=result", $param);
  }
  
  public function distance($param=array()){
    return $this->request("GET","/tmap/routes/distance?version=1&format=json&callback=result", $param);
  }   
  
  private function request($method, $uri, $param=array(), $headers=null) {
    $curl = curl_init();
    $url="https://apis.openapi.sk.com".$uri;
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    
    switch ($method) {
      case "POST":                
      case "PUT":
      case "DELETE":
      if ($param) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));
        break;
      default: // GET
      if ($param) $url = sprintf("%s?%s", $url, http_build_query($param));
    }
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
    $http_headers = array("Content-Type: application/json","appkey: ".static::$APP_KEY);
    if (is_array($headers)) $http_headers = array_merge($http_headers, $headers);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $http_headers);
        
    $ret = curl_exec($curl);
    if (curl_error($curl)) {
      return FALSE;
    }
    curl_close($curl);
    //echo $ret;
    $obj=json_decode($ret);
        
    return $obj;
  }
}

