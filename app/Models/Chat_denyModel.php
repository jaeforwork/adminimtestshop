<?php 
namespace App\Models;
use CodeIgniter\Model;

class Chat_denyModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CHAT_DENY';
  protected $primaryKey = 'IDX';   
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  // protected $skipValidation     = false;  
  protected $returnType   = 'array';
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = true; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['USER_IDX','ADMIN_IDX','UPDATED_AT'];
}



