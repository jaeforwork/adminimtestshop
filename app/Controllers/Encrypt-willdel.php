<?php
namespace App\Controllers;

class Encrypt extends BaseController {
  public function index() {
    $encrypter = \Config\Services::encrypter();
    $plainText1 = 'This is a plain-text message!';
    $plainText2 = '한글테스트입니다.';
    $plainText3 = '010-1234-5679';

    $text_encrypt1 = base64_encode($encrypter->encrypt($plainText1));
    $text_decrypt1 = $encrypter->decrypt(base64_decode($text_encrypt1));
    $text_encrypt2 = base64_encode($encrypter->encrypt($plainText2));
    $text_decrypt2 =$encrypter->decrypt(base64_decode($text_encrypt2));
   //$mobile_encrypt1 = $encrypter->encrypt($plainText3);
    $mobile_encrypt1 = base64_encode($encrypter->encrypt($plainText3));
    $mobile_decrypt1 = $encrypter->decrypt(base64_decode($mobile_encrypt1));

    $data['title'] = ucfirst('encrypt decrypt test'); // Capitalize the first letter

    $data['text_encrypt1'] = $text_encrypt1; 
    $data['text_decrypt1'] = $text_decrypt1; 
    $data['text_encrypt2'] = $text_encrypt2; 
    $data['text_decrypt2'] = $text_decrypt2; 
    $data['mobile_encrypt1'] = $mobile_encrypt1; 
    $data['mobile_decrypt1'] = $mobile_decrypt1; 
print_r($data);


    // echo view('templates/header', $data);
    // echo view('db/encrypt');
    // echo view('templates/footer', $data);

    
  }






}
