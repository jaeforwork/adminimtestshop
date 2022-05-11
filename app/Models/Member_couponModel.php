<?php 
namespace App\Models;
use CodeIgniter\Model;

class Member_couponModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER_COUPON';
  protected $primaryKey = 'CP_IDX';   
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
  protected $allowedFields = ['CP_ID','CP_SUBJECT','CP_method','CP_ONLY_USE','USER_IDX','CP_START','CP_END','CP_PRICE','CP_TYPE','CP_trunc','CP_minimum','CP_maximum','ADMIN_IDX','END_CREATED_AT','IS_USED','UPDATED_AT','DELETED_AT'];
}