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
	$RETURNURL = "http://api.petglet.com/api/ISSUEBILLKEY.php"; 
	$CANCELURL = "http://api.petglet.com/api/Cancel.php";
	
	/**************************************************
	 *Sub CP 정보
	 **************************************************/
	$REQ_DATA["SUBCPID"] = "";

	
	/**************************************************
	 * 결제 정보
	 **************************************************/
	$REQ_DATA["ORDERID"]      ='API0125424455';
	$REQ_DATA["ITEMNAME"]     = 'ITEMNAME';
	$REQ_DATA["AMOUNT"]       = '100';
	$REQ_DATA["CURRENCY"]     = "410";
	$REQ_DATA["OFFERPERIOD"]  = "";
	
	/**************************************************
	 * 고객 정보
	 **************************************************/
	$REQ_DATA["USERNAME"]     ='USERNAME';
	$REQ_DATA["USERPHONE"]    ='01058965938';
	$REQ_DATA["USERID"]       ='USERID';
	$REQ_DATA["USEREMAIL"]    ='jaeforwork2020@gmail.com';
	$REQ_DATA["USERAGENT"]    = 'ONLINE';
	
	/**************************************************
	 * 기본 정보
	 **************************************************/
	$REQ_DATA["TXTYPE"]       = "ISSUEBILLKEY";
	$REQ_DATA["SERVICETYPE"]  = "BATCH";

	$REQ_DATA["CANCELURL"]    = $CANCELURL;
	$REQ_DATA["RETURNURL"]    = $RETURNURL;
	$REQ_DATA["ISBILL"]       = "N"; // N: 실제로 결제를 일으키지 않고 BillKey만 발급. Y: 실제로 거래를 일으키고 BillKey도 발급.
	// $REQ_DATA["ISNOTI"]       = "N"; //노티 수신 여부(Y/N)
	// $REQ_DATA["BYPASSVALUE"]  = "this=is;a=test;bypass=value"; // BILL응답 또는 Noti에서 돌려받을 값. '&'를 사용할 경우 값이 잘리게되므로 유의.

  $REQ_DATA["CARDNO"]       = "379183670276820";
	$REQ_DATA["EXPIREPERIOD"] = "2406";
  $REQ_DATA["CARDAUTH"]     = "720620";
	$REQ_DATA["CARDPWD"]      = "60";
  $REQ_DATA["QUOTA"]        = "00";


  print_r($REQ_DATA);
echo "<br><br>";


	$RES_DATA = CallCredit($REQ_DATA, false);
	//$RES_DATA = CallCreditExec($REQ_DATA, false); //curl_init() 함수 이용이 불가능할때, curl 바이너리를 호출(curl 설치 필요)
print_r($RES_DATA);
echo "<br><br>";


$RETURNMSG=iconv("EUC-KR", "UTF-8", $RES_DATA['RETURNMSG']);

echo $RETURNMSG;
echo "<br><br>";



exit;

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
