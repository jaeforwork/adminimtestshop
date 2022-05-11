<?php
	/*****************************************************
	 '* �ٳ� ������� ����Ű �߱� ��û
	*****************************************************/
	
	/*****************************************************
	 * ������ �ʿ��� Function �� ������ ����
	 *
	 * ������ ���� ���ǻ��� �����ø� ������������� ���� �ֽʽÿ�.
	 * DANAL Commerce Division Technique supporting Team
	 * EMail : credit_tech@danal.co.kr
	******************************************************/

	/******************************************************
	 *  DN_CREDIT_URL	: ���� ���� ����
	******************************************************/
	$DN_CREDIT_URL = "https://tx-creditcard.danalpay.com/credit/";
	
	/******************************************************
	 *  Set Timeout
	******************************************************/
	$DN_CONNECT_TIMEOUT = 5000;
	$DN_TIMEOUT = 30000; //max-time setting.
	
	$ERC_NETWORK_ERROR = "-1";
	$ERM_NETWORK = "Network Error";
	
	/******************************************************
	 * CPID		: �ٳ����� ������ �帰 CPID
	 * CRYPTOKEY	: �ٳ����� ������ �帰 �Ϻ�ȣȭ KEY(����KEY - 64���� Hashȭ ���ڿ�)
	 * IVKEY		: ������(����Ұ�)
	******************************************************/
	$CPID = "9810030929";
	$CRYPTOKEY = "20ad459ab1ad2f6e541929d50d24765abb05850094a9629041bebb726814625d";
	$IVKEY = "d7d02c92cb930b661f107cb92690fc83"; 
	
	$TEST_AMOUNT="1004";
	
	/******************************************************
	 * �ٳ� ������ ����Լ� CallTrans
	 *    - �ٳ� ������ ����ϴ� �Լ��Դϴ�.
	 *    - Debug�� true�ϰ�� ���������� debugging �޽����� ����մϴ�.
	******************************************************/
	function CallCredit( $REQ_DATA, $Debug ){		
		global $CPID;
		global $DN_CREDIT_URL, $DN_CONNECT_TIMEOUT, $DN_TIMEOUT;
		global $ERC_NETWORK_ERROR, $ERM_NETWORK;
		
		$REQ_STR = toEncrypt( data2str($REQ_DATA) );
		$REQ_STR = urlencode( $REQ_STR );
		$REQ_STR = "CPID=".$CPID."&DATA=".$REQ_STR;
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt( $ch,CURLOPT_POST,1 );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,0 );
		curl_setopt( $ch,CURLOPT_CONNECTTIMEOUT,$DN_CONNECT_TIMEOUT );
		curl_setopt( $ch,CURLOPT_TIMEOUT,$DN_TIMEOUT );
		curl_setopt( $ch,CURLOPT_URL,$DN_CREDIT_URL );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array("Content-type:application/x-www-form-urlencoded; charset=euc-kr"));
		curl_setopt( $ch,CURLOPT_POSTFIELDS,$REQ_STR );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER,1 );
		curl_setopt( $ch,CURLINFO_HEADER_OUT,1 );
		//curl_setopt( $ch,CURLOPT_SSLVERSION, 'all' ); //ssl ���� ������ �߻��� ��� �ּ��� �����ϰ� 6( TLSv1.2) �Ǵ� 1(TLSv1)�� ����
		
		$RES_STR = curl_exec($ch);
		
		if( ($CURL_VAL=curl_errno($ch)) != 0 )
		{
			$RES_STR = "RETURNCODE=".$ERC_NETWORK_ERROR."&RETURNMSG=".$ERM_NETWORK."(" . $CURL_VAL . ":" . curl_error($ch) . ")";
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
			exit();
		}
		
		curl_close($ch);
		
		$RES_DATA = str2data( $RES_STR );
		if( isset($RES_DATA["DATA"]) ){
			$RES_DATA = str2data( toDecrypt( $RES_DATA["DATA"] ) );
		}
		
		return $RES_DATA;
	}
	
	/*******************************************************
	 * curl_init() ����� �Ұ����� ��, ���̳ʸ��� �������Ͽ� ����
	 *******************************************************/
	function CallCreditExec( $REQ_DATA, $Debug ){
		
		$CP_CURL_PATH = "/usr/bin/curl ";
		
		global $CPID;
		global $DN_CREDIT_URL, $DN_CONNECT_TIMEOUT, $DN_TIMEOUT;
		global $ERC_NETWORK_ERROR, $ERM_NETWORK;
		
		$REQ_STR = toEncrypt( data2str($REQ_DATA) );
		$REQ_STR = urlencode( $REQ_STR );
		$REQ_STR = "CPID=".$CPID."&DATA=".$REQ_STR;
		
		$REQ_CMD = $CP_CURL_PATH;
		$REQ_CMD = $REQ_CMD . ' -k --connect-timeout ' . $DN_CONNECT_TIMEOUT;
		$REQ_CMD = $REQ_CMD . ' --max-time ' . $DN_TIMEOUT;
		$REQ_CMD = $REQ_CMD . ' --data ' . "\"" . $REQ_STR . "\"";
		$REQ_CMD = $REQ_CMD . ' '. "\"" . $DN_CREDIT_URL . "\"";
		
		exec($REQ_CMD, $RES_STR, $CURL_VAL);
		
		if($Debug){
			echo "Request : " . $REQ_CMD . "<BR>\n";
			echo "Ret : " . $CURL_VAL . "<BR>\n";
			echo "Out : " . $RES_STR[0] . "<BR>\n";
		}
		
		$RES_DATA = null;
		if($CURL_VAL != 0){
			$RES_STR = "RETURNCODE=" . $ERC_NETWORK_ERROR ."&RETURNMSG=" . $ERM_NETWORK ."( " . $CURL_VAL . " )";
			$RES_DATA = str2data( $RES_STR );
		}
		else{
			$RES_DATA = str2data( $RES_STR );
			$RES_DATA = str2data( toDecrypt( $RES_DATA["DATA"] ) );
		}
		
		return $RES_DATA;
	}
	
	function str2data($str){
		$data = array(); //return variable
		$in = "";
	
		if((string)$str == "Array"){
			for($i=0; $i<count($str);$i++){
				$in .= $str[$i];
			}
		}else{
			$in = $str;
		}
	
		$pairs = explode("&", $in);
	
		foreach($pairs as $line){
			$parsed = explode("=", $line, 2);
	
			if(count($parsed) == 2){
				$data[$parsed[0]] = urldecode( $parsed[1] );
			}
		}
	
		return $data;
	}
	
	function data2str($data){
	
		$pairs = array();
		foreach($data as $key => $value){
			array_push($pairs, $key . '=' . urlencode($value));
		}
	
		return implode('&', $pairs);
	}
	
	
	function toEncrypt($plaintext){
		global $CPID, $CRYPTOKEY, $IVKEY;
		
		$iv = convertHexToBin($IVKEY);
		$key = convertHexToBin($CRYPTOKEY);
		$ciphertext = openssl_encrypt($plaintext, "aes-256-cbc", $key, true, $iv);
		$ciphertext = base64_encode($ciphertext);
		
		return $ciphertext;
	}
	
	function toDecrypt($ciphertext){
		global $CPID, $CRYPTOKEY, $IVKEY;
		
		$iv = convertHexToBin($IVKEY);
		$key = convertHexToBin($CRYPTOKEY);
		$ciphertext = base64_decode($ciphertext);
		$plaintext = openssl_decrypt($ciphertext, "aes-256-cbc", $key, true, $iv);
	
		return $plaintext;
	}
	
	function convertHexToBin( $str ) {
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
?>
