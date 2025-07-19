<?php namespace Modules\Admin\Models;

use CodeIgniter\Model;

class SatkerModel extends Model
{
	protected $table      = 'm_satker';
	protected $primaryKey = 'satkerid';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;


	protected $allowedFields = [
		'satkerid', 'balaiid', 'satker'
	];

	protected $useTimestamps = false;



	protected $validationRules = [];


	protected $validationMessages = [];

	protected $skipValidation = false;

	protected $beforeInsert = ['beforeInsert'];


	protected function beforeInsert(array $data){
		return $data;
	  }
	
    public function getSatkerByBalai($balai)
    {
        return $this->db->table($this->table)->where('balaiid',$balai)->get()->getResultArray();
    }
		

}