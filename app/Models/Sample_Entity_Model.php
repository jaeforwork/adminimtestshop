<?php 
namespace App\Models;
use CodeIgniter\Model;

class Sample_Entity_Model extends Model {
  protected $DBGroup = 'default';

  protected $table = 'USERS';
  protected $primaryKey = 'IDX';
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  protected $validationRules = [
    'EMAIL'  => 'required|valid_email'       
  ]; 
  protected $validationMessages = [
    "EMAIL" => [
      "required" => "EMAIL is required",
    ],
  ];  
  protected $skipValidation     = true; //false
  protected $returnType   = "App\Entities\UserEntity"; 
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = true; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['USERNAME', 'EMAIL', 'PASSWORD','UPDATED_AT','DELETED_AT'];
}





