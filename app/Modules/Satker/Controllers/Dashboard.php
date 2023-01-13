<?php

namespace Modules\Satker\Controllers;

class Dashboard extends \App\Controllers\BaseController
{

    public function index()
    {
        return view('Modules\Satker\Views\Dashboard');
    }
}
