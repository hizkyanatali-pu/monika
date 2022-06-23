<?php

namespace Modules\Balai\Controllers;

class Dashboard extends \App\Controllers\BaseController
{

    public function index(){
        return view('Modules\Balai\Views\Dashboard');
    }

}