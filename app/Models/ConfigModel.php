<?php namespace App\Models;
use CodeIgniter\Model;

class ConfigModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CONFIG';
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
  protected $allowedFields = ['KAKO_ID','KAKO_TOKEN','KAKO_TOKEN_UPDATED_AT','MEMO','UPDATED_AT','DELETED_AT'];
}



