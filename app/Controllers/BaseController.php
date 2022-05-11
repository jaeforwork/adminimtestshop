<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\No_access_ipModel;
use App\Models\Visit_Model;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    // protected $helpers = [];

    protected $helpers = ["common_helper"];
    protected $data=array();
    protected $at=null; //access token
    protected $accessData=null;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        // $accessToken=static::getAccessToken($this->request);
        
        // if($accessToken){
        //     if(!isset($accessToken->idx) || !isset($accessToken->name) || !isset($accessToken->image) || !isset($accessToken->end)){
        //         return;
        //     }
        //     if($accessToken->end<date("YmdHis")){// 토큰 만료
                
        //         $accessToken->expired=true;
        //     }else{
        //         $accessToken->expired=false;
        //     }
            
        //     $this->accessData=$accessToken;
        // }


  //설정파일 호출 
  $config = config('App'); 

 // global $member;

        
    header("Content-Type: text/html; charset=".$config->charset);
    header("Expires: 0"); // rfc2616 - Section 14.21
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
    header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0

    $agent = $this->request->getUserAgent();
    
    if ($agent->isReferral()) {
      $referrer=$agent->referrer();
    } else {
      $referrer='Unidentified User referrer';
    }

    if ($agent->getAgentString())  {
      $agent_string=$agent->getAgentString(); // Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2
    } else {
      $agent_string='Unidentified User agent_string';
    }

    $vi_device = 'PC';


    if ($agent->isBrowser())  {
      $currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
    } elseif ($agent->isRobot()) {
      $currentAgent = $agent->agent->getRobot();
      $vi_device = 'Robot';
    } elseif ($agent->isMobile()) {
      $currentAgent = $agent->getMobile();
      $vi_device = 'Mobile';
    } else {
      $currentAgent = 'Unidentified User Agent';
    }

    //접속을 막는 IP는 차단한다.
    $No_access_ip_Std = new No_access_ipModel();
    $No_access_ip_Result = $No_access_ip_Std->where(['IP'=> $_SERVER['REMOTE_ADDR'],'STATUS'=> 'Y'])->countAllResults(); //숫자로 나옴
    if($No_access_ip_Result>0){
     exit; 
    }
    //사무실 IP에서만 보이도록
    if($_SERVER['REMOTE_ADDR']=='14.36.46.90' || $_SERVER['REMOTE_ADDR']=='121.65.132.178') {
      echo "--> 사무실 IP에서만 보임 (디버깅)<br>";
      echo "접속을 막는 IP result <br>";
      print_r($No_access_ip_Result);
      echo "<br>";
      echo $currentAgent;
      echo "<br>";
      echo $agent->getPlatform(); // Platform info (Windows, Linux, Mac, etc.)
      echo "<br>";
      echo "사무실 IP에서만 보임 (디버깅) <--<br>";    
      echo "<br>";
    } 
    
  $Visit_NewData = array(
    'vi_ip'       => $_SERVER['REMOTE_ADDR'],
    'vi_date'     => TIME_YMD,
    'vi_time'     => TIME_HIS,
    'vi_referer'  => $referrer,
    'vi_agent'    => $currentAgent,
    'vi_agent_string' => $agent_string,
    'vi_browser'  => $agent->isBrowser(),
    'vi_os'       => $agent->getPlatform(),
    'vi_device'   => $vi_device
  );

  // insert
  $Visit_Std = new Visit_Model();
  $Visit_Std->insert($Visit_NewData);
  $Visit_result=$Visit_Std->transCommit();

  //회원 세션을 검사한다.
  $is_member = $is_super = $is_opsuper = FALSE;

  //불필요한 세션을 지운다. 16-01-22
  $DeleteTime=strtotime("-3 hours");
  //$session->get('item');
    

  $session = \Config\Services::session();
  $session->set('member', 'some_value');

  $login_id = $session->get('ss_mb_id');
  //echo $config->baseURL;

  if ($login_id) {

  //$std = new DriverInfoModel();
    //$data['students'] = $std->findAll();
    // $data['students'] = $std->paginate(2);
   // $result = $std->countAllResults();
   // $builder = $std->builder();
   // $builder->where('s_subject', 33);

    // $data = [
    //  'students' => $std->orderBy('S_IDX','DESC')->paginate(5),     
    //   'pager' => $std->pager,   
    //   'tcount' => $std->countAllResults(),   
    // ];








    $member = $CI->Basic_model->get_member($login_id);

    if (substr($member['mb_today_login'], 0, 10) != TIME_YMD) {
      $CI->load->model('Point_model');
      $CI->Point_model->insert($member['mb_id'], $CI->config->item('cf_login_point'), TIME_YMD.' 첫로그인', '@login', $member['mb_id'], TIME_YMD);

      $CI->db->where('mb_id', $member['mb_id']);
      $CI->db->update('ki_member', array(
        'mb_today_login' => TIME_YMDHIS,
        'mb_login_ip'	 => $CI->input->server('REMOTE_ADDR')
      ));
    }

    if ($member['mb_id']) {
      $is_member = TRUE;
				
      if ($member['mb_level'] == 10) // 시스템관리자 조건
        $is_super = $member['mb_id'];
				
      if ($member['mb_level'] >= 8 && $member['mb_level'] < 10) // 관리자 조건
        $is_opsuper = $member['mb_id'];

      if ($member['mb_level'] >= 5) // 전문가 조건
        $IsPro = $member['mb_id'];	
					
      if (!$config->cf_use_nick)
        $member['mb_nick'] = $member['mb_name'];
			}
		}
		else {
		//	$member['mb_level'] = 1;

      
    }   
 
  }

}
