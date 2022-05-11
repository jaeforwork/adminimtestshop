<?php namespace App\Models;
use CodeIgniter\Model;

class Sms_writeModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'SMS_WRITE';
  protected $primaryKey = 'wr_no';   
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
  protected $allowedFields = ['wr_renum','wr_renum','wr_reply','wr_message','wr_booking','wr_total','wr_re_total','wr_success','wr_failure','wr_memo','msg_types','UPDATED_AT','DELETED_AT'];
}