<?php namespace App\Models;
use CodeIgniter\Model;

class TransportModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'TRANSPORT';
  protected $primaryKey = 'TR_IDX';   
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
  protected $allowedFields = ['CALL_TYPE','STATUS','USER_IDX','DRIVER_IDX','DRIVER_START','ADDR_START',  'ADDR_DEST','ROUND_TRIP','E_DISTANCE','E_FEE','E_TIME','E_ARRIVE_TIME','PET_LIST','USER_RIDE',  'DISTANCE','TIME','FEE','FEE_PAY','D_FEE','R_FEE','P_FEE','A_FEE','DC_FEE','O_FEE',
  'USER_MEMO','MEMO','RESERVE_TIME','C_IDX','ARRIVE_TIME','CCTV_URL','IS_USER_SHOW','UPDATED_AT','DELETED_AT'];
} 

