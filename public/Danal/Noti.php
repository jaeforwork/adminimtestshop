<?php
	error_reporting(0);
	
	header("Pragma: No-Cache");
	include("./inc/function.php");

	$RET_STR = $_POST['DATA'];
	$RET_STR = urldecode($RET_STR);
	
	//mcyrpt 라이브러리 설치 여부 확인
	if( function_exists("mcrypt_encrypt") ){
		$RET_STR = toDecrypt( $RET_STR );
	}
	else{
		$RET_STR = "**mcrypt library fail**";
	}
	
	//Log Example
	$Out = "";
	$Out .= "] DATA [";
	$Out .= $_POST['DATA'];
	$Out .= "] DECRYPT DATA [";
	$Out .= $RET_STR;
	$Out .= "]";
	
	if(!file_exists("./log")){
		echo ("Fail-Cannot open log file");
		exit();
	}
	
	$fp = fopen("./log/noti_".date("Ymd").".log","a+");
	fputs($fp,"[".date("Y-m-d H:i:s")."]".$Out."\n");
	fclose($fp);
	
	echo("OK");
	
	/***************************************************
	 * Noti 성공 시 결제 완료에 대한 작업
	* - Noti의 결과에 따라 DB작업등의 코딩을 삽입하여 주십시오.
	* - ORDERID, AMOUNT 등 결제 거래내용에 대한 검증을 반드시 하시기 바랍니다.
	****************************************************/
?>