<?php namespace App\Models;
use CodeIgniter\Model;

class No_access_ipModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'NO_ACCESS_IP';
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
  protected $allowedFields = ['USER_IDX','STATUS','IP','MEMO','UPDATED_AT','DELETED_AT'];
}