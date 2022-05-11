<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** �ٳ�����ī�� ������� ���� ��û *****
	
	/*[ �ʼ� ������ ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 *Sub CP ����
	**************************************************/
	$REQ_DATA["SUBCPID"] = "subcpid"; 
	
	/**************************************************
	 * ���� ����
	**************************************************/
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT; 
	$REQ_DATA["CURRENCY"] = "410"; 
	$REQ_DATA["ITEMNAME"] = "TESTITEM"; 
	$REQ_DATA["USERAGENT"] = "ONLINE"; //������
	$REQ_DATA["ORDERID"] = "ORDERID0245354"; 
	
	/**************************************************
	 * ���� ����
	**************************************************/
	$REQ_DATA["USERNAME"] = "username";
	$REQ_DATA["USERPHONE"] = "01012345678";
	$REQ_DATA["USERID"] = "userid";
	
	/**************************************************
	 * ī�� ����
	**************************************************/
	$REQ_DATA["ISREBILL"] = "N"; //������
	$REQ_DATA["BILLINFO"] = ""; //�������KEY

	/**************************************************
	 * �⺻ ����
	 **************************************************/
	$REQ_DATA["TXTYPE"] = "ISSUEBILLKEY";
	$REQ_DATA["SERVICETYPE"] = "BATCH";
	
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
