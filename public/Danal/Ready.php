<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="./css/style.css" type="text/css" rel="stylesheet"  media="all" />
<title>*** �ٳ�����ī�� �������Key �߱޽��� ��û ***</title>
</head>
<body>
<?php
	$REQ_DATA = array();

	/******************************************************
	 *  RETURNURL 	: CPCGI(ISSUEBILLKEY)�������� Full URL�� �־��ּ���
	 *  CANCELURL 	: BackURL�������� Full URL�� �־��ּ���
	 ******************************************************/
	$RETURNURL = "http://your.domain/ISSUEBILLKEY.php"; 
	$CANCELURL = "http://your.domain/Cancel.php";
	
	/**************************************************
	 *Sub CP ����
	 **************************************************/
	$REQ_DATA["SUBCPID"] = "";
	
	/**************************************************
	 * ���� ����
	 **************************************************/
	$REQ_DATA["ORDERID"] = $_POST["orderid"];
	$REQ_DATA["ITEMNAME"] = $_POST["itemname"];
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT;
	$REQ_DATA["CURRENCY"] = "410";
	$REQ_DATA["OFFERPERIOD"] = "";
	
	/**************************************************
	 * �� ����
	 **************************************************/
	$REQ_DATA["USERNAME"] =$_POST["username"];
	$REQ_DATA["USERPHONE"] =$_POST["userphone"];
	$REQ_DATA["USERID"] =$_POST["userid"];
	$REQ_DATA["USEREMAIL"] =$_POST["useremail"];
	$REQ_DATA["USERAGENT"] = $_POST["useragent"];
	
	/**************************************************
	 * �⺻ ����
	 **************************************************/
	$REQ_DATA["TXTYPE"] = "AUTH";
	$REQ_DATA["SERVICETYPE"] = "BATCH";

	$REQ_DATA["CANCELURL"] = $CANCELURL;
	$REQ_DATA["RETURNURL"] = $RETURNURL;
	$REQ_DATA["ISBILL"] = "Y"; // N: ������ ������ ����Ű�� �ʰ� BillKey�� �߱�. Y: ������ �ŷ��� ����Ű�� BillKey�� �߱�.
	$REQ_DATA["ISNOTI"] = "N"; //��Ƽ ���� ����(Y/N)
	$REQ_DATA["BYPASSVALUE"] = "this=is;a=test;bypass=value"; // BILL���� �Ǵ� Noti���� �������� ��. '&'�� ����� ��� ���� �߸��ԵǹǷ� ����.
	
	$RES_DATA = CallCredit($REQ_DATA, false);
	//$RES_DATA = CallCreditExec($REQ_DATA, false); //curl_init() �Լ� �̿��� �Ұ����Ҷ�, curl ���̳ʸ��� ȣ��(curl ��ġ �ʿ�)

	if ( $RES_DATA['RETURNCODE'] == "0000" ) {
?>
<form name="form" ACTION="<?= $RES_DATA["STARTURL"] ?>" METHOD="POST" >
<input TYPE="HIDDEN" NAME="STARTPARAMS"  	VALUE="<?= $RES_DATA["STARTPARAMS"] ?>">
<input TYPE="HIDDEN" NAME="CIURL"  	VALUE="">
<input TYPE="HIDDEN" NAME="COLOR"  	VALUE="">
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
