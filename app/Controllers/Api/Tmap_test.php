<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
// use App\Models\Member_couponModel;
// use App\Models\MemberModel;
// use App\Models\App_push_messagesModel;
//use App\Libraries\ValidChecker;
use App\Libraries\Tmap;

class Tmap_test extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  }
  
  
	public function distance() {
    $Tmap     = new Tmap();
    $param  = array(); 
    $param['appKey']     = "l7xx9f4e3034dbb04ca39269f054f2923070"; //필수 삭제 금지
    $param['startX']    = "126.97837";
    $param['startY']    = "37.57678";
    $param['endX']      = "126.986072"; 
    $param['endY']      = "37.570028"; 
    $param['reqCoordType']  = "WGS84GEO"; 

    $returnData = $Tmap->distance($param);
    print_r($returnData);
    print_r("<br>");
    print_r($returnData->distanceInfo->distance);
    print_r(" meter <br>");

    // 전체 리턴 값
    //stdClass Object ( [distanceInfo] => stdClass Object ( [distance] => 1012 ) )

	}

	public function geo_to_add() {
    $Tmap     = new Tmap();
    $param  = array(); //notification 데이터
   // $param['appKey']     = "l7xx9f4e3034dbb04ca39269f054f2923070";
    $param['coordType']  = "WGS84GEO";
    $param['addressType']   = "A10";
    $param['lon']     = "126.986072"; 
    $param['lat']     = "37.570028"; 

    //private function request($method, $uri, $param=array(), $headers=null) {

    $returnData = $Tmap->geotoadd($param);
    print_r($returnData);
    print_r("<br>");
    print_r($returnData->addressInfo->fullAddress);
    print_r("<br>");
    print_r($returnData->addressInfo->city_do);
    print_r("<br>");
    print_r($returnData->addressInfo->gu_gun);
    print_r("<br>");
    print_r($returnData->addressInfo->legalDong);
    print_r("<br>");
    print_r($returnData->addressInfo->bunji);
    print_r("<br>");

    // 전체 리턴 값
    //stdClass Object ( [addressInfo] => stdClass Object ( [fullAddress] => 서울특별시 종로구 종로1.2.3.4가동,서울특별시 종로구 종로2가 84-11,서울특별시 종로구 종로 76-2 가로판매대 [addressType] => A10 [city_do] => 서울특별시 [gu_gun] => 종로구 [eup_myun] => [adminDong] => 종로1.2.3.4가동 [adminDongCode] => 1111061500 [legalDong] => 종로2가 [legalDongCode] => 1111013800 [ri] => [bunji] => 84-11 [roadName] => 종로 [buildingIndex] => 76-2 [buildingName] => 가로판매대 [mappingDistance] => 16.1 [roadCode] => 111103100013 ) )
	}













}