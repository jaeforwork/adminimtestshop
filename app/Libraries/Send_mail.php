<?php

//defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."../lib/phpmailer/vendor/autoload.php";
class Send_mail{
    
    static $EMAIL_ID="help@hoo.co.kr";
    //static $SMTP_HOST="220.90.209.57";
    static $SMTP_HOST="222.239.169.212";
    static $SMTP_PORT= 25;
    public function send($to,$subject, $body, $debug=0){
        try{
            $mail = new PHPMailer\PHPMailer\PHPMailer(true); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = $debug; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = false; // authentication enabled
            $mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for Gmail
            $mail->SMTPAutoTLS=false;
            $mail->Host = Send_mail::$SMTP_HOST;//"mail.jjang0u.com";
            $mail->Port = Send_mail::$SMTP_PORT;//465; // or 587
            //$mail->Hostname="www.hoo.co.kr";
            $mail->IsHTML(true);
            $mail->Username = Send_mail::$EMAIL_ID;
            //$mail->Password = EMAIL_PWD;
            $mail->setFrom(Send_mail::$EMAIL_ID,"hoo 관리자");
            $mail->CharSet="utf-8";
            
            $mail->addAddress($to);
            
            
            
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            return $mail->send();
        }catch(Exception $e){
            
            return false;
        }
    }
}