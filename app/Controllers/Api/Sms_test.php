<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
//use App\Models\MemoModel;
//use App\Libraries\ValidChecker;
use App\Libraries\Sms;

class Sms_test extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }

	public function send() {
    exit;
    $to ="010-5896-5938";

    $sms = new Sms();
    $dataMessage = array(); //데이터

    $dataMessage['title']     = "SMS title";
    $dataMessage['message']   = "SMS message";
    $dataMessage['msg_type']  = "SMS";

    $result=$sms->send_sms($to, $dataMessage);

    print_r($result);
    //$pushNoti->send($memberInfo->push_token, $dataMessage);


	}







}