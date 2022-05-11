<?php namespace App\Models;
use CodeIgniter\Model;

class Log_admin_page_viewModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'LOG_ADMIN_PAGE_VIEW';
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
  protected $allowedFields = ['URL','USER_IDX','STATUS','USER_LEVEL','VI_REFERER','IP','UPDATED_AT','DELETED_AT'];
}