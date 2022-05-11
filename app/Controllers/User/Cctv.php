<?php
namespace App\Controllers\User;

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

  public function cctv_url() {  
    $request = \Config\Services::request();

    $USER_ID    = esc($request->getPost('USER_ID'));
    $PASSWD     = esc($request->getPost('PASSWD'));
    $USER_IDX   = esc($request->getPost('USER_IDX'));
    $PHONE      = esc($request->getPost('PHONE'));

    $DEVICE_ID  = esc($request->getPost('DEVICE_ID'));
    $APP_TYPE   = esc($request->getPost('APP_TYPE'));    
    $TR_IDX     = esc($request->getPost('tr_idx'));    
    
    //count
    $builder = $this->db->table("TRANSPORT as transport");
    $builder->select('transport.TR_IDX, transport.USER_IDX, transport.CCTV_URL');
   // $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where('transport.TR_IDX', $TR_IDX);  
    $total = $builder->countAllResults();

    //select
    $builder->select('transport.TR_IDX, transport.USER_IDX, transport.CCTV_URL');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    //  $builder->join('MEMBER as member', 'user.S_IDX = member.IDX', "left"); // added left here
    //$builder->join('TRANSPORT as transport', 'user.USER_IDX = transport.USER_IDX', "left inner"); 
    $builder->where('transport.TR_IDX', $TR_IDX);  

    //$builder->orderBy('user.USER_IDX','DESC');
    // $data['students'] = $builder->get(5,$page)->getResult('array');
    $data['transport'] = $builder->get()->getResult('array');  
   // print_r($data); 
    $data['tcount']= $total;  
    $url = "https://petgle-test-edge.bbidc-cdn.com:8443/".$data['transport'][0]['CCTV_URL']."/playlist.m3u8";
  
    echo(json_encode(array("result" => 'succ', "msg" => $url))); 
    exit;
  }


  









}//class