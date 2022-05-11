<?php namespace App\Models;
use CodeIgniter\Model;

class Payment_linkModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'PAYMENT_LINK';
  protected $primaryKey = 'PAY_LINK_IDX';   
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
  protected $allowedFields = ['USER_IDX','TR_IDX','FEE','URL','STATUS','ENDED_AT','UPDATED_AT','DELETED_AT'];
}
  