<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

use App\Models\DriverModel;
use App\Models\CctvModel;
use App\Models\TransportModel;

class Cctv extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();       
  }

  public function source_make() {    //새로 구매하면 반드시 해야 함
    $post['company'] = "petgle"; 
    $post['source'] = "rtsp://petgle2:eoqkrpetgle@223.171.128.40:554/stream2";  
    //$post['source'] = "rtsp://<petgle2:eoqkrpetgle@223.171.128.40:554 변수로-->/stream2"; 
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
    $ret = curl_exec($oCurl);
    curl_close($oCurl);

    $retArr = json_decode($ret, true); // 결과배열
   //print_r($retArr);
    //exit;
    if($retArr['success']!=''){ 
      if (!empty($playback)) {
          $message='';
          echo(json_encode(array("result" => 'fail', "msg" => $message))); 
          exit;
        } else {      
          $message='';
          echo(json_encode(array("result" => 'succ', "msg" => $message,""))); 
          exit;
        }
      }       
  }

















  public function test_start() {    
    $post['company'] = "petgle"; 
    $post['source'] = "rtsp://petgle2:eoqkrpetgle@223.171.128.40:554/stream2";  
    $post['playback'] = "11_20220410007";  
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
   //print_r($retArr);
    //exit;
    if($retArr['success']!=''){ 
      if (!empty($playback)) {
          $message='';
          echo(json_encode(array("result" => 'fail', "msg" => $message))); 
          exit;
        } else {      
          $message='';
          echo(json_encode(array("result" => 'succ', "msg" => $message,""))); 
          exit;
        }
      }       
  }

  public function test_end() {  
    $post['company'] = "petgle"; 
    $post['playback'] = "11_20220410007";  
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
  
    if($retArr['success']!=''){
      $message='';
      echo(json_encode(array("result" => 'succ', "msg" => $message))); 
      exit;
    } else {
      $message='';
      echo(json_encode(array("result" => 'fail', "msg" => $message))); 
      exit;
    }   
  }

  public function test_cctv_url() {  
    $url = "https://petgle-test-edge.bbidc-cdn.com:8443/petgle/_definst_/11_20220410007.stream/playlist.m3u8";
    $message='';
    echo(json_encode(array("result" => 'succ', "msg" => $url))); 
    exit;
  }

  
  public function start() {  
    $request = \Config\Services::request();

    $USER_ID    = esc($request->getPost('USER_ID'));
    $PASSWD     = esc($request->getPost('PASSWD'));
    $USER_IDX   = esc($request->getPost('USER_IDX'));
    $PHONE      = esc($request->getPost('PHONE'));

    $DEVICE_ID  = esc($request->getPost('DEVICE_ID'));
    $APP_TYPE   = esc($request->getPost('APP_TYPE'));      
    
    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*, member.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    //select
    $builder->select('user.*, join_info.*, transport.TR_IDX');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->join('TRANSPORT as transport', 'user.USER_IDX = transport.DRIVER_IDX', "left inner"); 
    //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
    $builder->where('user.USER_IDX', $USER_IDX);  

    //$builder->orderBy('user.USER_IDX','DESC');
    // $data['students'] = $builder->get(5,$page)->getResult('array');
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;

    $post['company'] = "petgle"; 
    $post['source'] = "rtsp://".$data['driver'][0]['CCTV_IDX']."/stream2";  
    $post['playback'] = $USER_IDX."_20220210007";  

    // $url='https://m-stream-api-test.bbidc-cdn.com/api/playback/0';     

    // $host_info = explode("/", $url);
    // $port = $host_info[0] == 'https:' ? 443 : 80;

    // $oCurl = curl_init();
    // curl_setopt($oCurl, CURLOPT_PORT, $port);
    // curl_setopt($oCurl, CURLOPT_URL, $url);
    // curl_setopt($oCurl, CURLOPT_POST, 1); // POST 전송 여부
    // curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1); 
    // curl_setopt($oCurl, CURLOPT_POSTFIELDS,  $post);                       
    // curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    // $ret = curl_exec($oCurl);
    // curl_close($oCurl);

    $retArr = json_decode($ret, true); // 결과배열

    //  print_r($retArr);
    //  exit;
    if($retArr['success']!=''){
    // $retArr['domain'] && !empty($retArr['domain'])){

      $post['source'] = "rtsp://".$data['driver'][0]['CCTV_IDX']."/stream2";  
      $post['playback'] = $USER_IDX."_20220210007";  

      $playback  = $data['driver'][0]['CCTV_IDX']."/stream2&playback=".$retArr['playback'];
      // print_r($playback);
      // echo $data['driver'][0]['TR_IDX'];
      exit;
      if (!empty($playback)) {
        $newData = ['CCTV_URL'=>$playback,];
        $std = new TransportModel();
        $std->transBegin();

        $result = $std->update($data['driver'][0]['TR_IDX'],$newData);
       
        if ($std->transStatus() === FALSE) {
          $std->transRollback();
          $message='';
          echo(json_encode(array("result" => 'fail', "msg" => $message))); 
          exit;
        } else {
          $std->transCommit();          
          $message='';
          echo(json_encode(array("result" => 'succ', "msg" => $message,""))); 
          exit;
        }
      }
    }  else {
      $message='';
      echo(json_encode(array("result" => 'fail', "msg" => $message,""))); 
      exit;
    }         
  }

  public function end() {  
    $request = \Config\Services::request();

    $USER_ID    = esc($request->getPost('USER_ID'));
    $PASSWD     = esc($request->getPost('PASSWD'));
    $USER_IDX   = esc($request->getPost('USER_IDX'));
    $PHONE      = esc($request->getPost('PHONE'));

    $DEVICE_ID  = esc($request->getPost('DEVICE_ID'));
    $APP_TYPE   = esc($request->getPost('APP_TYPE'));
         
    //$USER_ID = 'driver1';
    //$USER_IDX = 11;

    //count
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*, member.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();

    //select
    $builder->select('user.*, join_info.*, transport.TR_IDX, transport.CCTV_URL');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
    $builder->join('TRANSPORT as transport', 'user.USER_IDX = transport.DRIVER_IDX', "left inner"); 
    $builder->where('user.USER_IDX', $USER_IDX);  

    //$builder->orderBy('user.USER_IDX','DESC');
    // $data['students'] = $builder->get(5,$page)->getResult('array');
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;
    
    // /*****/
    // $post['company'] = "petgle"; 
    // $post['playback'] = $data['driver'][0]['CCTV_URL'];
    // $url='https://m-stream-api-test.bbidc-cdn.com/api/playback/0';

    // $host_info = explode("/", $url);
    // $port = $host_info[0] == 'https:' ? 443 : 80;
    // $post = http_build_query($post, '', '&');

    // $oCurl = curl_init();
    // curl_setopt($oCurl, CURLOPT_PORT, $port);
    // curl_setopt($oCurl, CURLOPT_URL, $url);
    // curl_setopt($oCurl, CURLOPT_POST, 0);
    // curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
    // curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1); 
    // curl_setopt($oCurl, CURLOPT_POSTFIELDS,  $post);                       
    // curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    // $ret = curl_exec($oCurl);
    // curl_close($oCurl);

    $retArr = json_decode($ret, true); // 결과배열
    // print_r($retArr);
    // exit;

    if($retArr['success']!=''){
      $message='';
      echo(json_encode(array("result" => 'succ', "msg" => $message,""))); 
      exit;
    } else {
      $message='';
      echo(json_encode(array("result" => 'fail', "msg" => $message))); 
      exit;
    }   

  }

  public function cctv_url() {  
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
   
    $TR_IDX    = esc($request->getPost('tr_idx'));

    if($TR_IDX==0 || !$TR_IDX) {
      ajaxReturn(RESULT_FAIL,'',"");
      return;
    }

    //count
    $builder = $this->db->table("MEMBER as member");
    $builder->select('member.*, transport.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'member.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->join('TRANSPORT as transport', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('member.USER_IDX', $USER_IDX); 
    $builder->where('transport.TR_IDX', $TR_IDX); 
    $total = $builder->countAllResults();

    //select
    $builder->select('member.*, transport.*');
    $builder->join('DRIVER_JOIN_INFO as join_info', 'member.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->join('TRANSPORT as transport', 'member.USER_IDX = transport.USER_IDX', "left inner"); // added left here
    $builder->where('member.USER_IDX', $USER_IDX); 
    $builder->where('transport.TR_IDX', $TR_IDX); 

    //$builder->orderBy('member.USER_IDX','DESC');
    // $data['students'] = $builder->get(5,$page)->getResult('array');
    $data['driver'] = $builder->get()->getResult('array');   
    $data['tcount']= $total;  

    if($total==0){
      ajaxReturn(RESULT_FAIL,'','');
      return;
    }
    //$url = "https://petgle-test-edge.bbidc-cdn.com:8443/api/stream/0/pull?company=petgle&source=rtsp://petgle1:eoqkrpetgle@223.171.128.79:554/stream2&playback=11_20220210007.m3u8";
   
    $url = "https://petgle-test-edge.bbidc-cdn.com:8443/api/stream/0/pull?company=petgle&source=rtsp://".$data['driver'][0]['CCTV_URL'].".m3u8";

    $message='';
    echo(json_encode(array("result" => 'succ', "msg" => $url))); 
    exit;
  }
  
  public function record_url() {  
    $request = \Config\Services::request();
    $uri = $request->getUri();  
    $getPath = explode('/', $uri->getPath());
    $PLAYBACK=$getPath[3];
    
    $hls    = esc($request->getPost('hls'));
       
    $hlsArr = json_decode($hls, true); // 결과배열

    $URL = $hlsArr['hls'];
    // $KEY = 'driver1';
    //$URL = 'https://petgle-test-edge.bbidc-cdn.com:8443/eoqkrpetgle@223.171.128.79:554';

    //count
    $builder = $this->db->table("CCTV_RECORD_URL as cctv");
    $builder->select('cctv.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('cctv.URL', $URL);  
    $total = $builder->countAllResults();

    if($total==0 && !empty($URL)) {
      $newData = ['URL'=>$URL,'PLAYBACK'=>$PLAYBACK,];
 
      $std = new CctvModel();
      $std->transBegin();
      $std->insert($newData);

      if ($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='이미 같은 값이 있거나 DB입력 오류';
        echo(json_encode(array("result" => 'fail', "msg" => $message))); 
        exit;
      } else {
        $std->transCommit();
        $message='Y';
        echo(json_encode(array("result" => 'succ', "msg" => $message))); 
        exit;
      }

    } else {
      $message='';
      echo(json_encode(array("result" => 'fail', "msg" => $message))); 
      exit;
    }
 }










}//class