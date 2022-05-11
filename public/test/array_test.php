<?php

$ret = '{"result":"N","msg":"roll back","code":"0","message":"성공적으로 전송요청 하였습니다.","info":"성공적으로 전송요청 하였습니다."}';

// $ret="[code] => 0 [message] => 성공적으로 전송요청 하였습니다. [info] => ( [type] => AT [mid] => 336962435 [current] => 49869.0 [unit] => 6.5 [total] => 6.5 [scnt] => 1 [fcnt] => 0 ) )";

  // 리턴 JSON 문자열 확인
 //print_r($ret . PHP_EOL);
 echo "<br>";
  // JSON 문자열 배열 변환
 // $retArr = json_decode($ret,true);
 $retArr = json_decode($ret);
  echo "<br>";
     
  print_r($retArr);
  echo "<br>";
  echo "code <br>";
  print_r($retArr->result_code);
  echo "message <br>";
  print_r($retArr->message);
  echo "mid <br>";
  print_r($retArr->mid);
  echo "info scnt <br>";
  print_r($retArr->info->scnt);
  echo "retArr message <br>";
  print_r($retArr->message);
  echo "<br>";






    // exit;

//stdClass Object ( [code] => 0 [message] => 성공적으로 전송요청 하였습니다. [info] => stdClass Object ( [type] => AT [mid] => 336962435 [current] => 49869.0 [unit] => 6.5 [total] => 6.5 [scnt] => 1 [fcnt] => 0 ) )



