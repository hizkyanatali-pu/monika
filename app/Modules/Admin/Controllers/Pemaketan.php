<?php

namespace Modules\Admin\Controllers;

class Pemaketan extends \App\Controllers\BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Progres Pemaketan',
        );

        return view('Modules\Admin\Views\Progres-pemaketan\index', $data);
    }
}