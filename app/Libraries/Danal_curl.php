<?php
namespace App\Libraries;

class Danal_curl{
    /******************************************************
     * 다날 결제서버 URL
     ******************************************************/
    private $coupon_url="https://tx-coupon.danalpay.com/culture/culture";
    
    private $credit_url = "https://tx-creditcard.danalpay.com/credit/";
    
    /******************************************************
     * Set Timeout
     ******************************************************/
    private $connect_timeout=3;//sec
    private $timeout=40; //sec
    
    /******************************************************
     * Danal Server Timeout
     ******************************************************/
    private $rtime                       = "10";	// (sec)
    
    /******************************************************
     * curl path
     ******************************************************/
    private $curl_path                   = "/usr/bin/curl -k";
    //$CURL_PATH                 = "D:\\curl\\curl -k ";
    
    /******************************************************
     * CPID, CPPWD : 다날에서 제공해 드린 CPID, CPPWD
     ******************************************************/
    private $cpid			= DANAL_CP_ID;
    private $cppwd			= DANAL_CP_PWD;
    private $card_cpid      = DANAL_CP_ID;
    
    
    private $CRYPTOKEY = "b3711f94ec9a6126d4ccaf251302136ad1c96b8f3400d58f4b7c0224f5c26e53";// 암호화KEy. 실서비스를 위해서는 반드시 교체필요.
    private $IVKEY = "d7d02c92cb930b661f107cb92690fc83"; // IV 고정값.
    
    public function __construct(){
        if( isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"])) {
            $this->curl_path="D:\\curl\\bin\\curl -k";
        } else {
        $this->curl_path= "/usr/bin/curl ";
        }
    }
    
    
    
    public function call( $url, $REQ_DATA, $Debug=false ) {
        
        
       
        $REQ_DATA['RTIME']			= $this->rtime;
        //$REQ_DATA['SERVICETYPE']	= $this->servicetype;
        
        $REQ_CMD = $this->curl_path;
        $REQ_STR = $this->data2str($REQ_DATA);
        
        $REQ_CMD = $REQ_CMD . ' --connect-timeout ' . $this->connect_timeout . ' --max-time ' . $this->timeout;
        $REQ_CMD = $REQ_CMD . ' --data "' . $REQ_STR . '"';
        $REQ_CMD = $REQ_CMD . ' ' . $url;
        
        exec($REQ_CMD, $RES_STR, $CURL_VAL);
        
        if($CURL_VAL != 0)
            $RES_STR = "RETURNCODE=-1&RETURNMSG=NetWork Error( " . $CURL_VAL . " )";
            
            if($Debug){
                echo "REQ[" . $REQ_CMD . "]<BR>";
                echo "RET[" . $CURL_VAL . "]<BR>";
                echo "RES[" . urldecode($RES_STR[0]) . "]<BR>";
                exit();
            }
            
            return $this->str2data($RES_STR);
    }
    
    
    
    public function str2data($str) {
        $in = "";
        
        if(is_array($str)){
            for($i=0; $i<count($str);$i++)
                $in .= $str[$i];
        }else{
            $in = $str;
        }
        $pairs = explode("&", $in);
        
        foreach ($pairs as $line) {
            $parsed = explode("=", $line);
            
            if (count($parsed) == 2){
                $data[$parsed[0]] = iconv("euc-kr","utf-8",urldecode($parsed[1]));
                
                if($parsed[0]=="RESULT"){
                    $data["RETURNCODE"]=$data[$parsed[0]]="0" ? "0000":$data[$parsed[0]];
                }else if($parsed[0]=="REASONTEXT"){
                    $data["RETURNMSG"]=$data[$parsed[0]];
                }else if($parsed[0]=="Result"){
                    $data["RETURNCODE"]=$data[$parsed[0]]="0" ? "0000":$data[$parsed[0]];
                }else if($parsed[0]=="ResultMsg"){
                    $data["RETURNMSG"]=$data[$parsed[0]];
                }
            }
        }
        
        return $data;
    }
    
    public function data2str($data) {
        $pairs = array();
        foreach ($data as $key => $value)
            array_push($pairs, $key . '=' . urlencode($value));
            return implode('&', $pairs);
    }
    
    
    
    
    
