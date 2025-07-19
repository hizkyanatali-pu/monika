<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		return redirect('auth');
		$data = [
            'title' => 'Welcome',
		];
		
		echo view('Templates\header', $data);
        echo view('welcome_message', $data);
        echo view('Templates\footer', $data);
	}

	//--------------------------------------------------------------------

}
