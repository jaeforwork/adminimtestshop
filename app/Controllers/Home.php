<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;


class Home extends BaseController
{
    public function index()
    {
        
        //$this->layout("default","document/form.php");
        
        $data=array("num"=>11111111,"name"=>"김개똥","img"=>"https://www.kkkfdafdsfds.com/fdsafsd/fdsa/fdsafdsfdsafdsafdsfsdfd.png","end"=>"20220112095800");
        $json_data=json_encode($data,JSON_UNESCAPED_UNICODE);
  // echo encrypt(1)."<br>";

        // echo encrypt256($json_data,"11111111222222223333333344444444","1111111122222222");
      //  return;
        
        // echo encrypt(1)."<br>";
        // echo encrypt(2)."<br>";
        
        
        /*
        $memberModel=getModel('MemberModel');
        $memberModel->db->abcd="1234";
        $db=db_connect("master",false);
        $certModel=getModel('CertModel',false,$db);
        echo $certModel->db->abcd;*/
        //echo encrypt("5_".(time()+300));
        
        //$this->layout("account","document/writeform.php");
        
        
        
        
        // $mem_idx=$this->request->getGet("mem_idx");
        // $memberModel=getModel('MemberModel');
        // //return csrf_token()." : ".csrf_hash();
        
        
        // $member=$memberModel->getData("*", array("mem_idx"=>$mem_idx));
        // $member->unum=encrypt($mem_idx);
        // $this->accessToken->createAccessToken($member);
        // set_cookie(COOKIE_REFRESH_TOKEN,$member->refresh_token, TOKEN_TIMEOUT,COOKIE_DOMAIN_NAME,"/","",TRUE,TRUE);
        
                
        // //$member=$memberModel->find(1);
        // print_r($member);
        
        
        
        // $this->layout("account","document/form.php");
        
        
        // return "";
        // $data=new stdClass();
        // $data->di_hash=md5(date("YmdHis"));
        // $data->nick_name="김개똥".rand(1000,9999);
        // $data->birth=rand(1970,2002).date("md", time()-rand(0,365)*86400);
        // $data->sex=rand(0,1)==1 ? "M":"F";
        // $data->join_type=rand(1,2);
        // $data->sido="서울";
        // $data->gu_gun="구로";
        // $data->join_ip="127.0.0.1";
        // $data->comment=DEFAULT_COMMENT;
        // $data->regdate=$data->updatedate=date("YmdHis");
        // $insertId=$memberModel->insertSimple("member", $data);
        
        // return $insertId;
        // //return view('welcome_message');
    }
    
    public function tutorial(){
        $this->layout("default","home/tutorial.php");
        return;
    }
}
