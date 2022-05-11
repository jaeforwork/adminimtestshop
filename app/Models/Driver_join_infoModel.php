<?php namespace App\Models;
use CodeIgniter\Model;

class Driver_join_infoModel extends Model {  
  protected $DBGroup = 'default';

  protected $table = 'DRIVER_JOIN_INFO';
  protected $primaryKey = 'INFO_IDX';
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
  protected $allowedFields = ['USER_IDX','NAME','GENDER','STATUS','BIRTH','EMAIL',  'ADDR1','ADDR2','CAR_NUM','MYCAR','CAR_TYPE','CAREER','ALLERGY','ATP_NUM','IBRC_NUM',  'IDCARD_IMAGE','BANKBOOK_IMAGE','COMMENT','FEE_TOTAL','FEE_CURRENT','EXCHANGE_TOTAL','CCTV_IDX','BANK_ACCOUNT','DRIVER_SECURITY','DRIVER_NUM','DRIVER_LICENCE_IMAGE','WITHDREWAL'];
}