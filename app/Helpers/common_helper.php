<?php
defined('SYSTEMPATH') OR exit('No direct script access allowed');

function ajaxReturn($result, $msg="",$data=null)
{
	$resultData['result']=$result;
	
	$resultData['msg']=$msg;
	if($data) {
    $resultData['data']=$data;
	}
	echo json_encode($resultData,JSON_UNESCAPED_UNICODE);
}

function access_token($len=40)
{
	$seed="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$seedLen=strlen($seed);
	$code="";
	for($i=0;$i<$len;$i++){
		$idx=rand(0,$seedLen-1);
		$code=$code.substr($seed,$idx,1);
	}
	return $code;
}

function board_date($date_str,$gubun="-")
{
	$time=strtotime($date_str);
	$date1=date("Y".$gubun."m".$gubun."d",$time);
	$today=date("Ymd");
	
	if(str_replace( $gubun, "",$date1)!=$today) {
	    $date=$date1;
	} else {
		$date=date("H:i:s",$time);
	}
	return $date;
}

function view_date($date_str,$gubun="-")
{
  $time=strtotime($date_str);
  $date1=date("y".$gubun."m".$gubun."d H:i:s",$time);
  $temp=explode(" ",$date1);
  return $temp[0]." <em>".$temp[1]."</em>";
  $today=date("Ymd");
    
  if(str_replace( $gubun, "",$date1)!=$today) {
    $date=$date1." ".date("H:i:s",$time);
  } else {
    $date=date("H:i:s",$time);
  }
  return $date;
}


function comment_date($date_str,$gubun="-")
{
	$time=strtotime($date_str);
	$date1=date("Y".$gubun."m".$gubun."d H:i:s",$time );
	
	return $date1;
}

if ( ! function_exists('print_option')) 
{
	function print_option($arrVal, $arrText, $val,$useReturn=false)
  {
		$arrCount=count($arrVal);
		$option="";
		for($i=0;$i<$arrCount;$i++){
			if(empty($arrText)){
				if($val==$arrVal[$i]){
					$option=$option."<option value='".$arrVal[$i]."' selected='selected'>".$arrVal[$i]."</option>";
				}else{
					$option=$option."<option value='".$arrVal[$i]."' >".$arrVal[$i]."</option>";
				}
			}else{
				if($val==$arrVal[$i]){
					$option=$option."<option value='".$arrVal[$i]."' selected='selected'>".$arrText[$i]."</option>";
				}else{
					$option=$option."<option value='".$arrVal[$i]."' >".$arrText[$i]."</option>";
				}
			}
			
		}
		if($useReturn) {
			return $option;
		} else {
			echo $option;
		}
	}
}
if ( ! function_exists('print_option_key'))
{
	function print_option_key($arr, $val,$useReturn=false){
		$option="";
		foreach($arr as $key=>$v){
			if($key==$val){
				$sel="selected='selected'";
			}else{
				$sel="";
			}
			
			$option=$option."<option value='$key' $sel>$v</option>";
		}
		
		if($useReturn){
			return $option;
		}else{
			echo $option;
		}
	}
}

function unit_size($size, $point_len=2)
{
  $file_size=$size/1024;
  $unit="KB";
  if($file_size>1024) {
    $file_size=$file_size/1024;
    $unit="MB";
  }
  $file_size=number_format($file_size,$point_len).$unit;
  return $file_size;
}

function getGeoIP($ipaddress)
{
	if (function_exists("geoip_record_by_name") && filter_var($ipaddress, FILTER_VALIDATE_IP)) {
		$geoinfo = geoip_record_by_name($ipaddress);
		
   if (is_array($geoinfo)) {
    return $geoinfo;
			
			//printf("<img src=\"/layouts/elkha_ilbe/img/flag/%s.png\" width=\"16\" height=\"11\" title=\"아이피: %s&#13;국가코드: %s&#13;국가명: %s&#13;도시: %s\" style=\"vertical-align: -1px;\">"
			//		, strtolower($geoinfo['country_code']), $ipaddress, $geoinfo['country_code'], $geoinfo['country_name'], $geoinfo['city']);
    }
		
	}
	return null;
}

//--------------------------------------------------------------------------------------------
function passwd_fast($password)
{	
	return password_hash($password, PASSWORD_BCRYPT);
}

function passwd($password, $cost=12)
{
    $passwd_options=["cost"=>$cost];
	return password_hash($password, PASSWORD_BCRYPT,$passwd_options);
}


