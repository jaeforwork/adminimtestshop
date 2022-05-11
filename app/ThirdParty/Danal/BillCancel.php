<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** 신용카드 결제 취소 *****
	
	/*[ 필수 데이터 ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 * 결제 정보
	**************************************************/
	$REQ_DATA["TID"] = ""; //결제 완료 TID
	
	/**************************************************
	 * 기본 정보
	**************************************************/
	$REQ_DATA["CANCELTYPE"] = "C";
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT;
	
	/**************************************************
	 * 취소 정보
	**************************************************/
	$REQ_DATA["CANCELREQUESTER"] = "CP_CS_PERSON";
	$REQ_DATA["CANCELDESC"] = "Item not delivered";
	
	
	$REQ_DATA["TXTYPE"] = "CANCEL";
	$REQ_DATA["SERVICETYPE"] = "DANALCARD";
	
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