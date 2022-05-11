<?php namespace App\Models;
use CodeIgniter\Model;

class CctvModel extends Model
{
   protected $DBGroup = 'default';

   protected $table = 'CCTV_RECORD_URL';
   protected $primaryKey = 'IDX';
   protected $returnType = 'array';
   protected $useTimestamps = true;
   protected $allowedFields = ['TR_IDX','URL','PLAYBACK'];
   protected $createdField = 'CREATED_AT';
   protected $updatedField = 'UPDATED_AT';
   protected $allowCallbacks = true;

}




