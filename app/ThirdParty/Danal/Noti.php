<?php
	error_reporting(0);
	
	header("Pragma: No-Cache");
	include("./inc/function.php");

	$RET_STR = $_POST['DATA'];
	$RET_STR = urldecode($RET_STR);
	
	//mcyrpt ���̺귯�� ��ġ ���� Ȯ��
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
	 * Noti ���� �� ���� �Ϸῡ ���� �۾�
	* - Noti�� ����� ���� DB�۾����� �ڵ��� �����Ͽ� �ֽʽÿ�.
	* - ORDERID, AMOUNT �� ���� �ŷ����뿡 ���� ������ �ݵ�� �Ͻñ� �ٶ��ϴ�.
	****************************************************/
?>