<?php

namespace Modules\Admin\Controllers;

class Tematik extends \App\Controllers\BaseController
{
	private $renderFolder = 'Modules\Admin\Views\Tematik';



    public function __construct()
    {
    }



    public function pageFoodEstate() {
    	return view($this->renderFolder.'\Food-estate');
    }



    public function pageKawasanIndustri() {
    	return view($this->renderFolder.'\Kawasan-industri');
    }



    public function pageKspn() {
    	return view($this->renderFolder.'\Kspn');
    }



    public function pageRekap() {
    	return view($this->renderFolder.'\Rekap');
    }
}