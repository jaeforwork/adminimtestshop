<?php namespace App\Entities;
use CodeIgniter\Entity\Entity;
use App\Models\User_Entity_Model;

class User_Entity extends Entity {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::connect('default');
  }


	protected $datamap = [];

  // protected $datamap = [ 
  //     'username' => 'username',
  //     'password' => 'password',
  //     'email' => 'email'
  // ]; //php name => DB field name

  
	protected $dates   = [
		'CREATED_AT',
		'UPDATED_AT',
		'DELETED_AT',
	];
  
	protected $casts   = [];

//   protected $casts = [
//     'is_banned' => 'boolean',
//     'is_banned_nullable' => '?boolean',
// ],


//   protected $attributes = [
//     'id'         => null,
//     'name'       => null, // Represents a username
//     'email'      => null,
//     'password'   => null,
//     'created_at' => null,
//     'updated_at' => null,
// ];
    

  public function db_count($data=1) {
    //count
    $builder = $this->db->table("USERS as users");
    $builder->select('users.*');
    //$builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where($data);  
    $total = $builder->countAllResults();    
    
    return $total;
  }

  public function db_select($select="*",$data=1) {
    //count
    $builder = $this->db->table("USERS");
    $builder->select($select);
   // $builder->join('DRIVER_JOIN_INFO as join_info', 'user.USER_IDX = join_info.USER_IDX', "left inner"); // added left here
    $builder->where($data);  
    $result = $builder->get()->getResult('array');   
    return $result;
  }

  public function db_insert($data) {        
   // $insertData['EMAIL']    = $this->gender($data['EMAIL']);
    $insertData['EMAIL']    = $data['EMAIL'];
    $insertData['PASSWORD'] = $this->setLoginPw($data['PASSWORD']);
    $insertData['USERNAME'] = $this->setName($data['USERNAME']);

    //입력 오류 처리
    if($insertData['EMAIL']=='' || $insertData['EMAIL'] == null) {
      $result['SUCCESS'] = 'N';
      $result['MESSAGE'] = '이메일이 비었습니다.';
      return $result;
    }
    //입력 오류 처리

    $EntityModel = new UserEntityModel(); 

    $EntityModel->transBegin();
    $inserted_IDX=$EntityModel->insert($insertData);

    if($EntityModel->transStatus() === FALSE) {
      $EntityModel->transRollback();
      $result['SUCCESS'] = 'N';
      $result['MESSAGE'] = '가입처리 중 오류가 발생했습니다.';
      return $result;
    } else {
      $EntityModel->transCommit();
      //$tranResult='Y'; //결과값을 변수로 저장
      $EntityResult = $EntityModel->asArray()->find($inserted_IDX); //배열로 받는다.

      $EntityResult['SUCCESS'] = 'Y';
      $EntityResult['MESSAGE'] = '';
      return $EntityResult;
    }
  }



  public function db_update($idx,$data) {        
    // $insertData['EMAIL']    = $this->gender($data['EMAIL']);
    $IDX          = $idx;
    $USERNAME     = esc($data['USERNAME']);
    $EMAIL        = esc($data['EMAIL']);
    $PASSWORD     = esc($data['PASSWORD']);




    

    $UPDATED_AT   = date('Y-m-d H:i:s');


    //입력 오류 처리
    if($IDX=='' || $IDX == null) {
      $result['SUCCESS'] = 'N';
      $result['MESSAGE'] = 'IDX 비었습니다.';
      return $result;
    }
    //입력 오류 처리

    $EntityModel = new UserEntityModel(); 

    $newData=array(
      'USERNAME'    => $USERNAME,
      'EMAIL'       => $EMAIL,
      'PASSWORD'    => $PASSWORD,
      'UPDATED_AT'  => $UPDATED_AT			
    );

   if($IDX !='') {
     $this->db->transBegin();
     $builder = $this->db->table("USER as user");  
     $builder->where('user.IDX', $IDX ); 
     $result = $builder->update($newData);
 
     if ($this->db->transStatus() === FALSE) {
       $this->db->transRollback();
       $result['SUCCESS'] = 'N';
       $result['MESSAGE'] = '오류가 발생했습니다.';
       return $result;
     } else {
       $this->db->transCommit();          
       //$tranResult='Y'; //결과값을 변수로 저장
       $EntityResult = $EntityModel->asArray()->find($IDX); //배열로 받는다.       
       $EntityResult['SUCCESS'] = 'Y';
       $EntityResult['MESSAGE'] = '';
       return $EntityResult;
      }
    }
  }
 
 

  public function db_delete($idx,$data) {        
    $IDX         = $idx;
    $DELETED_AT  = date('Y-m-d H:i:s');

    //입력 오류 처리
    if($IDX=='' || $IDX == null) {
      $result['SUCCESS'] = 'N';
      $result['MESSAGE'] = 'IDX 비었습니다.';
      return $result;
    }
     //입력 오류 처리
    $newData=array(
      'STATUS'  => 'N',
      'DELETED_AT'   => $DELETED_AT		
    );

    $returnData=array(
      'IDX'     =>  $IDX,
      'STATUS'  => 'N',
      'DELETED_AT'   => $DELETED_AT		
    );

    if($IDX !='') {
      $this->db->transBegin();
      $builder = $this->db->table("USER as user");  
      $builder->where('user.IDX', $IDX ); 
      $result = $builder->update($newData);
  
      if ($this->db->transStatus() === FALSE) {
        $this->db->transRollback();
        $result['SUCCESS'] = 'N';
        $result['MESSAGE'] = '가입처리 중 오류가 발생했습니다.';
        return $result;
      } else {
        $this->db->transCommit();          
        //$tranResult='Y'; //결과값을 변수로 저장     
        $EntityResult['data'] = $returnData;   
        $EntityResult['SUCCESS'] = 'Y';
        $EntityResult['MESSAGE'] = '';
        return $EntityResult;
      }
    }
  }
 
 
// private //
  private function setLoginPw($value) {
    //$this->attributes['login_pw'] = password_hash($login_pw, PASSWORD_BCRYPT); 
    $result= password_hash($value, PASSWORD_BCRYPT); 
    return $result;
  }

  private function setName($value) {       
    $result="username";
    return $result;
  }

  private function getAge($value) { 
    if ($value >= 20){
      return "adult";
    }
    if ($value >= 10){
      return "student";
    }
    if ($value >= 5){
      return "kids";
    }
  return "baby";
  }
    
  private function gender($value){ 
    if ($value == 'M'){
      return "Male";
    } else if ($value == 'F'){
      return "Female";
    }
    return ;
  }    
    
  private function getFullData(){ 
    return $this->attributes['USERNAME'] . " " . $this->attributes['EMAIL'] . " "; 
  }

  // private function checkUser($origin_password): bool
  // {
  //     $hashed_password = $this->attributes['password']; 
  //     return password_verify($origin_password, $hashed_password);
  // }



  private function setPasswd(string $pwd){
    $this->attributes["passwd"]=password_hash($pwd, PASSWORD_BCRYPT);
    return $this;
}



    



}