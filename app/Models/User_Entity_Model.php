<?php namespace App\Models;
use CodeIgniter\Model;

class User_Entity_Model extends Model {
  protected $DBGroup = 'default';

  protected $table = 'USERS';
  protected $primaryKey = 'IDX';
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  // protected $skipValidation     = false; 
  protected $validationRules = [
    'EMAIL'  => 'required|valid_email'         // works if removed, was fine in RC
  ];
  /*
  protected $validationRules = [
    'username'  => 'required|alpha_dash|min_length[3]|is_unique[USERS.username]',
    'email'  => 'required|valid_email|is_unique[USERS.email]',
    'password'      => 'required|min_length[8]',
    'passwordrepeat'      => 'required|min_length[8]|matches[password]'             // works if removed, was fine in RC
  ];
*/
  protected $returnType = "App\Entities\User_Entity"; 
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = false; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['USERNAME', 'EMAIL', 'PASSWORD',];
}