function passwd_verify($password, $hash)
{
  if(password_verify($password, $hash))
  {       
		return TRUE;
	}	    
	return FALSE;	
}

function getAuthCode($len=6)
{
	$seed="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$seedLen=strlen($seed);
	$code="";
	for($i=0;$i<$len;$i++)
  {
		$idx=rand(0,$seedLen-1);
		$code=$code.substr($seed,$idx,1);
	}
	return $code;
}



/**
 * 파마리터를 배열 형식으로 변경
 * @param unknown $param
 * @return mixed[]
 */
function parseParam($param)
{	
	$ret=[];
	$param=trim($param);
	if(empty($param))
  {
		return $ret;
	}
	$temp=explode("&", $param);
	foreach($temp as $data)
  {		
		$temp2=explode("=", $data);		
		$ret[$temp2[0]]=$temp2[1];		
	}
	return $ret;
}

// function backAlert($msg,$back=TRUE){
// 	echo "<script>";
// 	if($msg!==""){   
// 	   echo "alert('$msg');";
// 	}
// 	if($back){
// 	   echo "history.back();";
// 	}
// 	echo "</script>";
// 	exit;
// }
// function goAlert($msg,$url){
// 	echo "<script>alert('$msg');location.href='$url';</script>";
// 	exit;
// }

// function closeAlert($msg,$callBack=""){
// 	echo "<script>alert('$msg');$callBack;self.close();</script>";
// 	exit;
// }

function searchHighlight($arrSearch, $str)
{
	foreach ($arrSearch as $s)
  {
		if($s!="")
    {
			//$str=str_ireplace($s, "<strong>$s</strong>", $str);
			$str=preg_replace("/($s)/i", "<strong>$1</strong>", $str);
		}
	}
	return $str;
}

function swapArray($array, $size)
{
	$temp=[];
	foreach($array as $val)
  {
		$temp[]=$val;
	}
	$ret=[];
	for($i=0;$i<$size; $i++)
  {
		$len=count($temp);
		$idx= rand(0,$len-1);
		
		$count=0;
		foreach ($temp as $key=>$val)
    {
			if($idx==$count)
      {
				$ret[]=$temp[$key];
				unset($temp[$key]);
			}
			$count++;
		}
	}
	return $ret;
}

// function urlGet($url, $headers=array()/*add header*/){
// 	$cu = curl_init();
// 	curl_setopt($cu, CURLOPT_URL,$url); // 데이타를 보낼 URL 설정
// 	//curl_setopt($cu, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); // 해당 데이타를 보낼 http head 정의 : 삭제해도 되긴함
// 	curl_setopt($cu, CURLOPT_POST, false); // 데이타를 get/post 로 보낼지 설정
// 	curl_setopt ($cu, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다.
// 	if($headers)curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
// 	// default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
// 	curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
// 	curl_setopt($cu, CURLOPT_RETURNTRANSFER,1); // REQUEST 에 대한 결과값을 받을건지 체크 #Resource ID 형태로 넘어옴 :: 내장 함수 curl_errno 로 체크
// 	curl_setopt($cu, CURLOPT_TIMEOUT,100); // REQUEST 에 대한 결과값을 받는 시간타임 설정
// 	$output = curl_exec($cu); // 실행
// 	curl_close($cu);
// 	return $output;
// }


function urlPost($url,$param, $headers=array()){
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL,$url); // 데이타를 보낼 URL 설정
    //curl_setopt($cu, CURLOPT_URL,"https://apcert.checkover.kr/fail_request.php"); // 데이타를 보낼 URL 설정
    //curl_setopt($cu, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); // 해당 데이타를 보낼 http head 정의 : 삭제해도 되긴함
    curl_setopt($cu, CURLOPT_POST, true); // 데이타를 get/post 로 보낼지 설정
    curl_setopt($cu, CURLOPT_POSTFIELDS,$param); // 보낼 데이타를 설정 형식은 GET 방식으로 설정    
    if($headers)curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ($cu, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다.
    // default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
    curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($cu, CURLOPT_HTTPHEADER,$headers);
    
    curl_setopt($cu, CURLOPT_RETURNTRANSFER,1); // REQUEST 에 대한 결과값을 받을건지 체크 #Resource ID 형태로 넘어옴 :: 내장 함수 curl_errno 로 체크
    curl_setopt($cu, CURLOPT_TIMEOUT,10); // REQUEST 에 대한 결과값을 받는 시간타임 설정
    $output = curl_exec($cu); // 실행
    curl_close($cu);
    return $output;
}


