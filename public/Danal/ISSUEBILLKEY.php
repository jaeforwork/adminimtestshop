<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="./css/style.css" type="text/css" rel="stylesheet"  media="all" />
<title>*** �ٳ�����ī�� �������Key �߱޿�û ***</title>
</head>
<body>
<?php
	//************ ��ȣȭ *********************
	$RES_STR = toDecrypt( $_POST['RETURNPARAMS'] );
	$RET_MAP = str2data( $RES_STR );
	
	$RET_RETURNCODE = $RET_MAP["RETURNCODE"];
	$RET_RETURNMSG = $RET_MAP["RETURNMSG"];
	
	//*****  ����������� Ȯ�� *****************
	$RES_DATA = array();
	if( is_null($RET_RETURNCODE) || $RET_RETURNCODE != "0000" ){
		// returnCode�� ���ų� �Ǵ� �� ����� ������ �ƴ϶�� ���� ó��
		
		$RES_DATA["RETURNCODE"] = $RET_RETURNCODE;
		$RES_DATA["RETURNMSG"] = $RET_RETURNMSG;
	}
	else{
		//***** �������� ���� �� ���� �Ϸ� ��û *****
		
		/*[ �ʼ� ������ ]***************************************/
		$REQ_DATA = array();
		
		/**************************************************
		 * ���� ����
		**************************************************/
		$REQ_DATA["TID"] = $RET_MAP["TID"];
		$REQ_DATA["AMOUNT"] = $TEST_AMOUNT; //���� ������û(AUTH)�ÿ� ���´� �ݾװ� ������ �ݾ��� ����(ISBILL=Y)
				
		/**************************************************
		 * �⺻ ����
		 **************************************************/
		$REQ_DATA["TXTYPE"] = "ISSUEBILLKEY";
		$REQ_DATA["SERVICETYPE"] = "BATCH";

		$RES_DATA = CallCredit($REQ_DATA, false);
	}
	
	if ( $RES_DATA['RETURNCODE'] == "0000" ) {
?>
<form name="form" ACTION="./Success.php" METHOD="POST" >
<input TYPE="HIDDEN" NAME="RETURNCODE"  	VALUE="<?= $RES_DATA["RETURNCODE"] ?>">
<input TYPE="HIDDEN" NAME="RETURNMSG"  	VALUE="<?= $RES_DATA["RETURNMSG"] ?>">
<input TYPE="HIDDEN" NAME="TID"  	VALUE="<?= $RES_DATA["TID"] ?>">
</form>
<script>
	document.form.submit();
</script>
<?php
	} else {
		$RETURNCODE = $RES_DATA['RETURNCODE'];
		$RETURNMSG = $RES_DATA['RETURNMSG'];
		$BackURL = "Javascript:self.close()";

		include("Error.php");
	}
?>
</form>
</body>
</html>
