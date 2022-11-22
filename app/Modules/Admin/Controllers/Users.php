<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\UserModel;
use Modules\Admin\Models\UsergroupsModel;
use Modules\Admin\Models\BalaiModel;
use Modules\Admin\Models\SatkerModel;

class Users extends \App\Controllers\BaseController
{
    public function __construct() {

        $this->users      = new UserModel();
        $this->usergroups = new UsergroupsModel();
        $this->balai      = new BalaiModel();
        $this->satker     = new SatkerModel();
        
    }
    public function index(){
        

        $data = array(
            'title'=> 'Dashboard',
            // 'users' => $this->users->paginate(10),
            'users' => $this->users->where('user_pk','1')->get()->getResultArray(),
            'pager' => $this->users->pager
        );
       
        return view('Modules\Admin\Views\Users\Users', $data);
    }

    public function edit($slug = null){

        $data = array(
            'title'=> 'Edit User',
            'user'=> $this->users->getUsers($slug),
            'usergroups'=> $this->usergroups->getUserGroups(),
            'balai'=> $this->balai->get()->getResultArray(),
            'satker'     => $this->satker->get()->getResultArray()
        );
        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the users item: ' . $slug);
        }

        return view('Modules\Admin\Views\Users\UsersEdit', $data);
    }

    public function ChangePassword($slug = null){

        $data = array(
            'title'=> 'Change Password User',
            'user'=> $this->users->getUsers($slug),
            'usergroups'=> $this->usergroups->getUserGroups(),
            'balai'=> $this->balai->get()->getResultArray(),
            'satker'     => $this->satker->get()->getResultArray()
        );
        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the users item: ' . $slug);
        }

        return view('Modules\Admin\Views\Users\UsersChangePassword', $data);
    }

    public function update(){
        $rules = [
            'nama' 				=> 'required|min_length[2]',
            'idpengguna' 		=> 'required|min_length[2]|is_unique[ku_user.idpengguna,uid,{uid}]',
			'email' 			=> 'required|valid_email|is_unique[ku_user.email,uid,{uid}]',
            'balaiid'           => 'required'
		];
        // if($this->request->getVar('sandi')){
        //     $rules['sandi'] = 'required|min_length[5]';
        //     $rules['sandi_konfirm'] = 'required|matches[sandi]';
        // }
		if (! $this->validate($rules)) {
			return redirect()->to('edit/'.$this->request->getVar('uid'))
				->withInput()
				->with('errors', $this->validator->getErrors());
        }
 
        $saved = $this->users->where('uid',  $this->request->getVar('uid'))->set([
            'uid'               => $this->request->getVar('uid'),
            'nama' 				=> $this->request->getVar('nama'),
            'idpengguna' 		=> $this->request->getVar('idpengguna'),
            'email' 			=> $this->request->getVar('email'),
            // 'sandi'			    => $this->request->getVar('sandi'),
            'telpon'            => $this->request->getVar('telpon'),
            'nip'               => $this->request->getVar('nip'),
            'balaiid'           => $this->request->getVar('balaiid'),
            'satkerid'           => $this->request->getVar('satkerid'),
            'group_id'           => $this->request->getVar('group_id'),
        ])->update();

        if($saved)
        {
            return redirect()->to('/users')->with('success', 'Updated user successfully');
        }
    
    }

    public function UpdateChangePassword(){
        // $rules = [
        //     'nama' 				=> 'required|min_length[2]',
        //     'idpengguna' 		=> 'required|min_length[2]|is_unique[ku_user.idpengguna,uid,{uid}]',
		// 	'email' 			=> 'required|valid_email|is_unique[ku_user.email,uid,{uid}]',
        //     'balaiid'           => 'required'
		// ];
        if($this->request->getVar('sandi')){
            $rules['sandi'] = 'required|min_length[5]';
            $rules['sandi_konfirm'] = 'required|matches[sandi]';
        }
		if (! $this->validate($rules)) {
			return redirect()->to('changepassword/'.$this->request->getVar('uid'))
				->withInput()
				->with('errors', $this->validator->getErrors());
        }
 
        $saved = $this->users->where('uid',  $this->request->getVar('uid'))->set([
            // 'uid'               => $this->request->getVar('uid'),
            // 'nama' 				=> $this->request->getVar('nama'),
            // 'idpengguna' 		=> $this->request->getVar('idpengguna'),
            // 'email' 			=> $this->request->getVar('email'),
            'sandi'			    => $this->request->getVar('sandi'),
            // 'telpon'            => $this->request->getVar('telpon'),
            // 'nip'               => $this->request->getVar('nip'),
            // 'balaiid'           => $this->request->getVar('balaiid'),
            // 'satkerid'           => $this->request->getVar('satkerid'),
            // 'group_id'           => $this->request->getVar('group_id'),
        ])->update();

        if($saved)
        {
            return redirect()->to('/users')->with('success', 'Updated user successfully');
        }
    
    }



    public function create(){
        
        $data = array(
            'title'      => 'Add User',
            'usergroups' => $this->usergroups->getUserGroups(),
            'balai'      => $this->balai->get()->getResultArray(),
            'satker'     => $this->satker->get()->getResultArray()
        );
        return view('Modules\Admin\Views\Users\UsersCreate', $data);
    }


    public function store(){
        $rules = [
            'nama' 				=> 'required|min_length[2]',
            'idpengguna' 		=> 'required|min_length[2]|is_unique[ku_user.idpengguna,uid,{uid}]',
			'email' 			=> 'required|valid_email|is_unique[ku_user.email,uid,{uid}]',
            'sandi'			    => 'required|min_length[5]',
            'sandi_konfirm'	    => 'required|matches[sandi]',
            'balaiid'           => 'required'
		];
		if (! $this->validate($rules)) {
			return redirect()->to('create')
				->withInput()
				->with('errors', $this->validator->getErrors());
        }
        $check = $this->users->checkUid($this->request->getVar('idpengguna'));

        if(!$check){
            $saved = $this->users->save([
                'uid'        => $this->request->getVar('idpengguna'),
                'nama'       => $this->request->getVar('nama'),
                'idpengguna' => $this->request->getVar('idpengguna'),
                'email'      => $this->request->getVar('email'),
                'sandi'      => $this->request->getVar('sandi'),
                'telpon'     => $this->request->getVar('telpon'),
                'nip'        => $this->request->getVar('nip'),
                'balaiid'    => $this->request->getVar('balaiid'),
                'satkerid'   => $this->request->getVar('satkerid'),
                'group_id'   => $this->request->getVar('group_id'),
            ]);

            if($saved)
            {
                return redirect()->to('/users')->with('success', 'Created user successfully');
            }
        } else {
            return redirect()->to('/users/create')
				->withInput()
				->with('error', 'ID pengguna tidak valid');
        }
    }

    public function delete($uid=null) {
        if($uid){
            $check = $this->users->checkUid($uid);

            if($check){
                //$this->users->delete(['uid' => $check['uid']]); // gak bisa pake ini karena primary key default nya ID
                $this->users->where('uid',  $check['uid'])->delete();
                return redirect()->to('/users')->with('success', 'Delete successfully');
            } else {
                return redirect()->to('/users')->with('error', 'Error proses delete');
            }
        } else {
            return redirect()->to('/users')->with('error', 'Error proses delete no uid');
        }
    }


    public function passwordRechange(){
        
        $config         = new \Config\Encryption();
        $config->key    = 'aBigsecret_ofAtleast32Characters';
        $config->driver = 'OpenSSL';
        // $config->digest = 'sha224';

        $encrypter = \Config\Services::encrypter($config);

        // $plainText  = 'yusfiadil@gmail.com';
        // $ciphertext = $encrypter->encrypt($plainText);
        // // Outputs: This is a plain-text message!
        // echo $decypt;exit;
        
        $users = $this->users->where('user_pk','1')->get()->getResultArray();
        
        // echo count($users);exit;
        foreach($users As $user){
            
            $enc = base64_encode($encrypter->encrypt($user['idpengguna']));
            $decypt = $encrypter->decrypt(base64_decode($enc));
            $data = [
                'sandi' => $enc ,
            ];
            
            $this->users->where('idpengguna',$user['idpengguna'])->set($data)->update();



            // echo $decypt;

        }









    }



}