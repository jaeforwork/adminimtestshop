<?php namespace App\Models;
use CodeIgniter\Model;

class Log_locationModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'LOG_LOCATION';
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
  protected $allowedFields = ['TR_IDX','USER_IDX','DRIVER_IDX','STATUS','DEVICE_STATUS','LOC','METERS','FEE','MINUTES','UPDATED_AT','DELETED_AT'];
}



