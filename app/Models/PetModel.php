<?php namespace App\Models;
use CodeIgniter\Model;

class PetModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'PET';
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
  protected $allowedFields = ['USER_IDX','PET_NAME','PET_TYPE','PET_KIND','CHARACTER','COMMENT','STATUS','IMAGE','UPDATED_AT','DELETED_AT'];
}
