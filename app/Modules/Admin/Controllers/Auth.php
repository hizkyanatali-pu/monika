<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\Controller;
use Config\Email;
use Config\Services;
use Modules\Admin\Models\UserModel;
use Modules\Admin\Models\LogloginModel;
use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\ImportdataSqliteModel;


class Auth extends \App\Controllers\BaseController
{
	/**
	 * Displays login form or redirects if user is already logged in.
	 */
	public function index()
	{
		$data = array(
			'title' => 'Login'
		);
		if ($this->session->isLoggedIn) {
			return redirect()->to('pulldata');
		}
		return view('Modules\Admin\Views\Auth', $data);
	}


	//--------------------------------------------------------------------

	/**
	 * Attempts to verify user's credentials through POST request.
	 */
	public function attemptLogin()
	{
		// validate request
		//print_r($this->request);
		$rules = [
			'idpengguna'		=> 'required',
			'sandi' 	=> 'required|min_length[5]',
		];

		if (!$this->validate($rules)) {
			return redirect()->to('auth')
				->withInput()
				->with('errors', $this->validator->getErrors());
		}

		// check credentials
		$users = new UserModel();
		$user = $users
			->where('idpengguna', $this->request->getPost('idpengguna'))
			->where('sandi', md5($this->request->getPost('sandi')))
			->join('ku_user_group', 'ku_user_group.uid=ku_user.uid', 'left')
			->first();
		//dd($user);
		if (
			is_null($user)
			|| !$user['group_id']
			//|| ! password_verify($this->request->getPost('sandi'), $user['sandi'])
		) {
			return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna atau Sandi salah');
		}
		//if(!$user['group_id']) return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna atau Sandi salah');

		// check activation
		if (!$user['aktif']) {
			return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna Tidak Aktif');
		}

		$this->akses = new AksesModel();


		// check db Active
		$cekdb = new ImportdataSqliteModel();
		$cekdb = $cekdb->getactiveDB();

		// login OK, save user data to session
		$this->session->set('isLoggedIn', true);
		$this->session->set('userData', [
			'uid' 			=> $user['uid'],
			'nama' 			=> $user['nama'],
			'idpengguna' 	=> $user['idpengguna'],
			'telpon'        => $user['telpon'],
			'email'         => $user['email'],
			'balaiid'		=> $user['balaiid'],
			'group_id'		=> $user['group_id'],
			'tblfilter'		=> $this->akses->gettblfilterid($user['idkelompok']),
			'unitbalai'		=> $this->akses->getunitbalai($user['uid']),
			'unitsatker'	=> $this->akses->getunitsatker($user['uid']),
			'unitgiat'		=> $this->akses->getunitgiat($user['uid']),
			'dbuse'			=> $cekdb
		]);
		$log = new LogloginModel();
		$log->save(array(
			'idpengguna' => $user['idpengguna'],
			'in_dt' => date('Y-m-d H:i:s'),
			'ip' => $this->request->getIPAddress()
		));
		return redirect()->to('pulldata');
	}



	//--------------------------------------------------------------------

	/**
	 * Log the user out.
	 */
	public function logout()
	{
		$this->session->remove(['isLoggedIn', 'userData']);
		$cache = \Config\Services::cache();
		$cache->clean();
		return redirect()->to('auth');
	}
}
