<?php namespace Modules\Admin\Models;

use CodeIgniter\Model;


class UserModel extends Model
{
	protected $table      = 'ku_user';
	//protected $primaryKey = 'uid';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;


	protected $allowedFields = [
		'uid', 'idpengguna', 'balaiid', 'satkerid', 'sandi', 'email',
		'nama', 'nip', 'telpon', 'gambar','aktif','aksi','in_dt','in_user','group_id','idkelompok','user_pk'
	];

	protected $useTimestamps = false;

	protected $validationRules = [];

	protected $dynamicRules = [
		'updateAccount' => [
			'nama'		=> 'required|alpha_space|min_length[2]',
			'telpon'	=> 'required|is_natural',
			'email'		=> 'required|valid_email|is_unique[ku_user.email,uid,{uid}]',
		]
	];

	protected $validationMessages = [];

	protected $skipValidation = false;

	protected $beforeInsert = ['beforeInsert'];
	protected $beforeUpdate = ['beforeUpdate'];

	protected function beforeInsert(array $data){

		$data = $this->hashPassword($data);
		$data['data']['in_dt'] = date('Y-m-d H:i:s');
		$data['data']['in_user'] = $_SESSION['userData']['uid'];//$this->session->get('userData.uid');
		$data['data']['aktif'] = 1;
		$data['data']['idkelompok'] =  $data['data']['group_id'];
		$data = $this->saveUserGroup_MM($data, true);
		return $data;
	  }
	
	  protected function beforeUpdate(array $data){
		if (isset($data['data']['group_id'])) $data['data']['idkelompok'] =  $data['data']['group_id'];
		if (isset($data['data']['sandi'])) $data = $this->hashPassword($data);
		if (isset($data['data']['group_id'])) $data = $this->saveUserGroup_MM($data, false);
		$data['data']['in_dt'] = date('Y-m-d H:i:s');

		return $data;
	  }
	

	public function saveUserGroup_MM(array $data, $new = false){
		$check = $this->db->table('ku_user_group')->where('uid',$data['data']['idpengguna'])
				->where('group_id',$data['data']['group_id'])
				->get()->getRowArray();
	
		if(!$check) {
			if($new){
				$this->db->table('ku_user_group')
				->insert([
					'group_id' => $data['data']['group_id'],
					'uid'=>$data['data']['idpengguna']
				]);
			} else {
				$this->db->table('ku_user_group')
				->where('uid',$data['data']['idpengguna'])
				->set([
					'group_id' => $data['data']['group_id'],
				])->update();
			}
		}else {
			$this->db->table('ku_user_group')
			->where('uid',$data['data']['idpengguna'])
			->set([
				'group_id' => $data['data']['group_id'],
			])->update();
		}
		unset($data['data']['group_id']);		
		return $data;
	}
    //--------------------------------------------------------------------
	// public function setValidationRules(string $rules)
	// {
	// 	$this->validationRules = $this->dynamicRules[$rules];
	// }

    //--------------------------------------------------------------------

    /**
     * Hashes the password after field validation and before insert/update
     */
	protected function hashPassword(array $data)
	{

		$config         = new \Config\Encryption();
        $config->key    = 'aBigsecret_ofAtleast32Characters';
        $config->driver = 'OpenSSL';
        
        $encrypter = \Config\Services::encrypter($config);

		if (! isset($data['data']['sandi'])) return $data;
		if ($data['data']['sandi'] ==='') return $data;

		// dd($data['data']['sandi']);

		$data['data']['sandi'] = base64_encode($encrypter->encrypt($data['data']['sandi']));//password_hash($data['data']['sandi'], PASSWORD_DEFAULT);
		//unset($data['data']['sandi']);

		return $data;
	}

	public function checkUid($uid){
		return $this->table($this->table)
                        ->where('uid', $uid)
                        ->get()
                        ->getRowArray();
	}

	public function getUsers($uid = false)
    {
        if($uid === false){
			$a = $this->table($this->table)->select('ku_user.*,ku_user_group.group_id')
			->join('ku_user_group', 'ku_user.uid = ku_user_group.uid', 'inner')
			->join('ku_group', 'ku_group.group_id = ku_user_group.group_id', 'inner')
			->get()
			->getResultArray();

            return $this->table($this->table)
                        ->get()
                        ->getResultArray();
        } else {

			return $this->table($this->table)->select('ku_user.*,ku_user_group.group_id')
						->join('ku_user_group', 'ku_user.uid = ku_user_group.uid', 'left')
						->join('ku_group', 'ku_group.group_id = ku_user_group.group_id', 'left')
                        ->where('ku_user.uid', $uid)
                        ->get()
                        ->getRowArray();
        }   
	} 
	
    public function update_user($data, $id)
    {
        return $this->db->table($this->table)->update($data, ['uid' => $id]);
    }

}