// function urlDelete($url,$param, $headers=array()){
// 	$cu = curl_init();
// 	curl_setopt($cu, CURLOPT_URL,$url); // 데이타를 보낼 URL 설정
// 	//curl_setopt($cu, CURLOPT_URL,"https://apcert.checkover.kr/fail_request.php"); // 데이타를 보낼 URL 설정
// 	curl_setopt($cu, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); // 해당 데이타를 보낼 http head 정의 : 삭제해도 되긴함
// 	curl_setopt($cu, CURLOPT_POST, true); // 데이타를 get/post 로 보낼지 설정
// 	curl_setopt($cu, CURLOPT_POSTFIELDS,$param); // 보낼 데이타를 설정 형식은 GET 방식으로 설정
// 	curl_setopt($cu, CURLOPT_CUSTOMREQUEST, "DELETE"); 
// 	if($headers)curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
// 	curl_setopt ($cu, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다.
// 	// default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
// 	curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
// 	//curl_setopt($cu, CURLOPT_HTTPHEADER,$headers);
	
// 	curl_setopt($cu, CURLOPT_RETURNTRANSFER,1); // REQUEST 에 대한 결과값을 받을건지 체크 #Resource ID 형태로 넘어옴 :: 내장 함수 curl_errno 로 체크
// 	curl_setopt($cu, CURLOPT_TIMEOUT,5); // REQUEST 에 대한 결과값을 받는 시간타임 설정
// 	$output = curl_exec($cu); // 실행
// 	curl_close($cu);
// 	return $output;
// }


// /**
//  * 
//  * @param unknown $target_url 업로드 URL
//  * @param unknown $filepath  업로드 파일 절대 경로
//  * @param unknown $ext  파일 확장자
//  * @return mixed  성공시 파일의 url, 실패 Null
//  */
// function httpUpload($target_url, $filepath, $ext){
// 	$fileHash=hash_file("md5",$filepath);
// 	$fileSize=filesize($filepath);
// 	$saveFileName=$fileHash."_".$fileSize.$ext;
	
	
// 	if (function_exists('curl_file_create')) { // php 5.5+
// 		$cFile = new CURLFile($filepath);		
// 		$cFile->setPostFilename($saveFileName);
// 	} else { //
// 		$cFile = '@' . realpath($filepath)."; filename=$saveFileName";
// 	}
// 	$post = array('upload'=> $cFile);
// 	$cu = curl_init();
// 	curl_setopt($cu, CURLOPT_URL,$target_url);
// 	curl_setopt($cu, CURLOPT_POST,1);
// 	curl_setopt($cu, CURLOPT_POSTFIELDS, $post);
// 	curl_setopt($cu, CURLOPT_RETURNTRANSFER,1);
// 	$result=curl_exec ($cu);
// 	curl_close ($cu);
// 	$data=json_decode($result);
	
// 	if($data->result=="Y"){
// 		return $data->url;
// 	}
	
// 	return NULL;
// }

