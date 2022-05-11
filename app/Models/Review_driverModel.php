<?php namespace App\Models;
use CodeIgniter\Model;

class Review_driverModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'REVIEW_DRIVER';
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
  protected $allowedFields = ['USER_IDX','DRIVER_IDX','TR_IDX','STAR','COMMENT','UPDATED_AT','DELETED_AT'];
}