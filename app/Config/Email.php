<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
	public $fromEmail;
	public $fromName;
	public $recipients;

	public $userAgent = 'CodeIgniter';

	public $protocol = 'mail';

	public $mailPath = '/usr/sbin/sendmail';

	public $SMTPHost;

	public $SMTPUser;

	public $SMTPPass;

	public $SMTPPort = 25;

	public $SMTPTimeout = 5;

	public $SMTPKeepAlive = false;

	public $SMTPCrypto = 'tls';

	public $wordWrap = true;

	public $wrapChars = 76;

	public $mailType = 'text';

	public $charset = 'UTF-8';

	public $validate = false;

	public $priority = 3;

	public $CRLF = "\r\n";

	public $newline = "\r\n";

	public $BCCBatchMode = false;

	public $BCCBatchSize = 200;

	public $DSN = false;
}