function milliseconds()
{
	$mt = explode(' ', microtime());
	return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

// function replaceLTGT($str){
// 	$arraySearch=array('<', '>');
// 	$arrayReplace=array('&lt;', '&gt;');
// 	return str_replace($arraySearch, $arrayReplace, $str);
// }

function find_links($post_content)
{
	$reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	// Check if there is a url in the text
	if(preg_match_all($reg_exUrl, $post_content, $urls)) 
  {
		// make the urls hyper links,
		foreach($urls[0] as $url){
			$post_content = str_replace($url, '<a href="'.$url.'" target="_blank">'.$url.'</a>', $post_content);
		}
		//var_dump($post_content);die(); //uncomment to see result
		//return text with hyper links
		return $post_content;
	} else {
		// if no urls in the text just return the text
		return $post_content;
	}
}


function has_url($content)
{
  $reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
  return preg_match_all($reg_exUrl, $content);
}

function url_auto_link($str = '', $popup = true)
{
	if (empty($str)) {
		return "";
	}
	$target = $popup ? 'target="_blank"' : '';
	/*$str = str_replace(
			array("&lt;", "&gt;",  "&quot;", "&nbsp;", "&#039;"),
			array("\t_lt_\t", "\t_gt_\t",  "\"", "\t_nbsp_\t", "'"),
			$str
			);
	*/
	$str = preg_replace(
			"/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
			"\\1<a href=\"\\2\" {$target}>\\2</A>",
			$str
	);
	
	/*
	$str = str_replace(
			array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t"),
			array("&nbsp;", "&lt;", "&gt;"),
			$str
			);
	*/
	return $str;
}

function calWhen($t)
{
	$tt=time()-$t;
	if($tt<60){
		return $tt."초 전";
	}else if($tt<3600){
		$min=intval($tt/60);
		return $min."분 전";
	}else if($tt<86400){
		$hour=intval($tt/3600);
		return $hour."시간 전";
	}else {
		$day=intval($tt/86400);
		return $day."일 전";
	}	
}

function today()
{
  $yoil = array("일","월","화","수","목","금","토");    
  $today=date("Y년 m월 d일 ").$yoil[date("w")]."요일";
  return $today;
}


function format($d)
{
    $t=explode(".",$d);
    if(count($t)==2 && $t[1]>0){
        return number_format($d,2);
    }
    return number_format($d);
}


// // 경고메세지를 경고창으로
// function alert($msg='', $url='') {
	
//   $config = config('App');

// 	if (!$msg) $msg = '올바른 방법으로 이용하세요.';

// 	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
// 	echo "<script type='text/javascript'>alert('".$msg."');";
// 	if (!$url)
//         echo "history.go(-1);";
//     echo "</script>";
//     if ($url)
//         goto_url($url);
// 	exit;
// }

// // 경고메세지 출력후 창을 닫음
// function alert_close($msg) {
	
//   $config = config('App'); 

// 	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$config->charset."\">";
// 	echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
// 	exit;
// }

// 해당 url로 이동
function goto_url($url) 
{
	$temp = parse_url($url);
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? RT_PATH.'/'.$url : $CI->config->item('base_url').RT_PATH;
	}
	echo "<script type='text/javascript'> location.replace('".$url."'); </script>";
	exit;
}

// // 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
// function get_token() {
// 	$CI =& get_instance();

// 	$token = md5(uniqid(rand(), TRUE));
// 	$CI->session->set_userdata('ss_token', $token);

// 	return $token;
// }

// // POST로 넘어온 토큰과 세션에 저장된 토큰 비교
// function check_token($url=FALSE) {
// 	$CI =& get_instance();
// 	// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
// 	if ($CI->input->post('token') && $CI->session->userdata('ss_token') == $CI->input->post('token')) {
// 		// 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
// 		$CI->session->unset_userdata('ss_token');
// 	}
// 	else
// 		alert('Access Error',($url) ? $url : $CI->input->server('HTTP_REFERER'));

// 	// 잦은 토큰 에러로 인하여 토큰을 사용하지 않도록 수정
// 	// $CI->session->unset_userdata('ss_token');
// 	// return TRUE;
// }

// function check_wrkey() {
// 	$CI =& get_instance();
// 	$key = $CI->session->userdata('captcha_keystring');
// 	if (!($key && $key == $CI->input->post('wr_key'))) {
// 		$CI->session->unset_userdata('captcha_keystring');
// 	    alert('정상적인 접근이 아닙니다.', '/');
// 	}
// }

// //추가


// url에 http:// 를 붙인다
function set_http($url) 
{
  if (!trim($url)) return;
  if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
  $url = "http://" . $url;
  return $url;
}

// 한글 요일
function get_yoil($date, $full=0) 
{
  $arr_yoil = array ("일", "월", "화", "수", "목", "금", "토");

  $yoil = date("w", strtotime($date));
  $str = $arr_yoil[$yoil];
  if ($full) {
    $str .= "요일";
  }
  return $str;
}


//휴대전화번호 인지 여부를 가려낸다.
function is_hp($hp) 
{
  $hp = str_replace('-', '', trim($hp));
  if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp))
    return true;
  else
    return false;
}
	
	
//휴대전화의 번호를 통일화한다.
//$mb_mobile = get_hp($mb_mobile, 0);	
//$mb_mobile = get_hp($mb_mobile, 1); "-"이 있는 경우
function get_hp($hp, $hyphen=1) 
{
  if (!is_hp($hp)) return '';
  if ($hyphen) $preg = "$1-$2-$3"; else $preg = "$1$2$3";
  $hp = str_replace('-', '', trim($hp));
  $hp = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $preg, $hp);
  return $hp;
}

