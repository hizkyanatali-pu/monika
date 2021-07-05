<?php namespace Modules\Admin\Models;

use CodeIgniter\Model;

class BalaiModel extends Model
{
	protected $table      = 'm_balai';
	protected $primaryKey = 'balaiid';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;


	protected $allowedFields = [
		'balai', 'balaiid'
	];

	protected $useTimestamps = false;



	protected $validationRules = [];


	protected $validationMessages = [];

	protected $skipValidation = false;

		

}