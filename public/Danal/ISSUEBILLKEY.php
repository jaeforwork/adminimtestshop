<?php
	header("Pragma: No-Cache");
	include("./inc/function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="./css/style.css" type="text/css" rel="stylesheet"  media="all" />
<title>*** 다날페이카드 정기결제Key 발급요청 ***</title>
</head>
<body>
<?php
	//************ 복호화 *********************
	$RES_STR = toDecrypt( $_POST['RETURNPARAMS'] );
	$RET_MAP = str2data( $RES_STR );
	
	$RET_RETURNCODE = $RET_MAP["RETURNCODE"];
	$RET_RETURNMSG = $RET_MAP["RETURNMSG"];
	
	//*****  본인인증결과 확인 *****************
	$RES_DATA = array();
	if( is_null($RET_RETURNCODE) || $RET_RETURNCODE != "0000" ){
		// returnCode가 없거나 또는 그 결과가 성공이 아니라면 실패 처리
		
		$RES_DATA["RETURNCODE"] = $RET_RETURNCODE;
		$RES_DATA["RETURNMSG"] = $RET_RETURNMSG;
	}
	else{
		//***** 본인인증 성공 시 결제 완료 요청 *****
		
		/*[ 필수 데이터 ]***************************************/
		$REQ_DATA = array();
		
		/**************************************************
		 * 결제 정보
		**************************************************/
		$REQ_DATA["TID"] = $RET_MAP["TID"];
		$REQ_DATA["AMOUNT"] = $TEST_AMOUNT; //최초 결제요청(AUTH)시에 보냈던 금액과 동일한 금액을 전송(ISBILL=Y)
				
		/**************************************************
		 * 기본 정보
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
