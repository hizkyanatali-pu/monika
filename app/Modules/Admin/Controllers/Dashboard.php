<?php

namespace Modules\Admin\Controllers;



class Dashboard extends \App\Controllers\BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Dashboard'
        );
        return view('Modules\Admin\Views\Dashboard', $data);
    }
}
