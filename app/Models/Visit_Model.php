<?php namespace App\Models;
use CodeIgniter\Model;

class Visit_Model extends Model {
  protected $DBGroup = 'default';

  protected $table = 'VISIT';
  protected $primaryKey = 'vi_id';   
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
  protected $allowedFields = ['vi_ip','vi_date','vi_time','vi_referer','vi_agent','vi_agent_string', 'vi_browser','vi_os','vi_device','UPDATED_AT','DELETED_AT'];
} 

