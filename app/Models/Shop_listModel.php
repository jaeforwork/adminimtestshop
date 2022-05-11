<?php namespace App\Models;
use CodeIgniter\Model;

class Shop_listModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'SHOP_LIST';
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
  protected $allowedFields = ['SHOP_NAME','ADD1','ADD2','ADD3','LOC','PHONE','KIND1','KIND2','MEMO1','MEMO2','UPDATED_AT','DELETED_AT'];
}