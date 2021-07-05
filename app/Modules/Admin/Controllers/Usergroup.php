<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\UsergroupsModel;

class Usergroup extends \App\Controllers\BaseController
{
    public function __construct() {

        $this->usergroups = new UsergroupsModel();
        
    }
    public function index(){
        $data = array(
            'title'=> 'User Group',
            'usergroups'=> $this->usergroups->getUserGroups()
        );
       
        return view('Modules\Admin\Views\Usergroups\Usergroups', $data);
    }

    public function edit($slug = null){

        $data = array(
            'title'=> 'Edit User',
            'usergroup'=> $this->usergroups->getUserGroups($slug)
        );
        if (empty($data['usergroup'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the Group: ' . $slug);
        }

        return view('Modules\Admin\Views\Usergroups\UsergroupsEdit', $data);
    }
    public function update(){
        $rules = [
            'keterangan' 		=> 'required|min_length[2]',
            'nama' 		        => 'required|min_length[2]|is_unique[ku_group.nama,group_id,{group_id}]',
            'aktif'           => 'required'
		];

		if (! $this->validate($rules)) {
			return redirect()->to('edit/'.$this->request->getVar('group_id'))
				->withInput()
				->with('errors', $this->validator->getErrors());
        }
 
        $saved = $this->usergroups->where('group_id',  $this->request->getVar('group_id'))->set([
            'group_id'                  => $this->request->getVar('group_id'),
            'nama' 				        => $this->request->getVar('nama'),
            'keterangan' 				=> $this->request->getVar('keterangan'),
            'aktif' 				    => $this->request->getVar('aktif'),
        ])->update();

        if($saved)
        {
            return redirect()->to('/usergroups')->with('success', 'Updated Group successfully');
        }
    
    }
    public function create(){
        $data = array(
            'title'=> 'Add User Group',
        );
        return view('Modules\Admin\Views\Usergroups\UsergroupsCreate', $data);
    }


    public function store(){
        $rules = [
            'nama' 			=> 'required|min_length[2]|is_unique[ku_group.nama,group_id,{group_id}]',
            'keterangan'    => 'required',
            'aktif'         => 'required'
		];
		if (! $this->validate($rules)) {
			return redirect()->to('create')
				->withInput()
				->with('errors', $this->validator->getErrors());
        }
        $check = $this->usergroups->checkGuid($this->request->getVar('nama'));

        if(!$check){
           
            $saved = $this->usergroups->save([
                'group_id'          => $this->request->getVar('nama'),
                'nama' 				=> $this->request->getVar('nama'),
                'keterangan' 		=> $this->request->getVar('keterangan'),
                'aktif' 		    => $this->request->getVar('aktif')
            ]);

            if($saved)
            {
                return redirect()->to('/usergroups')->with('success', 'Created user successfully');
            }
        } else {
            return redirect()->to('/usergroups/create')
				->withInput()
				->with('error', 'User Group tidak valid');
        }
    }

    public function delete($uid=null) {
        if($uid){
            $check = $this->usergroups->checkGuid($uid);

            if($check){
                //$this->users->delete(['uid' => $check['uid']]); // gak bisa pake ini karena primary key default nya ID
                $this->users->where('group_id',  $check['group_id'])->delete();
                return redirect()->to('/usergroups')->with('success', 'Delete successfully');
            } else {
                return redirect()->to('/usergroups')->with('error', 'Error proses delete');
            }
        } else {
            return redirect()->to('/usergroups')->with('error', 'Error proses delete no group id');
        }
    }
}