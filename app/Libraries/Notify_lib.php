<?php
class Notify_lib{
    
    
   
    private $web_sock_ip;
    private $port;
    
    public function __construct(){
        if( isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"])) {
            $this->web_sock_ip="127.0.0.1";
            $this->port=3579;
        }else{
            $this->web_sock_ip="chat_server";
            $this->port=3579;
        }
    }
    
    
    public function send_msg($room_no, $cmd, $array_msg){
        $body=array("cmd"=>$cmd,"room_no"=>"$room_no","msg"=>json_encode($array_msg,JSON_UNESCAPED_UNICODE));
        $this->proc_socket($body);
    }
    
    
    public function get_connect_count(){
        return $this->proc_socket("conn_count",false);
    }
    
    
    public function proc_socket( $cmd,$type_json=true){
        //$server_list=array("220.90.209.121");
        //return "";
        
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // TCP 통신용 소켓 생성 //
        
        
        if ($socket === false) {
            return "";
        }
        
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 5, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 0));
        
        
        $result = @socket_connect($socket, $this->web_sock_ip, $this->port); // 소켓 연결 및 $result에 접속값 지정 //
        if ($result === false) {
            return "";
        }
        $packet = $type_json ? json_encode($cmd) : $cmd;
        @socket_write($socket, pack("N",strlen($packet)));
        @socket_write($socket, $packet);
        
        $line = @socket_read($socket, 2048) ; // 소켓으로 부터 받은 REQUEST 정보를 $input에 지정 //
               
        
        socket_close($socket);
        
        return $line;
        
        /*
        $fsock  = @fsockopen($host ,$port);
        if($fsock){
            $packet = json_encode($cmd); //보내고자 하는 전문 //
            fwrite($fsock, $packet);
            $line = fread($fsock, 4096);
            fclose($fsock);
            return $line;
        }
        return "";*/
    }
}