<?php
  namespace App\Controllers\User;
  
  use App\Controllers\BaseController;
  use CodeIgniter\Exceptions\AlertError;
  
  use App\Models\MemberModel;
  use App\Models\PaymentModel;
  use App\Models\Member_pointModel;
  
  class Test extends BaseController {
    private $db;
  
    public function __construct() {
      $this->db = \Config\Database::connect('default');
    }
    

  public function member() {  
    $request = \Config\Services::request();

    $PHONE   = esc($request->getPost('phone'));
    $PHONE = get_hp($PHONE,1); //-넣음

     //count
     $builder = $this->db->table("MEMBER as user");
     $builder->select('user.*');
     $builder->where('user.PHONE', $PHONE);  
     $total = $builder->countAllResults();
 
     //select
     $builder->select('user.*');
     $builder->where('user.PHONE', $PHONE);  
 
     $data['member'] = $builder->get()->getResult('array');   
    $data['total'] = $total;   

     ajaxReturn(RESULT_SUCCESS,"",$data);  
    return;       
  }



  //Access_token 발생
  public function header() {
    $headers = apache_request_headers();
    foreach ($headers as $header => $value) {     
      //if($header=='User-Agent'){
        echo "$header: $value <br />";
      // }
    }
  
  }




  
}