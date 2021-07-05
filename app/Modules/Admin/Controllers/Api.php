<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\TopikModel;

class Usulan extends \App\Controllers\BaseController
{
    public function __construct() {

        $this->TopikModel        = new TopikModel();
       
    }

    public function index()
    {
        $data = array(
            'title'=> 'Usulan Monitoring',
            'usulan'=> array(
                array('id'=>1,'nosurat'=>'1s8238','tglsurat'=>'20/20/2020','perihal'=>'Perihal 1','keterangan'=>'kete....'),
            )
        );
        return view('Modules\Admin\Views\Usulan\Usulan',$data);
       
    }

    public function create()
    {
        $data = array(
            'title'=> 'Create Usulan Monitoring',
            'topikData'=> $this->TopikModel->getTopik()
        );
       
        return view('Modules\Admin\Views\Usulan\UsulanCreate',$data);
       
    }

    public function api()
    {
        if ($this->request->isAJAX()) {
            
            return json_encode(['success'=> 'success', 'csrf' => csrf_hash(), 'query ' => $query ]);
        }
       
    }

}