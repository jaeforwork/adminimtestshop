<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="./css/style.css" type="text/css" rel="stylesheet"  media="all" />
<title>*** 다날페이카드 정기결제Key 발급시작 요청 ***</title>
</head>
<body>
<?php
	$REQ_DATA = array();

	/******************************************************
	 *  RETURNURL 	: CPCGI(ISSUEBILLKEY)페이지의 Full URL을 넣어주세요
	 *  CANCELURL 	: BackURL페이지의 Full URL을 넣어주세요
	 ******************************************************/
	$RETURNURL = "http://your.domain/ISSUEBILLKEY.php"; 
	$CANCELURL = "http://your.domain/Cancel.php";
	
	/**************************************************
	 *Sub CP 정보
	 **************************************************/
	$REQ_DATA["SUBCPID"] = "";
	
	/**************************************************
	 * 결제 정보
	 **************************************************/
	$REQ_DATA["ORDERID"] = $_POST["orderid"];
	$REQ_DATA["ITEMNAME"] = $_POST["itemname"];
	$REQ_DATA["AMOUNT"] = $TEST_AMOUNT;
	$REQ_DATA["CURRENCY"] = "410";
	$REQ_DATA["OFFERPERIOD"] = "";
	
	/**************************************************
	 * 고객 정보
	 **************************************************/
	$REQ_DATA["USERNAME"] =$_POST["username"];
	$REQ_DATA["USERPHONE"] =$_POST["userphone"];
	$REQ_DATA["USERID"] =$_POST["userid"];
	$REQ_DATA["USEREMAIL"] =$_POST["useremail"];
	$REQ_DATA["USERAGENT"] = $_POST["useragent"];
	
	/**************************************************
	 * 기본 정보
	 **************************************************/
	$REQ_DATA["TXTYPE"] = "AUTH";
	$REQ_DATA["SERVICETYPE"] = "BATCH";

	$REQ_DATA["CANCELURL"] = $CANCELURL;
	$REQ_DATA["RETURNURL"] = $RETURNURL;
	$REQ_DATA["ISBILL"] = "Y"; // N: 실제로 결제를 일으키지 않고 BillKey만 발급. Y: 실제로 거래를 일으키고 BillKey도 발급.
	$REQ_DATA["ISNOTI"] = "N"; //노티 수신 여부(Y/N)
	$REQ_DATA["BYPASSVALUE"] = "this=is;a=test;bypass=value"; // BILL응답 또는 Noti에서 돌려받을 값. '&'를 사용할 경우 값이 잘리게되므로 유의.
	
	$RES_DATA = CallCredit($REQ_DATA, false);
	//$RES_DATA = CallCreditExec($REQ_DATA, false); //curl_init() 함수 이용이 불가능할때, curl 바이너리를 호출(curl 설치 필요)

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
