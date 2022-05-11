<?php 
namespace App\Models;
use CodeIgniter\Model;

class App_push_messagesModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'APP_PUSH_MESSAGES';
  protected $primaryKey = 'PUSH_IDX';   
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  
  // protected $validationRules = [
  //   "pet_name"    => "required", //|min_length[1]|max_length[255]
  //   "pet_type"    => "required",
  //   "character"   => "required",
  // ];

  // protected $validationMessages = [
  //     "pet_name" => [
  //         "required" => "user_idx is required",
  //     ],
  //     "pet_type" => [
  //         "required" => "nick_name is required",
  //     ],
  //     "character" => [
  //         "required" => "Minimum length of Name should be 3",
  //     ],
  // ];

  // protected $skipValidation     = false;  
  protected $returnType   = 'array';
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = false; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['TR_IDX','USER_IDX','PID','APP_TYPE','DEVICE_ID','PUSH_TOKEN','MESSAGE','DELIVERY','STATUS','ERROR_TEXT','UPDATED_AT'];
}



