<?php namespace App\Models;
use CodeIgniter\Model;

class Member_pointModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_POINT';
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
  protected $useSoftDeletes = false; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['TR_IDX','USER_IDX','ADMIN_IDX','TYPE','UPO_GET_POINT','UPO_USE_POINT','STATUS','UPO_EXPIRE_DATE','UPO_CONTENT','UPDATED_AT'];
}
