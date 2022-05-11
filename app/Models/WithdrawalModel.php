<?php namespace App\Models;
use CodeIgniter\Model;

class WithdrawalModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'WITHDRAWAL';
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
  protected $allowedFields = ['USER_IDX','BANK','ACCOUNT','ACCOUNT_NAME','PHONE','AMOUNT','AMOUNT_EX','FEE_PLATFORM','TAX','STATUS','ADMIN_IDX','COMPLETED_AT','REJECT_MSG','UPDATED_AT','DELETED_AT'];
} 

