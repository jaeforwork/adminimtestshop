<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\AlertError;
use App\Models\PetModel;
use App\Models\MemberModel;
use App\Models\Driver_join_infoModel;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Files\File;
use App\Libraries\ValidChecker;

class Upload extends BaseController {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect();
  }

  public function supview() {
    $fileInfo = []; 
    return view("/upload/supload", ['fileInfo' => $fileInfo]);
  }

  public function mupview() {
    $fileInfo = []; 
    return view("/upload/mupload", ['fileInfo' => $fileInfo]);
  }

  public function pet(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }

    $IDX        = esc($request->getPost('pet_idx'));
    $file = $this->request->getFile("single_file"); 

    $fileInfo = []; 

    if(!$file){
      ajaxReturn(RESULT_EMPTY, "파일 정보가 없습니다.","" );
      return false;
    }

    if ($file != null) { //파일이 있는 경우

      //기존 이미지가 있으면 삭제하고 새로 업로드한다.    
      $CntBuilder = $this->db->table("PET");
      $CntBuilder->select('IDX');
      $CntBuilder->where(['USER_IDX' => $USER_IDX,'IDX' => $IDX,]);

      $total = $CntBuilder->countAllResults();
      if($total<>0) {        
        // ajaxReturn(RESULT_FAIL,"","");
        // return;  
        $SelBuilder = $this->db->table("PET");
        $SelBuilder->select('IMAGE');
        $SelBuilder->where(['USER_IDX' => $USER_IDX,'IDX' => $IDX,]);

        $pet = $SelBuilder->get()->getResult('array');  

        $pet_img   = $pet[0]['IMAGE'];
        $pet_file  = FCPATH . $pet_img;

        if($pet_img !="/assets/images/icon_no_image.png" && file_exists($pet_file)) {
          @unlink($pet_file); //파일 삭제
          //DB에 넣기
          $newData = ['IMAGE'=>'/assets/images/icon_no_image.png',];

          $std = new PetModel();
          $std->transBegin();

          $result = $std->update($IDX,$newData);
              
          if($std->transStatus() === FALSE) {
            $std->transRollback();
          } else {
            $std->transCommit();  
          }
        }
      }
      // 새로 업로드된 파일을 올린다.

      $maxSize=10*1024*1024; //10MB  
      $mimeTypes='jpg|png|jpeg|jfif|pjpeg|pjp';          
      //echo $file->getMimeType()." ".$file->guessExtension();
      if($msg=$this->isError($file, $maxSize, $mimeTypes)){
        ajaxReturn(RESULT_FAIL, $msg);
        return false;      
      }   

      if (!$file->isValid()) { 
        $errorString = $file->getErrorString();
        $errorCode = $file->getError(); 

        $fileInfo['hasError'] = true;
        $fileInfo['errorString'] = $errorString;
        $fileInfo['errorCode'] = $errorCode;   
      } else { 
        $fileInfo['hasError'] = false;
        $savePath = FCPATH . 'uploads/images/pet';

        if(!is_dir($savePath)) {
          @mkdir($savePath,0777,true);
        }

        if ($file->hasMoved() === false) {                
          $fileInfo['mimeType'] = $file->getMimeType(); 
          $fileInfo['guessExtension'] = $file->guessExtension(); 

          $savedPath = $file->store();

          $fileInfo['savedPath'] = $savedPath;
          $fileInfo['clientName'] = $file->getClientName();
          $fileInfo['name'] = $file->getName(); 
          $fileInfo['clientMimeType'] = $file->getClientMimeType(); 
          $fileInfo['clientExtension'] = $file->getClientExtension(); 

          rename ("".WRITEPATH."uploads/".$savedPath."", "".$savePath."/".$fileInfo['name'].""); 

          //DB에 넣기
          $new_img="/uploads/images/pet/".$fileInfo['name'];
          $newData = ['IMAGE'=>$new_img,];
                
          $std = new PetModel();
          $std->transBegin();

          $result = $std->update($IDX,$newData);
              
          if($std->transStatus() === FALSE) {
            $std->transRollback();
            $message='';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $std->transCommit();          
            $message='';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }          
        }
      }        
    }    
  }

  public function member(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.","");
      return false;  
    }

    $file = $this->request->getFile("single_file"); 
    $fileInfo = []; 

    if(!$file){
      ajaxReturn(RESULT_EMPTY, "파일 정보가 없습니다.","" );
      return false;
    }

    if ($file != null) {

  //기존 이미지가 있으면 삭제하고 새로 업로드한다.    
  $CntBuilder = $this->db->table("MEMBER");
  $CntBuilder->select('USER_IDX');
  $CntBuilder->where(['USER_IDX' => $USER_IDX]);

  $total = $CntBuilder->countAllResults();
  if($total<>0) {        
    // ajaxReturn(RESULT_FAIL,"","");
    // return;  
    $SelBuilder = $this->db->table("MEMBER");
    $SelBuilder->select('IMAGE');
    $SelBuilder->where(['USER_IDX' => $USER_IDX,'IDX' => $IDX,]);

    $member = $SelBuilder->get()->getResult('array');  

    $member_img   = $member[0]['IMAGE'];
    $member_file  = FCPATH . $member_img;

    if($member_img !="/assets/images/icon_no_image.png" && file_exists($member_file)) {
      @unlink($member_file); //파일 삭제
      //DB에 넣기
      $newData = ['IMAGE'=>'/assets/images/icon_no_image.png',];
      
      $std = new MemberModel();
      $std->transBegin();

      $result = $std->update($USER_IDX,$newData);
          
      if($std->transStatus() === FALSE) {
        $std->transRollback();
      } else {
        $std->transCommit();  
      }
    }
  }















      $maxSize=10*1024*1024; //10MB  
      $mimeTypes='jpg|png|jpeg|jfif|pjpeg|pjp';          
      //echo $file->getMimeType()." ".$file->guessExtension();
      if($msg=$this->isError($file, $maxSize, $mimeTypes)){
        ajaxReturn(RESULT_FAIL, $msg);
        return false;      
      }   

      if (!$file->isValid()) { 
        $errorString = $file->getErrorString();
        $errorCode = $file->getError(); 

        $fileInfo['hasError'] = true;
        $fileInfo['errorString'] = $errorString;
        $fileInfo['errorCode'] = $errorCode;   
      } else { 
        $fileInfo['hasError'] = false;
        $savePath = FCPATH . 'uploads/images/member';

        if(!is_dir($savePath)) {
          @mkdir($savePath,0777,true);
        }

        if ($file->hasMoved() === false) {                
          $fileInfo['mimeType'] = $file->getMimeType(); 
          $fileInfo['guessExtension'] = $file->guessExtension(); 

          $savedPath = $file->store();

          $fileInfo['savedPath'] = $savedPath;
          $fileInfo['clientName'] = $file->getClientName();
          $fileInfo['name'] = $file->getName(); 
          $fileInfo['clientMimeType'] = $file->getClientMimeType(); 
          $fileInfo['clientExtension'] = $file->getClientExtension(); 

          rename ("".WRITEPATH."uploads/".$savedPath."", "".$savePath."/".$fileInfo['name'].""); 

          //DB에 넣기
          $new_img="/uploads/images/member/".$fileInfo['name'];
          $newData = ['IMAGE'=>$new_img,];
                
          $this->db->transBegin();         
          $builder = $this->db->table("MEMBER as member");   
          $builder->where('member.USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='업로드 중 오류가 발생했습니다.';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $this->db->transCommit();          
            $message='업로드 되었습니다.';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }



        }
      }        
    }    
  }






  public function delete_member_img(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }   

    //count  
    $builder = $this->db->table("MEMBER as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();
// echo $total;
// exit;


    //select
    $builder->select('user.USER_ID, user.PHONE,user.USER_TYPE,user.NICK_NAME,user.IMAGE');
    $builder->where('user.USER_IDX', $USER_IDX);  

    $data['member'] = $builder->get()->getResult('array');   
    // $img = FCPATH .$data['member'][0]['IMAGE'];
    $img = ".".$data['member'][0]['IMAGE'];
    
    if(@unlink($img)) {

    //DB update



    //DB에 넣기
          $new_img="/uploads/images/member/icon_no_image.png";
          $newData = ['IMAGE'=>$new_img,];
                
          $this->db->transBegin();         
          $builder = $this->db->table("MEMBER as member");   
          $builder->where('member.USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='삭제하지 못했습니다.';
            ajaxReturn(RESULT_FAIL,$message,'');
            return false;
          } else {
            $this->db->transCommit();          
            $message='정상적으로 삭제되었습니다.';
            ajaxReturn(RESULT_SUCCESS,$message,'');
            return false;
          }
      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return false;
    } else {
      ajaxReturn(RESULT_FAIL,"","");  
      return false;
    } 
  }






  public function delete_pet_img(): string  {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }

    $IDX  = esc($request->getPost('idx'));

    //count
    $builder = $this->db->table('PET as user');
    //$builder = $this->db->table("MEMBER as user");
    $builder->select('user.*');
    $builder->where('user.IDX', $IDX);  
    $total = $builder->countAllResults();

    //select
    $builder->select('user.IDX,user.USER_IDX,user.IMAGE');
    $builder->where('user.IDX', $IDX);  

    $data['member'] = $builder->get()->getResult('array');   
    // $img = FCPATH .$data['member'][0]['IMAGE'];
    $img = ".".$data['member'][0]['IMAGE'];
    
    if(@unlink($img)) {
      //DB update
      $new_img="/uploads/images/pet/icon_no_image.png";
      $newData = ['IMAGE'=>$new_img,];
                
      $std = new PetModel();
      $std->transBegin();

      $result = $std->update($IDX,$newData);
              
      if($std->transStatus() === FALSE) {
        $std->transRollback();
        $message='';
        ajaxReturn(RESULT_FAIL,$message,"");
        return false;
      } else {
        $std->transCommit();          
        $message='';
        ajaxReturn(RESULT_SUCCESS,$message,$newData);
        return false;
      }

      ajaxReturn(RESULT_SUCCESS,"",$newData);  
      return false;
    } else {
      ajaxReturn(RESULT_FAIL,"","");  
      return false;
    }  
  }







  public function driver_license_img(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }

    $file = $this->request->getFile("single_file"); 
    $fileInfo = []; 

    if(!$file){
      ajaxReturn(RESULT_EMPTY, "파일 정보가 없습니다." );
      return false;
    }

    if ($file != null) {


    //기존 이미지가 있으면 삭제하고 새로 업로드한다.    
    $CntBuilder = $this->db->table("DRIVER_JOIN_INFO");
    $CntBuilder->select('DRIVER_LICENCE_IMAGE');
    $CntBuilder->where(['USER_IDX' => $USER_IDX]);

    $total = $CntBuilder->countAllResults();
    if($total<>0) {        
      // ajaxReturn(RESULT_FAIL,"","");
      // return;  
      $SelBuilder = $this->db->table("DRIVER_JOIN_INFO");
      $SelBuilder->select('DRIVER_LICENCE_IMAGE');
      $SelBuilder->where(['USER_IDX' => $USER_IDX]);

      $member = $SelBuilder->get()->getResult('array');  

      $member_img   = $member[0]['DRIVER_LICENCE_IMAGE'];
      $member_file  = FCPATH . $member_img;

      if($member_img !="/assets/images/icon_no_image.png" && file_exists($member_file)) {
        @unlink($member_file); //파일 삭제
        //DB에 넣기
        $newData = ['DRIVER_LICENCE_IMAGE'=>'/assets/images/icon_no_image.png',];

        $std = new Driver_join_infoModel();
        $std->transBegin();

        $result = $std->update($USER_IDX,$newData);
            
        if($std->transStatus() === FALSE) {
          $std->transRollback();
        } else {
          $std->transCommit();  
        }
      }
    }





      $maxSize=10*1024*1024; //10MB  
      $mimeTypes='jpg|png|jpeg|jfif|pjpeg|pjp';          
      //echo $file->getMimeType()." ".$file->guessExtension();
      if($msg=$this->isError($file, $maxSize, $mimeTypes)){
        ajaxReturn(RESULT_FAIL, $msg);
        return false;      
      }   

      if (!$file->isValid()) { 
        $errorString = $file->getErrorString();
        $errorCode = $file->getError(); 

        $fileInfo['hasError'] = true;
        $fileInfo['errorString'] = $errorString;
        $fileInfo['errorCode'] = $errorCode;   
      } else { 
        $fileInfo['hasError'] = false;
        $savePath = FCPATH . 'uploads/images/driver';

        if(!is_dir($savePath)) {
          @mkdir($savePath,0777,true);
        }

        if ($file->hasMoved() === false) {                
          $fileInfo['mimeType'] = $file->getMimeType(); 
          $fileInfo['guessExtension'] = $file->guessExtension(); 

          $savedPath = $file->store();

          $fileInfo['savedPath'] = $savedPath;
          $fileInfo['clientName'] = $file->getClientName();
          $fileInfo['name'] = $file->getName(); 
          $fileInfo['clientMimeType'] = $file->getClientMimeType(); 
          $fileInfo['clientExtension'] = $file->getClientExtension(); 

          rename ("".WRITEPATH."uploads/".$savedPath."", "".$savePath."/".$fileInfo['name'].""); 

          //DB에 넣기
          $new_img="/uploads/images/driver/".$fileInfo['name'];
          $newData = ['DRIVER_LICENCE_IMAGE'=>$new_img,];
                
          $this->db->transBegin();         
          $builder = $this->db->table("DRIVER_JOIN_INFO as user");   
          $builder->where('USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='업로드중 오류가 발생했습니다.';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $this->db->transCommit();          
            $message='업로드되었습니다.';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }



        }
      }        
    }    
  }






  public function delete_driver_license_img(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }   

    //count  
    $builder = $this->db->table("DRIVER_JOIN_INFO as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();
    // echo $total;
    // exit;


    //select
    $builder->select('user.DRIVER_LICENCE_IMAGE');
    $builder->where('user.USER_IDX', $USER_IDX);  

    $data['member'] = $builder->get()->getResult('array');   
    // $img = FCPATH .$data['member'][0]['IMAGE'];
    $img = ".".$data['member'][0]['DRIVER_LICENCE_IMAGE'];
    //  echo $img;
    // exit;

    if(@unlink($img)) {   

      //DB에 넣기
          $new_img="/uploads/images/member/icon_no_image.png";
          $newData = ['DRIVER_LICENCE_IMAGE'=>$new_img,];
                
          $this->db->transBegin();         
          $builder = $this->db->table("DRIVER_JOIN_INFO as user");   
          $builder->where('USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='삭제 중 오류가 발생했습니다.';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $this->db->transCommit();          
            $message='삭제되었습니다.';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }

      ajaxReturn(RESULT_SUCCESS,"삭제되었습니다.",$newData);  
      return false;
    } else {
      ajaxReturn(RESULT_FAIL,"삭제 중 오류가 발생했습니다.","");  
      return false;
    } 
  }











  public function driver_bank_img(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }

    $file = $this->request->getFile("single_file"); 
    $fileInfo = []; 

    if(!$file){
      ajaxReturn(RESULT_EMPTY, "파일 정보가 없습니다." );
      return false;
    }

    if ($file != null) {

      
  //기존 이미지가 있으면 삭제하고 새로 업로드한다.    
  $CntBuilder = $this->db->table("DRIVER_JOIN_INFO");
  $CntBuilder->select('BANKBOOK_IMAGE');
  $CntBuilder->where(['USER_IDX' => $USER_IDX]);

  $total = $CntBuilder->countAllResults();
  if($total<>0) {        
    // ajaxReturn(RESULT_FAIL,"","");
    // return;  
    $SelBuilder = $this->db->table("DRIVER_JOIN_INFO");
    $SelBuilder->select('BANKBOOK_IMAGE');
    $SelBuilder->where(['USER_IDX' => $USER_IDX]);

    $member = $SelBuilder->get()->getResult('array');  

    $member_img   = $member[0]['BANKBOOK_IMAGE'];
    $member_file  = FCPATH . $member_img;

    if($member_img !="/assets/images/icon_no_image.png" && file_exists($member_file)) {
      @unlink($member_file); //파일 삭제
      //DB에 넣기
      $newData = ['BANKBOOK_IMAGE'=>'/assets/images/icon_no_image.png',];

      $std = new Driver_join_infoModel();
      $std->transBegin();

      $result = $std->update($USER_IDX,$newData);
          
      if($std->transStatus() === FALSE) {
        $std->transRollback();
      } else {
        $std->transCommit();  
      }
    }
  }




      $maxSize=10*1024*1024; //10MB  
      $mimeTypes='jpg|png|jpeg|jfif|pjpeg|pjp';          
      //echo $file->getMimeType()." ".$file->guessExtension();
      if($msg=$this->isError($file, $maxSize, $mimeTypes)){
        ajaxReturn(RESULT_FAIL, $msg);
        return false;      
      }   

      if (!$file->isValid()) { 
        $errorString = $file->getErrorString();
        $errorCode = $file->getError(); 

        $fileInfo['hasError'] = true;
        $fileInfo['errorString'] = $errorString;
        $fileInfo['errorCode'] = $errorCode;   
      } else { 
        $fileInfo['hasError'] = false;
        $savePath = FCPATH . 'uploads/images/driver';

        if(!is_dir($savePath)) {
          @mkdir($savePath,0777,true);
        }

        if ($file->hasMoved() === false) {                
          $fileInfo['mimeType'] = $file->getMimeType(); 
          $fileInfo['guessExtension'] = $file->guessExtension(); 

          $savedPath = $file->store();

          $fileInfo['savedPath'] = $savedPath;
          $fileInfo['clientName'] = $file->getClientName();
          $fileInfo['name'] = $file->getName(); 
          $fileInfo['clientMimeType'] = $file->getClientMimeType(); 
          $fileInfo['clientExtension'] = $file->getClientExtension(); 

          rename ("".WRITEPATH."uploads/".$savedPath."", "".$savePath."/".$fileInfo['name'].""); 

          //DB에 넣기
          $new_img="/uploads/images/driver/".$fileInfo['name'];
          $newData = ['BANKBOOK_IMAGE'=>$new_img,];

          $this->db->transBegin();         
          $builder = $this->db->table("DRIVER_JOIN_INFO as user");   
          $builder->where('USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $this->db->transCommit();          
            $message='';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }

        }
      }        
    }    
  }






  public function delete_driver_bank_img(): string {
    $request = \Config\Services::request();

    $ACCESS_DATA = esc($request->getPost('access_data'));  
    $ACCESS_DATA=json_encode($ACCESS_DATA,JSON_UNESCAPED_UNICODE);
    $ACCESS_DATA=json_decode($ACCESS_DATA,true);
    
    //Header정리 
    $headers = apache_request_headers();      
    foreach ($headers as $header => $value) {     
      if($header  == 'user_idx') {
        $USER_IDX = $value;
          // print_r($USER_IDX);
      } else {
        $USER_IDX     = $ACCESS_DATA['user_idx'];
      } 
    
      if($header  == 'access_token') {   
        $ACCESS_TOKEN  = $value;
        // print_r($ACCESS_TOKEN);
      } else {
        $ACCESS_TOKEN = $ACCESS_DATA['access_token'];
      } 
    
      if($header  == 'device_id') {
        $DEVICE_ID  = $value;
      } else {
        $DEVICE_ID    = $ACCESS_DATA['device_id'];
      } 
    
      if($header  == 'app_type') {
        $APP_TYPE = $value;
      } else {
        $APP_TYPE     = $ACCESS_DATA['app_type'];
      } 
    
      if($header  == 'refresh_token') {
        $REFRESH_TOKEN = $value;
      } else {
        $REFRESH_TOKEN     = $ACCESS_DATA['refresh_token'];
      } 
    }    
    //Header정리 
    
    if(!$USER_IDX){
      ajaxReturn(RESULT_FAIL,"로그인 후 이용 하세요.");
      return false;  
    }   

    //count  
    $builder = $this->db->table("DRIVER_JOIN_INFO as user");
    $builder->select('user.*');
    $builder->where('user.USER_IDX', $USER_IDX);  
    $total = $builder->countAllResults();
    // echo $total;
    // exit;


    //select
    $builder->select('user.BANKBOOK_IMAGE');
    $builder->where('user.USER_IDX', $USER_IDX);  

    $data['member'] = $builder->get()->getResult('array');   
    // $img = FCPATH .$data['member'][0]['BANKBOOK_IMAGE'];
    $img = ".".$data['member'][0]['BANKBOOK_IMAGE'];
    
    if(@unlink($img)) {   

      //DB에 넣기
          $new_img="/uploads/images/member/icon_no_image.png";
          $newData = ['BANKBOOK_IMAGE'=>$new_img,];
                
          $this->db->transBegin();         
          $builder = $this->db->table("DRIVER_JOIN_INFO as user");   
          $builder->where('USER_IDX', $USER_IDX );
          $result = $builder->update($newData);

          if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $message='';
            ajaxReturn(RESULT_FAIL,$message,"");
            return false;
          } else {
            $this->db->transCommit();          
            $message='';
            ajaxReturn(RESULT_SUCCESS,$message,$newData);
            return false;
          }

      ajaxReturn(RESULT_SUCCESS,"",$data);  
      return false;
    } else {
      ajaxReturn(RESULT_FAIL,"","");  
      return false;
    } 
  }






















  public function mupload(): string
  {
    $files = $this->request->getFileMultiple("files"); // (1)
      
    if ($files == null) {  // (2)
        return View("/upload/mupload", [
            'file_info_array' => []
        ]);
    }

    $file_info_array = [];  // (3)

    foreach ($files as $file) {  // (4)
        $fileInfo = [];
        if ($file != null) {
            if (!$file->isValid()) {
                $errorString = $file->getErrorString();
                $errorCode = $file->getError();
                $fileInfo['hasError'] = true;
                $fileInfo['errorString'] = $errorString;
                $fileInfo['errorCode'] = $errorCode;

            } else {
                $fileInfo['hasError'] = false;
                if ($file->hasMoved() === false) {                    
                    $fileInfo['mimeType'] = $file->getMimeType();
                    $fileInfo['guessExtension'] = $file->guessExtension();

                    $savedPath = $file->store();

                    $fileInfo['savedPath'] = $savedPath;
                    $fileInfo['clientName'] = $file->getClientName();
                    $fileInfo['name'] = $file->getName();
                    $fileInfo['clientMimeType'] = $file->getClientMimeType();
                    $fileInfo['clientExtension'] = $file->getClientExtension();
                  // 
                }
            }
        }

        array_push($file_info_array, $fileInfo);  // (5)
    } // foreach ($files as $file) {

    return View("/upload/mupload_result", [
        'file_info_array' => $file_info_array
    ]);
    
  }

  private function isError(UploadedFile $file, $maxSize, $mimeTypes){
          
    $size=$file->getSize();
    if($size>$maxSize){
        return "업로드 파일 사이즈가 초과 되었습니다.";
    }
    
    $ext=$file->guessExtension();
    $temp=explode("|",$mimeTypes);
    if(!in_array($ext, $temp)){
        return "허가 되지 않은 파일 형식입니다.";
    }
    
    return false;
  }




}