function ShowHp($hp) 
{
	if(strlen($hp)==11):
  $hp = substr($hp,0,3)."-".substr($hp,3,4)."-".substr(hp,7,4); 
	Elseif(strlen($hp)==10):
  $hp = substr($hp,0,3)."-".substr($hp,3,3)."-".substr($hp,6,4);           
	Endif;

	return $hp;
}

	
//날짜를 기준으로 종료일을 구한다.
 function ddayTimeNum($startday,$dday,$except_wdays)
 {     
  $stime = strtotime($startday); 
  $kwday = array('일','월','화','수','목','금','토'); 
  $wdays_code = array_keys(array_intersect($kwday,explode(',',$except_wdays))); 
  $wdays_cnt = sizeof($wdays_code); 
  $week_date_cnt = (7-$wdays_cnt); 
  $week_cnt = floor($dday/$week_date_cnt); 
  $week_left_date_cnt = $dday%$week_date_cnt; 
  $last_wdays_dcnt=0; 
  for($i=0;$i<=$week_left_date_cnt;$i++) { 
    if(in_array((date('w',$stime)+$i)%7,$wdays_code)) { 
      $last_wdays_dcnt++; 
      $week_left_date_cnt++; 
    } 
  }      
  $wdays_dcnt = ($week_cnt*$wdays_cnt); 

  $tar_date = strtotime($startday.' + '.($dday+$wdays_dcnt+$last_wdays_dcnt).' days'); 

  return $tar_date; 
} 

function get_valid_ip($ip) {  
  return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .             "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $ip ); 
} 
		 
		 		 
//날짜 형식을 바꿈
 function ChangeDate($datetime,$type) 
 {       

  if($type=="Y-m-d") {
    $datetime=substr($datetime,2,8);
    return  $datetime;
  } else if($type=="y/m/d") {
    $datetime = substr($datetime,2,2)."/".substr($datetime,5,2)."/".substr($datetime,8,2);
    return  $datetime;
  } else if($type=="Y-m-d H:i:s") {
    $datetime=substr($datetime,2,17);
    return  $datetime;
  } else if($type=="H:i:s") {
    $datetime=substr($datetime,12,8);
    return  $datetime;
  } else if($type=="y/m/d H:i:s") {
    $datetime = substr($datetime,2,2)."/".substr($datetime,5,2)."/".substr($datetime,8,2)." ".substr($datetime,11,2).":".substr($datetime,14,2).":".substr($datetime,17,2);
    return  $datetime;
  }	
}		 
		 
		

//윤년인지 아닌지 확인하기    12-06-25 08-38
function checkLeapyear($year) 
{
  return checkdate(2, 29, $year);
}

//$flag1 = checkLeapyear(2000);//2000年はうるう年なのでTRUE
//$flag2 = checkLeapyear(2007);//平年なのでFALSE



/*
PHP Array 변수를 JS Array 변수로 변환하여 리턴
*/
function phpArray2jsArray2($array, $checkDataType=false) {
	$js = '[';
	$tmp = '';
	$prevKey = -1;
	if(is_Array($array)) {
		foreach($array as $key => $val) {
			$curKey = intval($key);

			while($prevKey < $curKey-1) {
				if(strlen($tmp)>0) $tmp .= ',';
				$tmp .= 'null';
				$prevKey ++;
			}
			if(strlen($tmp)>0) $tmp .= ',';

			if(is_array($val)) {
				$tmp .= phpArray2jsArray2($val, $checkDataType);
			} else {
				if($checkDataType && is_int($val)) $tmp .= $val;
				else if($checkDataType && is_bool($val)) $tmp .= $val ? 'true':'false';
				else $tmp .= '"'.my_javascriptspecialchars($val).'"';
			}
			$prevKey = $curKey;
		}
	}
	$js .= $tmp;
	$js .= ']';

	return $js;
	
}


// my_javascriptspecialchars($str) : 자바스크립트 특수문자 인코딩
function my_javascriptspecialchars($str) {
	$src = Array("\\", "\"","'", "\n", "\r");
	$tar = Array("","","","","");

	return preg_replace("/[^0-9]*/s", "", str_replace($src,$tar,$str));
}


function my_javascriptspecialchars2($str) {
	$src = Array("\\","\"", "\n", "\r");
	$tar = Array("\\\\","\\\"", "\\n", "\\r");

	return str_replace($src,$tar,$str);
}



// // TEXT 형식으로 변환 이미 있는 것 같다. get_text
// function GetText($str, $html=0)
// {
//     /* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
//     $source[] = "/  /";
//     $target[] = " &nbsp;";
//     */

//     // 3.31
//     // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
//     if ($html == 0) {
//         $str = html_symbol($str);
//     }

