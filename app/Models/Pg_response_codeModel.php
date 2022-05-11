<?php namespace App\Models;
use CodeIgniter\Model;

class Pg_response_codeModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'PG_RESPONSE_CODE';
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
  protected $allowedFields = ['TYPE','CODE','MSG','UPDATED_AT','DELETED_AT'];
}