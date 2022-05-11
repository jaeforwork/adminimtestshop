<?php namespace App\Models;
use CodeIgniter\Model;

class Member_favor_transportModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMEBER_FAVOR_TRANSPORT';
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
  protected $allowedFields = ['TR_IDX','USER_IDX','UPDATED_AT','DELETED_AT'];
}
