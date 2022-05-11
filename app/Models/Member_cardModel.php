<?php namespace App\Models;
use CodeIgniter\Model;

class Member_cardModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_CARD';
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
  protected $useSoftDeletes = true; //false
  protected $deletedField  = 'DELETED_AT';
  protected $allowedFields = ['USER_IDX','CARD_NAME','TYPE','CARD_NUM','MONTH','YEAR','CVS','OWNER_NUM','PAY_KEY','DISP','STATUS','UPDATED_AT','DELETED_AT'];
}