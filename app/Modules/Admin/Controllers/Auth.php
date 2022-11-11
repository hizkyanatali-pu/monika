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
			return redirect()->to('dashboard');
		}
		return view('Modules\Admin\Views\Auth', $data);
	}


	//--------------------------------------------------------------------

	/**
	 * Attempts to verify user's credentials through POST request.
	 */
	public function attemptLogin()
	{
		helper('string');

		// validate request
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

		$user = $users->select("
			ku_user.*, ku_user_group.group_id
		")
			->where('idpengguna', $this->request->getPost('idpengguna'))
			->where('sandi', md5($this->request->getPost('sandi')))
			->join('ku_user_group', 'ku_user_group.uid=ku_user.uid', 'left')
			->first();
		//dd($user);

		if (
			is_null($user)
			//|| !$user['group_id']
			//|| ! password_verify($this->request->getPost('sandi'), $user['sandi'])
		) {
			return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna atau Sandi salah');
		}
		//if(!$user['group_id']) return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna atau Sandi salah');

		// check activation
		if (!$user['aktif']) {
			return redirect()->to('auth')->withInput()->with('error', 'ID Pengguna Tidak Aktif');
		}


		if ($this->request->getPost('tahun') < 2023 and $user['uid'] != "admin") {
			return redirect()->to('auth')->withInput()->with('error', 'Fitur Input Dokumen Perjanjian Kinerja Dapat Dibuka Jika memilih tahun > 2022');
		}

		$this->akses = new AksesModel();

		// check db Active
		$cekdb = new ImportdataSqliteModel();
		$cekdb = $cekdb->getactiveDB();


		/** set userData session */
		$setSession_userData = [
			'uid'        => $user['uid'],
			'nama'       => $user['nama'],
			'idpengguna' => $user['idpengguna'],
			'idkelompok' => $user['idkelompok'],
			'telpon'     => $user['telpon'],
			'email'      => $user['email'],
			'balaiid'    => $user['balaiid'],
			'group_id'   => $user['group_id'],
			'tblfilter'  => $this->akses->gettblfilterid($user['idkelompok']),
			'unitbalai'  => $this->akses->getunitbalai($user['uid']),
			'unitsatker' => $this->akses->getunitsatker($user['uid']),
			'unitgiat'   => $this->akses->getunitgiat($user['uid']),
			'dbuse'      => $cekdb,
			'tahun'      => $this->request->getPost('tahun'),
			'user_type'  => 'other'
		];


		if (
			strContains($user['uid'], 'satker')
			|| $user['idkelompok'] == 'SATKER'
			|| ($user['balaiid'] != '' && $user['satkerid'] != '')
		) {
			$dataSarker_n_Balai = $users->select("
				m_satker.satkerid,
				m_satker.satker,
				m_satker.jabatan_penanda_tangan_pihak_2,
				m_balai.balaiid,
				m_balai.balai
			")
				->where('idpengguna', $this->request->getPost('idpengguna'))
				->where('sandi', md5($this->request->getPost('sandi')))
				// ->join('ku_user_satker', 'ku_user.uid = ku_user_satker.uid_user', 'left')
				// ->join('m_satker', 'ku_user_satker.satkerid = m_satker.satkerid', 'left')
				// ->join('m_balai', 'm_satker.balaiid = m_balai.balaiid', 'left')
				->join('m_satker', 'ku_user.satkerid = m_satker.satkerid', 'left')
				->join('m_balai', 'ku_user.balaiid = m_balai.balaiid', 'left')
				->first();


			$setSession_userData['satker_id']   = $dataSarker_n_Balai['satkerid'];
			$setSession_userData['satker_nama'] = $dataSarker_n_Balai['satker'];
			$setSession_userData['balai_id']    = $dataSarker_n_Balai['balaiid'];
			$setSession_userData['balai_nama']  = $dataSarker_n_Balai['jabatan_penanda_tangan_pihak_2'];
			$setSession_userData['user_type']   = 'satker';
		} elseif (
			strContains($user['uid'], 'balai')
			|| $user['idkelompok'] == 'BALAI'
			|| ($user['balaiid'] != '' && $user['satkerid'] == '')
		) {
			$dataBalai = $users->select("
				m_balai.balaiid,
				m_balai.balai
			")
				->where('uid', $user['uid'])
				->join('m_balai', 'ku_user.balaiid = m_balai.balaiid', 'left')
				->first();

			$setSession_userData['balai_id']    = $dataBalai['balaiid'];
			$setSession_userData['balai_nama']  = $dataBalai['balai'];
			$setSession_userData['user_type']    = 'balai';
		}
		/** end-of: set userData session */

		// login OK, save user data to session
		$this->session->set('isLoggedIn', true);
		$this->session->set('userData', $setSession_userData);
		$log = new LogloginModel();
		$log->save(array(
			'idpengguna' => $user['idpengguna'],
			'in_dt' => date('Y-m-d H:i:s'),
			'ip' => $this->request->getIPAddress()
		));


		return redirect()->to('dashboard');
	}



	//--------------------------------------------------------------------

	/**
	 * Log the user out.
	 */
	public function logout()
	{
		$this->session->remove(['isLoggedIn', 'userData', 'createDokumenByAdmin', 'createDokumenByBalai']);
		$cache = \Config\Services::cache();
		$cache->clean();
		return redirect()->to('auth');
	}
}
