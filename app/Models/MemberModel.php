<?php namespace App\Models;
use CodeIgniter\Model;

class MemberModel extends Model {
  protected $DBGroup = 'default';

  protected $table = 'MEMBER';
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
  protected $allowedFields = ['USER_IDX','USER_ID','PHONE','PASSWD','EMAIL','USER_TYPE','NICK_NAME','AGE','POINT','STATUS','IMAGE','DEVICE_ID','APP_TYPE','ACCESS_TOKEN','TOKEN_EXPIRED_DATE','PUSH_TOKEN','REFRESH_TOKEN','REFRESH_TOKEN_EXPIRED_DATE','JOIN_IP','LOGIN_IP','JOIN_DATE','LOGIN_DATE','AGREED_TERM_DATE','AGREED_PERSON_DATE','LOGIN_TYPE','APPROVED_AT','APPROVED_BY','UPDATED_AT','DELETED_AT'];
}

