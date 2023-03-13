<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

		if (!defined('THEME'))
			define('THEME', '');

		if (!defined('PATH'))
			define('PATH', base_url());
		if (!defined('BASE'))
			define('BASE', base_url() . '/');
		if (!defined('ASSETS'))
			define('ASSETS', base_url() . '/assets/');
		if (!defined('TITLE'))
			define('TITLE', 'KoffeeKodes IT Solutions');
		if (!defined('LOGODARK'))
			define('LOGODARK', ASSETS . '/img/brand/logo_dark.png');
		if (!defined('LOGO'))
			define('LOGO', PATH . '/uploads/company/logo.png');
		if (!defined('LOGOICON'))
			define('LOGOICON', '/uploads/company/logo.png');
		if (!defined('GCODE'))
			define('GCODE', 'SNNGNHTW33U44SBI');

		if(session('DataSource')){
			if (!defined('DataSource'))
				define('DataSource', session('DataSource'));
		}
		// if (!defined('GMAP'))
		// 	define('GMAP', 'AIzaSyDMFjsFu-RTGRYCHsGV10Cl2UzP22FRkGU');

		$agent = $this->request->getUserAgent();
		if (!defined('getBrowser'))
			define('getBrowser', $agent->getBrowser());
		if (!defined('isMobile'))
			define('isMobile', $agent->isMobile());

		define('CDATE', date('Y-m-d H:i:s'));

		helper('base');  
		helper('addbook');  
		helper('gst');  
		helper('balanceSheet');  
		helper('trading');  
		helper('pl');

	}

}
