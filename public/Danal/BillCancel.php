<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** �ſ�ī�� ���� ��� *****
	
	/*[ �ʼ� ������ ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 * ���� ����
	**************************************************/
	$REQ_DATA["TID"] = ""; //���� �Ϸ� TID
	
	/**************************************************
	 * �⺻ ����
	**************************************************/
	$REQ_DATA["CANCELTYPE"] = "C";
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT;
	
	/**************************************************
	 * ��� ����
	**************************************************/
	$REQ_DATA["CANCELREQUESTER"] = "CP_CS_PERSON";
	$REQ_DATA["CANCELDESC"] = "Item not delivered";
	
	
	$REQ_DATA["TXTYPE"] = "CANCEL";
	$REQ_DATA["SERVICETYPE"] = "DANALCARD";
	
	$RES_DATA = CallCredit($REQ_DATA, false);
	
	if ( $RES_DATA['RETURNCODE'] == "0000" ) {
		// ���� ���� �� �۾� ����
		echo urldecode( data2str( $RES_DATA ) );
	}
	else{
		// ���� ���� �� �۾� ����
		echo urldecode( data2str( $RES_DATA ) );
	}

?>