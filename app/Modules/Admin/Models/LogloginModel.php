<?php namespace Modules\Admin\Models;

use CodeIgniter\Model;


class LogloginModel extends Model
{
	protected $table      = 'p4_log_login';
	protected $primaryKey = 'id';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;


	protected $allowedFields = [
		'id', 'idpengguna', 'in_dt','ip'
	];

	protected $useTimestamps = false;

	
	protected $validationMessages = [];

	protected $skipValidation = false;

	
}