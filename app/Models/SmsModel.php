<?php namespace App\Models;
use CodeIgniter\Model;

class SmsModel extends Model
{
   protected $DBGroup = 'default';

   protected $table = 'SMS_HISTORY';
   protected $primaryKey = 'S_IDX';
   protected $returnType = 'array';
   protected $useTimestamps = true;
   protected $allowedFields = ['SH_IDX','TYPE','SH_PHONE','SH_MEMO'];
   protected $createdField = 'CREATED_AT';
   protected $updatedField = 'UPDATED_AT';
   protected $allowCallbacks = true;

}

