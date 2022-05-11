<?php namespace App\Models;
use CodeIgniter\Model;

class Member_configModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_CONFIG';
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
  protected $allowedFields = ['USER_IDX','AUTO_LOGIN','PROC_BG','NOTI_TIME','LOC_GARAGE','RESERVE','CHAT_NOTI','CONTROL_NOTI','UPDATED_AT','DELETED_AT'];
}