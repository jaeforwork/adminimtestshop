<?php namespace App\Models;
use CodeIgniter\Model;

class Log_db_error_Model extends Model {
  protected $DBGroup = 'default';

  protected $table = 'LOG_DB_ERROR';
  protected $primaryKey = 'IDX';
  protected $useAutoIncrement = true;  
  protected $useTimestamps  = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  // protected $skipValidation = true; //false
  protected $returnType     = 'array';
  protected $createdField   = 'CREATED_AT';
  protected $updatedField   = 'UPDATED_AT';
  protected $useSoftDeletes = false; //false
  protected $deletedField   = 'DELETED_AT';
  protected $allowedFields  = ['TABLE_NAME','URL','ERROR','IP','UPDATED_AT','DELETED_AT'];
}



