<?php
namespace App\Libraries;

class ValidChecker {  
    private $db;
  
  function __construct() {
    $this->db = \Config\Database::connect();
  }
  
  function index() {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();        
  } 
    
  function array_test() {
    $pet="70,71,72,73";
    $tmp = explode(',',$pet); 
    $total=count($tmp); 

    $data['transport'][0]=['0','1','pet','3','4','5','6','7'];
    $data['transport'][0]['PETLIST'] = array();
    $USER_IDX = 13;

    $builder = $this->db->table("PET as pet");

    $petlist=array();
 
    $builder = $this->db->table("PET as pet");

    for( $i=0;$i<count($tmp);$i++ ) {
      $PET_IDX = trim($tmp[$i]);

      //select
      $builder->select('pet.IDX AS PET_IDX, pet.USER_IDX, pet.PET_NAME, pet.STATUS, pet.IMAGE, pet.PET_TYPE, pet.CHARACTER, pet.COMMENT');
       // $builder->where('pet.USER_IDX', $USER_IDX);  
      $builder->where('pet.IDX', $PET_IDX);  
      $builder->where('pet.USER_IDX', $USER_IDX);  
   
      $petdata = $builder->get()->getResult('array');   
 
       array_push($petlist, $petdata[0]);
      
      $petlist[0]['total'] = $total;   

      array_push($data['transport'][0]['PETLIST'], $petlist[0]);
 
      return $data;
    }
  }









}