    /******************************************************
     * 다날 서버와 통신함수 CallTrans
     *    - 다날 서버와 통신하는 함수입니다.
     *    - Debug가 true일경우 웹브라우져에 debugging 메시지를 출력합니다.
     ******************************************************/
    public function CallCredit( $REQ_DATA, $Debug ){        
        
        $REQ_STR = $this->toEncrypt( $this->data2str($REQ_DATA) );
        $REQ_STR = urlencode( $REQ_STR );
        $REQ_STR = "CPID=".$this->card_cpid."&DATA=".$REQ_STR;
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_POST,1 );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,0 );
        curl_setopt( $ch,CURLOPT_CONNECTTIMEOUT,5000 );
        curl_setopt( $ch,CURLOPT_TIMEOUT,3000 );
        curl_setopt( $ch,CURLOPT_URL,$this->credit_url );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, array("Content-type:application/x-www-form-urlencoded; charset=euc-kr"));
        curl_setopt( $ch,CURLOPT_POSTFIELDS,$REQ_STR );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER,1 );
        curl_setopt( $ch,CURLINFO_HEADER_OUT,1 );
        //curl_setopt( $ch,CURLOPT_SSLVERSION, 'all' ); //ssl 관련 오류가 발생할 경우 주석을 해제하고 6( TLSv1.2) 또는 1(TLSv1)로 설정
        
        $RES_STR = curl_exec($ch);
        
        if( ($CURL_VAL=curl_errno($ch)) != 0 )
        {
            $RES_STR = "RETURNCODE=-1&RETURNMSG=Network Error(" . $CURL_VAL . ":" . curl_error($ch) . ")";
        }
        
        if( $Debug )
        {
            $CURL_MSG = "";
            if( function_exists("curl_strerror") ){
                $CURL_MSG = curl_strerror($CURL_VAL);
            }
            else if( function_exists("curl_error") ){
                $CURL_MSG = curl_error($ch);
            }
            
            echo "REQ[" . $REQ_STR . "]<BR>";
            echo "RET[" . $CURL_VAL . ":" . $CURL_MSG . "]<BR>";
            echo "RES[" . urldecode($RES_STR) . "]<BR>";
            echo "<BR>" . print_r(curl_getinfo($ch));
            //exit();
        }
        
        curl_close($ch);
        
        $RES_DATA = $this->str2data( $RES_STR );
        if( isset($RES_DATA["DATA"]) ){
            $RES_DATA = $this->str2data( $this->toDecrypt( $RES_DATA["DATA"] ) );
        }
        
        return $RES_DATA;
    }
    
    /*******************************************************
     * curl_init() 사용이 불가능할 때, 바이너리를 컴파일하여 실행
     *******************************************************/
    public function CallCreditExec( $REQ_DATA, $Debug ){
        
        $REQ_STR = $this->toEncrypt( $this->data2str($REQ_DATA) );
        $REQ_STR = urlencode( $REQ_STR );
        $REQ_STR = "CPID=".$this->card_cpid."&DATA=".$REQ_STR;
        
        $REQ_CMD = $this->curl_path;
        $REQ_CMD = $REQ_CMD . ' -k --connect-timeout ' . 5000;
        $REQ_CMD = $REQ_CMD . ' --max-time ' . 3000;
        $REQ_CMD = $REQ_CMD . ' --data ' . "\"" . $REQ_STR . "\"";
        $REQ_CMD = $REQ_CMD . ' '. "\"" . $this->credit_url . "\"";
        
        exec($REQ_CMD, $RES_STR, $CURL_VAL);
        
        if($Debug){
            echo "Request : " . $REQ_CMD . "<BR>\n";
            echo "Ret : " . $CURL_VAL . "<BR>\n";
            echo "Out : " . $RES_STR[0] . "<BR>\n";
        }
        
        $RES_DATA = null;
        if($CURL_VAL != 0){
            $RES_STR = "RETURNCODE=-1&RETURNMSG=Network Error( " . $CURL_VAL . " )";
            $RES_DATA = str2data( $RES_STR );
        }
        else{
            $RES_DATA = str2data( $RES_STR );
            $RES_DATA = str2data( $this->toDecrypt( $RES_DATA["DATA"] ) );
        }
        
        return $RES_DATA;
    }
    
    public function toEncrypt($plaintext){
        
        
        $iv = $this->convertHexToBin($this->IVKEY);
        $key = $this->convertHexToBin($this->CRYPTOKEY);
        $ciphertext = openssl_encrypt($plaintext, "aes-256-cbc", $key, true, $iv);
        $ciphertext = base64_encode($ciphertext);
        
        return $ciphertext;
    }
    
    public function toDecrypt($ciphertext){
        
        
        $iv = $this->convertHexToBin($this->IVKEY);
        $key = $this->convertHexToBin($this->CRYPTOKEY);
        $ciphertext = base64_decode($ciphertext);
        $plaintext = openssl_decrypt($ciphertext, "aes-256-cbc", $key, true, $iv);
        
        return $plaintext;
    }
    
    public function convertHexToBin( $str ) {
        if( function_exists( 'hex2bin' ) ){
            return hex2bin( $str );
        }
        
        $sbin = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; $i += 2 ) {
            $sbin .= pack( "H*", substr( $str, $i, 2 ) );
        }
        
        return $sbin;
    }
}