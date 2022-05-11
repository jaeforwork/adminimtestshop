<?php 
namespace App\Models;
use CodeIgniter\Model;

class Member_memoModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_MEMO';
  protected $primaryKey = 'me_id';   
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
  protected $allowedFields = ['me_recv_mb_id','me_send_mb_id','me_send_datetime','me_read_datetime','me_memo','me_send_id','me_type','me_send_ip','UPDATED_AT','DELETED_AT'];
}