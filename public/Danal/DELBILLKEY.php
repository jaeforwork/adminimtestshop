<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");

	//***** �ſ�ī�� ���� ��� *****
	
	/*[ �ʼ� ������ ]***************************************/
	$REQ_DATA = array();
	
	/**************************************************
	 * ���� ����
	**************************************************/
	$REQ_DATA["BILLKEY"] = "BILLKEY_Approved_by_Danal"; //ISSUEBILLKEY�� ���� �߱��� BILLKEY
	
	/**************************************************
     * �⺻ ����
	**************************************************/
	$REQ_DATA["TXTYPE"] = "DELBILLKEY"; 
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
