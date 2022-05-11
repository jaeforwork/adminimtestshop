<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** 다날페이카드 정기결제 결제 요청 *****
	
	/*[ 필수 데이터 ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 *Sub CP 정보
	**************************************************/
	$REQ_DATA["SUBCPID"] = "subcpid"; 
	
	/**************************************************
	 * 결제 정보
	**************************************************/
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT; 
	$REQ_DATA["CURRENCY"] = "410"; 
	$REQ_DATA["ITEMNAME"] = "TESTITEM"; 
	$REQ_DATA["USERAGENT"] = "ONLINE"; //고정값
	$REQ_DATA["ORDERID"] = "ORDERID"; 
	
	/**************************************************
	 * 고객 정보
	**************************************************/
	$REQ_DATA["USERNAME"] = "username";
	$REQ_DATA["USERPHONE"] = "01012345678";
	$REQ_DATA["USERID"] = "userid";
	
	/**************************************************
	 * 카드 정보
	**************************************************/
	$REQ_DATA["ISREBILL"] = "Y"; //고정값
	$REQ_DATA["BILLINFO"] = ""; //정기결제KEY

	/**************************************************
	 * 기본 정보
	 **************************************************/
	$REQ_DATA["TXTYPE"] = "OTBILL";
	$REQ_DATA["SERVICETYPE"] = "BATCH";
	
	$RES_DATA = CallCredit($REQ_DATA, false);
	
	if ( $RES_DATA['RETURNCODE'] == "0000" ) {
		// 결제 성공 시 작업 진행
		echo urldecode( data2str( $RES_DATA ) );
	}
	else{
		// 결제 실패 시 작업 진행
		echo urldecode( data2str( $RES_DATA ) );
	}

?>