//     $source[] = "/</";
//     $target[] = "&lt;";
//     $source[] = "/>/";
//     $target[] = "&gt;";
//     //$source[] = "/\"/";
//     //$target[] = "&#034;";
//     $source[] = "/\'/";
//     $target[] = "&#039;";
//     //$source[] = "/}/"; $target[] = "&#125;";
//     if ($html) {
//         $source[] = "/\n/";
//         $target[] = "<br/>";
//     }

//     return preg_replace($source, $target, $str);
// }

function html_symbol($str) {
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}


// 이미지 테그를 지운다.
function RemoveImgTag($str) 
{
 $result = preg_replace('#<img[^>]*>#i', '', $str);
 return $result;
}


//게시판 제목 자르기
function substr_utf8($str,$from,$len)
{
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $from .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $len .'}).*#s','$1', $str);
}

function generateRandomString($type,$length) 
{
  if($type=='N') {
    $characters = '0123456789';
  } else if($type=='n') {
    $characters = '123456789';
  } else if($type=='T') {  
   $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  } else if($type=='t') { 
   $characters = 'abcdefghijklmnopqrstuvwxyz';
  } else {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }   
  $charactersLength = strlen($characters);
  $randomString = '';
  
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}


function check_csfs($nick){
  $pattern="/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/";
  if(preg_match($pattern, $nick)){
      return true;
  }else{
      return false;
  }
}


  function valid_nick($nick){
      $pattern="/^[가-힣a-zA-z0-9]{2,7}$/u";
      if(preg_match($pattern, $nick)){
          return true;
      }else{
          return false;
      }
  }
  
  
  function valid_userid($userid){
      $pattern="/^[a-z]{1}[a-z0-9_]{3,19}$/";
      if(preg_match($pattern, $userid)){
          return true;
      }else{
          return false;
      }
  }
  
  function valid_password($pwd){
      $pattern="/[^\s*]{6,}$/";
      return preg_match($pattern, $pwd);
  }

  function valid_email($email){
      if (function_exists('idn_to_ascii') && preg_match('#\A([^@]+)@(.+)\z#', $email, $matches))
      {
          $domain = defined('INTL_IDNA_VARIANT_UTS46')
          ? idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46)
          : idn_to_ascii($matches[2]);
          
          if ($domain !== FALSE)
          {
              $email = $matches[1].'@'.$domain;
          }
      }
      
      $r= (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
      
      if($r){
          $email_host=strtolower(explode("@", $email)[1]);
          $email_id=explode("@", $email)[0];
          
          $pos=strpos($email_id, "+");
          if($email_host=="gmail.com" && $pos!==false){
              
              return FALSE;
          }
          $pos=strpos($email_id, ".");
          if($email_host=="gmail.com" && $pos!==false){
              
              return FALSE;
          }
          return $r;
      }else{
          
          return $r;
      }
  }

  function valid_birth($birth){
    if(!preg_match('/^(19[0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/', $birth) || $birth >= date("Ymd")){
        
        return FALSE;
    }
    
    return TRUE;
}



// URL 인코드
function url_encode($str) {
	return str_replace('%', '.', urlencode($str));
}


// function encrypt($data,$key=ENC_KEY_128, $iv=IV){
//   if(!$data)return "";
//   //$key = TOKEN_KEY;    
//   $enc = @openssl_encrypt($data , "aes-128-cbc", $key, true, $iv);
//   $enc= strtoupper(bin2hex($enc));
//   return $enc;
// }

// function decrypt($encData,$key=ENC_KEY_128, $iv=IV){
//   if(!$encData)return "";
//   $enc= hex2bin($encData);
//   //$key = TOKEN_KEY;
//   $dec = @openssl_decrypt($enc , "aes-128-cbc", $key, true, $iv);
//   //$enc= strtoupper(bin2hex($enc));
//   return $dec;  
// }

// function encrypt256($data,$key=ENC_KEY_256, $iv=IV){
//   if(!$data)return "";
//   //$key = TOKEN_KEY;
//   $enc = @openssl_encrypt($data , "aes-256-cbc", $key, true, $iv);
//   $enc= strtolower(bin2hex($enc));    
//   return $enc;    
// }

// function decrypt256($encData,$key=ENC_KEY_256, $iv=IV){
//     if(!$encData)return "";
//     //$enc= hex2bin($encData);
//     //$key = TOKEN_KEY;
//     $dec = @openssl_decrypt($encData , "aes-256-cbc", $key, true, $iv);
//     //$enc= strtoupper(bin2hex($enc));
//     $dec= strtolower(bin2hex($dec));    
//     return $dec;
    
// }



