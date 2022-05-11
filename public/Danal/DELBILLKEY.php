<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** 신용카드 결제 취소 *****
	
	/*[ 필수 데이터 ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 * 결제 정보
	**************************************************/
	$REQ_DATA["BILLKEY"] = "BILLKEY_Approved_by_Danal"; //ISSUEBILLKEY를 통해 발급한 BILLKEY
	
	/**************************************************
     * 기본 정보
	**************************************************/
	$REQ_DATA["TXTYPE"] = "DELBILLKEY"; 
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
