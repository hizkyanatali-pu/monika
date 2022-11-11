<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\UserModel;

class Account extends \App\Controllers\BaseController
{
  public function index()
  {

    $session = $this->session->userData;

    $db = db_connect();
    $users = $db->query("SELECT idpengguna,nama,telpon,email FROM ku_user WHERE idpengguna= '$session[idpengguna]'")->getRowArray();

    $data = array(
      'title' => 'Kelola Pengguna',
      'userData' => $session,
      'users' => $users
    );
    return view('Modules\Admin\Views\Account', $data);
  }

  /**
   * Updates regular account settings.
   */
  public function update()
  {
    $users = new UserModel();

    $rules = [
      'nama'         => 'required|min_length[2]',
      'idpengguna'     => 'required|min_length[2]',
      'email'       => 'required|valid_email',
      // 'balaiid'           => 'required'
    ];
    if ($this->request->getVar('sandi')) {
      $rules['sandi'] = 'required|min_length[5]';
      $rules['sandi_konfirm'] = 'required|matches[sandi]';
    }
    if (!$this->validate($rules)) {
      return redirect()->to('account')
        ->withInput()
        ->with('errors', $this->validator->getErrors());
    }

    $user = $users->where('uid',  $this->session->get('userData.uid'))->set([
      //  'uid'               => $this->session->get('userData.uid'),
      'nama'         => $this->request->getVar('nama'),
      //  'idpengguna' 		=> $this->request->getVar('idpengguna'),
      'email'       => $this->request->getVar('email'),
      //   'sandi'			    => $this->request->getVar('sandi'),
      'telpon'            => $this->request->getVar('telpon'),
      'nip'               => $this->request->getVar('nip')
    ])->update();


    if (!$users) {
      return redirect()->back()->withInput()->with('errors', $users->errors());
    }

    // update session data
    //  $this->session->push('userData', $user);

    return redirect()->to('account')->with('success', 'Profil sudah di update');
  }

  /**
   * Updates passwords view.
   */
  public function change_password()
  {

    $session = $this->session->userData;

    $data = array(
      'title' => 'Ganti Password',
      'userData' => $session,
    );
    return view('Modules\Admin\Views\Change_password', $data);
  }

  /**
   * Updates passwords.
   */
  public function updatePassword()
  {
    $users = new UserModel();

    $rules = [
      'sandi'          => 'required|min_length[5]',
      'sandi_konfirm'      => 'required|matches[sandi]',
    ];
    if ($this->request->getVar('sandi')) {
      $rules['sandi'] = 'required|min_length[5]';
      $rules['sandi_konfirm'] = 'required|matches[sandi]';
    }
    if (!$this->validate($rules)) {
      return redirect()->to('change-password')
        ->withInput()
        ->with('errors', $this->validator->getErrors());
    }

    $user = $users->where('uid',  $this->session->get('userData.uid'))->set(['sandi'      => $this->request->getVar('sandi'),])->update();


    if (!$users) {
      return redirect()->back()->withInput()->with('errors', $users->errors());
    }

    // update session data
    //  $this->session->push('userData', $user);

    return redirect()->to('change-password')->with('success', 'Password Berhasil Diperbaruhi');
  }
}
