<?php 
namespace App\Models;
use CodeIgniter\Model;

class Member_petModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_PET';
  protected $primaryKey = 'PET_IDX';   
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  // protected $skipValidation     = false;  
  protected $returnType   = 'array';
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = false; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['USER_IDX','PET_NAME','me_send_datetime','me_read_datetime','me_memo','me_send_id','me_type','me_send_ip','UPDATED_AT','DELETED_AT'];
}