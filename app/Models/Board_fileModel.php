<?php 
namespace App\Models;
use CodeIgniter\Model;

class Board_fileModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'BOARD_FILE';
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
  protected $allowedFields = ['BOARD_IDX','USER_IDX','STATUS','UPDATED_AT'];
}



