<?php namespace App\Models;
use CodeIgniter\Model;

class Chat_room_single_removeModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CHAT_ROOM_SINGLE_REMOVE';
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
  protected $allowedFields = ['ROOM_KEY','OWNER_IDX','USER_IDX','STATUS','LAST_MESSAGE','OWNER_LASTDATE','USER_LASTDATE','OWNER_UNCONFIRMED','USER_UNCONFIRMED','REMOVED_AT','UPDATED_AT'];
}
