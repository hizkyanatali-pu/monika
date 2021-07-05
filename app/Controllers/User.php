<?php namespace App\Controllers;

use \App\Libraries\Oauth;
use \OAuth2\Request;
use CodeIgniter\API\ResponseTrait;



class User extends BaseController
{
	use ResponseTrait;

	public function login()
	{
		// die("tes");
		$oauth = new Oauth();
		$request = new Request();
		//print_r($_REQUEST); die();
		$respond = $oauth->server->handleTokenRequest($request->createFromGlobals());
		$code = $respond->getStatusCode();
		$body = $respond->getResponseBody();
		//print_r($request); die();
		return $this->respond(json_decode($body),$code);
	}


}
