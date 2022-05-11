<?php 
namespace App\Models;
use CodeIgniter\Model;

class Cctv_record_urlModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'CCTV_RECORD_URL';
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
  protected $allowedFields = ['TR_IDX','URL','PLAYBACK','EXPIRED_AT','UPDATED_AT'];
}



