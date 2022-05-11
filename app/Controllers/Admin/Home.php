<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;

//use App\Models\DriverModel;
//use App\Models\MemberModel;
//use App\Models\TransportModel;
//use App\Libraries\ValidChecker;
//use App\Libraries\Sms;

class Home extends BaseController {
  public function index() {

  if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='222.112.186.71' || $_SERVER['REMOTE_ADDR']=='112.171.25.51' || $_SERVER['REMOTE_ADDR']=='175.117.79.16' || $_SERVER['REMOTE_ADDR']=='183.101.208.22'|| $_SERVER['REMOTE_ADDR']=='112.151.86.82' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
  } else {
    exit;
  }
  // if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='222.112.186.71' || $_SERVER['REMOTE_ADDR']=='112.171.25.51' || $_SERVER['REMOTE_ADDR']=='175.117.79.16' || $_SERVER['REMOTE_ADDR']=='183.101.208.22'|| $_SERVER['REMOTE_ADDR']=='112.151.86.82' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
  //   exit;
  // } else {
  //   exit;
  // }



    $data['title'] = ucfirst('welcome'); // Capitalize the first letter

    echo view('templates/header', $data);
    echo view('index');
    echo view('templates/footer', $data);
  }
}
