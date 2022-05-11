<?php namespace App\Models;
use CodeIgniter\Model;

class Chat_single_report_log_detailModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CHAT_SINGLE_REPORT_LOG_DETAIL';
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
  protected $allowedFields = ['REPORT_IDX','USER_IDX','IP','TYPE','REPORT','UPDATED_AT','DELETED_AT'];
}