<?php

namespace App\Filters;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
	/**
	 * Do whatever processing this filter needs to do.
	 * By default it should not return anything during
	 * normal execution. However, when an abnormal state
	 * is found, it should return an instance of
	 * CodeIgniter\HTTP\Response. If it does, script
	 * execution will end and that Response will be
	 * sent back to the client, allowing for error pages,
	 * redirects, etc.
	 *
	 * @param \CodeIgniter\HTTP\RequestInterface $request
	 *
	 * @return mixed
	 */
	public function before(RequestInterface $request, $arguments = null)
	{
		if (!function_exists('logged_in')) {
			helper('auth');
		}


		$current = (string)current_url(true)
			->setHost('')
			->setScheme('')
			->stripQuery('token');

		//redirect if not Administrator
		$path = $request->uri->getSegments();
		if (isset($path[0])) {
			if ($path[0] === 'users' || $path[0] === 'usergroups') {
				$session = session();
				$user = $session->get('userData');
				// if ($user['group_id'] !== 'Administrator') return redirect('usulan');
			}
		}

		if (in_array((string)$current, [route_to('auth'), route_to('forgot'), route_to('reset-password'), route_to('register')])) {
			return;
		}

		// if (!logged_in() and $path[0] != 'api') {

		if (!empty($path) && $path[0] != 'api' && !logged_in()) {
			return redirect('auth');
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Allows After filters to inspect and modify the response
	 * object as needed. This method does not allow any way
	 * to stop execution of other after filters, short of
	 * throwing an Exception or Error.
	 *
	 * @param \CodeIgniter\HTTP\RequestInterface  $request
	 * @param \CodeIgniter\HTTP\ResponseInterface $response
	 *
	 * @return mixed
	 */
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		return;
	}

	//--------------------------------------------------------------------
}
