<?php
class Access_token{
    private $CI=null;
    private $debug=FALSE;
    public function __construct(){
        $this->CI=&get_instance();
        $this->CI->load->library("jwt");
        $this->CI->load->library("encryption");
    }
    
    public function set_debug($d){
        $this->debug=$d;
    }
    
    /**
     *access 권한이 있는지 확인(로그인)
     */
    public function verify_access_token(){
        try{
            
            $access_token=get_cookie(COOKIE_ACCESS_TOKEN);
            if($this->debug){
                echo $access_token."\n";
            }
            
            if(empty($access_token)){
                if($this->debug){
                    echo "access token empty\n";
                }
                delete_cookie(COOKIE_REFRESH_TOKEN);
                return FALSE;
            }
            $payload=Jwt::decode($access_token, TOKEN_KEY);
            
            
            
            if($this->debug){
                print_r($payload);
                echo date("Ymd");echo "<br>". date("Y-m-d H:i:s",$payload->exp);
            }
            
            $ctime=time();
            if($payload->exp<$ctime){
                $payload->verify=false;
            }else{
                $payload->verify=true;                
            }
            
            return $payload;
            
        }catch(Exception $e2){
            
        }
        return false;
    }
    
    public function verify_refresh_token($payload_access){
        
        $refresh_token=get_cookie(COOKIE_REFRESH_TOKEN);
        $access_token=get_cookie(COOKIE_ACCESS_TOKEN);
        
        if(empty($refresh_token) ||empty($access_token)){
            if($this->debug){
                echo "refresh_token empty\n";
            }
            delete_cookie(COOKIE_ACCESS_TOKEN);
            delete_cookie(COOKIE_REFRESH_TOKEN);
            return NULL;
        }
        
        if($this->debug){
            echo "refresh_token => $refresh_token\n";
        }
        
        
        
        $DB_M=$this->CI->load->database(DB_NAME_MASTER,TRUE);
        $this->CI->load->model("my_model");
        
        $param=new stdClass();
        $param->refresh_token=$refresh_token;
        $param->mem_idx=decrypt($payload_access->unum);
        
        $member=$this->CI->my_model->get_simple($DB_M, "member", "mem_idx, nick_name, status, profile_image, dia,adult as adt",$param);
        if(!$member || $member->status!="Y"){
            return NULL;
        }
        
        /*
        $count=$this->CI->my_model->get_simple($DB_M, "member_admin", "count(*) as cnt",array("mem_idx"=>$member->mem_idx))->cnt;
        if($count>0){//관리자는 성인
            $member->adult="Y";
        }
        */
        
        $temp=explode("_",$refresh_token);
        $time=time();
        if($time > $temp[1]){//refresh 토큰 만료 시간이 지났는지 체크
            $refresh_token=encrypt($member->mem_idx."_".$time."_".rand(100000,999999))."_".($time+ RFTOKEN_EXPIRE);
            $this->CI->my_model->_update($DB_M, "member", array("refresh_token"=>$refresh_token), array("mem_idx"=>$member->mem_idx));
            set_cookie(COOKIE_REFRESH_TOKEN,$refresh_token,($this->CI->mobile ? TOKEN_TIMEOUT_MOBILE : TOKEN_TIMEOUT),COOKIE_DOMAIN_NAME,"/","",NULL,TRUE);
        }
        
        $this->loginlog($DB_M, $member->mem_idx);
        $member->unum=encrypt($member->mem_idx);
        $DB_M->close();
        if($this->debug){
            print_r($member);
        }
        
        
        
        return $member;
    }
    
    public function create_access_token($payload_data, $exp=0){
        if(!$payload_data->profile_image){
            $payload_data->profile_image="/assets/images/icon_no_image.png";
        }
        if(!$exp)$exp=time()+TOKEN_EXPIRE;
        $payload_data->exp=$exp;
        
        //$payload=array("unum"=>$payload_data->unum,"nic"=>$payload_data->nick_name, "img"=>$payload_data->profile_image, "dia"=>$payload_data->dia,"exp"=>$exp,"ld"=>ld);
                
        $payload=new stdClass();
        $payload->unum=$payload_data->unum;
        $payload->nic=$payload_data->nick_name;
        $payload->img=$payload_data->profile_image;
        $payload->dia=$payload_data->dia;
        $payload->adt=isset($payload_data->adult) ? $payload_data->adult : (isset($payload_data->adt)? $payload_data->adt:"N");
        $payload->exp=$exp;
        
        
        
        $jwtstr= Jwt::encode($payload,TOKEN_KEY);
        set_cookie(COOKIE_ACCESS_TOKEN,$jwtstr,($this->CI->mobile ? TOKEN_TIMEOUT_MOBILE : TOKEN_TIMEOUT),COOKIE_DOMAIN_NAME,"/","",NULL,TRUE);
        
        return $payload;
        
    }
    
    private function loginlog($DB, $mem_idx){
        
        $this->CI->load->model("member_model");
        $loglist=$this->CI->member_model->get_simple_list($DB, "member_login_log", "regdate",array("mem_idx"=>$mem_idx),array("sort_field"=>"idx","sort"=>"desc","limit"=>1));
        $do_loginlog=FALSE;
        if(empty($loglist)){
            $do_loginlog=TRUE;
        }else{
            foreach($loglist as $log){
                if(date("Ymd",strtotime($log->regdate)) < date("Ymd")){
                    $do_loginlog=TRUE;
                }
                break;
            }
        }
        if($do_loginlog){
            $param=new stdClass();
            $param->mem_idx=$mem_idx;
            
            
            if($this->CI->mobile){
                switch($this->CI->app){
                    case "android":
                        $param->type="A";
                        break;
                    case "ios":
                        $param->type="I";
                        break;
                    default:
                        $param->type="M";
                        break;
                }
            }else{
                $param->type="P";
            }
            
            $param->ip=$this->CI->input->ip_address();
            $param->regdate=date("YmdHis");
            $param->change_day="Y";
            
            $this->CI->member_model->login_log($DB,$param);
        }
        
        
    }
}