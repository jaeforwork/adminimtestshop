<?php namespace App\Models;
use CodeIgniter\Model;

class Chat_singleModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CHAT_SINGLE';
  protected $primaryKey = 'CHAT_IDX';
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
  protected $allowedFields = ['ROOM_IDX','USER_IDX','TYPE','MESSAGE','IS_VIEW','MILISEC','UPDATED_AT','DELETED_AT'];
}