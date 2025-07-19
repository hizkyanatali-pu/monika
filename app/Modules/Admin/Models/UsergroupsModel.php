<?php namespace Modules\Admin\Models;

use CodeIgniter\Model;

class UsergroupsModel extends Model
{
	protected $table      = 'ku_group';
	//protected $primaryKey = 'uid';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;


	protected $allowedFields = [
		'group_id', 'nama', 'keterangan', 'aktif','urut'
	];

	protected $useTimestamps = false;



	protected $validationRules = [];


	protected $validationMessages = [];

	protected $skipValidation = false;

	protected $beforeInsert = ['beforeInsert'];


	protected function beforeInsert(array $data){
		$data['data']['aktif'] = 1;
		return $data;
	  }
	


	public function checkGuid($uid){
		return $this->table($this->table)
                        ->where('group_id', $uid)
                        ->get()
                        ->getRowArray();
	}

	public function getUserGroups($guid = false)
    {
        if($guid === false){
            return $this->table($this->table)
                        ->get()
                        ->getResultArray();
        } else {
            return $this->table($this->table)
                        ->where('group_id', $guid)
                        ->get()
                        ->getRowArray();
        }   
	} 
	
    public function update_user($data, $id)
    {
        return $this->db->table($this->table)->update($data, ['uid' => $id]);
    }

}