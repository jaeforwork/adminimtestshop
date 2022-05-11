<?php namespace App\Models;
use CodeIgniter\Model;

class Chat_room_multiModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CHAT_ROOM_MULTI';
  protected $primaryKey = 'ROOM_IDX';
  protected $useAutoIncrement = true;  
  protected $useTimestamps = true;
  protected $allowCallbacks = true;
  // protected $validationRules    = [];
  // protected $validationMessages = [];
  // protected $skipValidation     = false;  
  protected $returnType   = 'array';
  protected $createdField = 'CREATED_AT';
  protected $updatedField = 'UPDATED_AT';
  protected $useSoftDeletes = true; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['TR_IDX','OWNER_IDX','LAST_AT','STATUS','LAST_MESSAGE','UPDATED_AT','DELETED_AT'];
}
