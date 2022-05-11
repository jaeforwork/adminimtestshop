<?php namespace App\Models;
use CodeIgniter\Model;

class Visit_sumModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'VISIT_SUM';
  protected $primaryKey = 'VS_DATE';   
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
  protected $allowedFields = ['VS_COUNT','UPDATED_AT','DELETED_AT'];
} 

