<?php 
namespace App\Models;
use CodeIgniter\Model;

class Board_fqnaModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'BOARD_FQNA';
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
  protected $allowedFields = ['USER_IDX','TITLE','CONTENT','DISP','VIEW_COUNT','UPDATED_AT','DELETED_AT'];
}



