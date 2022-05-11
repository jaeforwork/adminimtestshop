<?php namespace App\Models;
use CodeIgniter\Model;

class Log_cctv_Model extends Model {
  protected $DBGroup = 'default';

  protected $table = 'LOG_CCTV';
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
  protected $allowedFields = ['USER_IDX','STATUS','DRIVER_IDX','RTSB','URL','MEMO','UPDATED_AT','DELETED_AT'];
}