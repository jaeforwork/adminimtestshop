<?php namespace App\Models;
use CodeIgniter\Model;

class PaymentModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'PAYMENT';
  protected $primaryKey = 'PAY_IDX';   
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
  protected $allowedFields = ['USER_IDX','TR_ID','TR_IDX','METHOD_IDX', 'PRICE','NET_PRICE','FEE_PG', 'STATUS', 'PG_IDX', 'REF_PGIDX', 'RBANK_NAME', 'RBANK_ACCOUNT', 'PAYDATE', 'PG_DATA', 'PAY_TYPE','UPDATED_AT','DELETED_AT'];
}
  