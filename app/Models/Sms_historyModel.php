<?php namespace App\Models;
use CodeIgniter\Model;

class Sms_historyModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'SMS_HISTORY';
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
  protected $allowedFields = ['SH_IDX','TYPE','USER_IDX','USER_ID','SH_NAME','SH_PHONE','SH_FLAG','SH_CODE','SH_MEMO','SH_LOG','UPDATED_AT','DELETED_AT'];
}