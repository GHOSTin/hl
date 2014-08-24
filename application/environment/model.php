<?php

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;

class model_environment{

	private static $profiles = ['default_page', 'profile', 'exit',
		'processing_center', 'import', 'meter', 'error', 'company', 'report',
		'about', 'export', 'task', 'metrics'];

	/*
	* Возвращает содержимое страницы.
	*/
	public static function get_page_content(){
		try{
			session_start();
			self::before();
			$request = new model_request();
			$resolver = new model_resolver();
			list($controller, $method) = $resolver->get_controller($request);
			$component = substr($controller, 11);
			$data = self::prepare_answer($controller, $component, $method, $request);
			return self::render_template($component, $method, $data);
		}catch(exception $e){
			if(application_configuration::status === 'development')
				die($e);
			else
				exit();
		}
	}

	public static function prepare_answer($controller, $component, $method, $request){
		if(isset($_SESSION['user'])){
			self::init_profile($component);
			$data['user'] = di::get('user');
			$data['menu'] = model_menu::build_menu($component);
		}else{
			$pimple = di::get_instance();
			$pimple['profile'] = null;
		}
		$profile = di::get('profile');
		if($profile instanceof data_profile)
			$data['rules'] = $profile->get_rules();
		$data['response'] = $controller::$method($request);
		$data['request'] = $request;
		return $data;
	}

	public static function render_template($component, $method, $data){
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
	  $loader->prependPath(ROOT.'/templates/'.$component.'/', $component);
		$twig=  new Twig_Environment($loader);
	  return $twig->render('@'.$component.'/'.$method.'.tpl', $data);
	}

	public static function init_profile($component){
		$profile = (new mapper_user2profile(di::get('company'),
			di::get('user')))->find($component);
		if(in_array($component, self::$profiles, true)){
			$pimple = di::get_instance();
			$pimple['profile'] = null;
			return;
		}elseif($profile instanceof data_profile){
			if($profile->get_rules()['generalAccess'] !== true)
				throw new RuntimeException('Доступа нет.');
			$pimple = di::get_instance();
			$pimple['profile'] = $profile;
		}else
			throw new RuntimeException('Доступа нет.');
	}

	public static function before(){
		require_once ROOT.'/application/application_configuration.php';
		date_default_timezone_set(application_configuration::php_timezone);
		$pimple = new \Pimple\Container();

		$pimple['factory_accrual'] = function($p){
			return new factory_accrual();
		};

		$pimple['factory_metrics'] = function($p){
			return new factory_metrics();
		};

		$pimple['factory_query2comment'] = function($p){
			return new factory_query2comment();
		};

		$pimple['factory_client_query'] = function($p){
			return new factory_client_query();
		};

		$pimple['factory_flat'] = function($p){
			return new factory_flat();
		};

		$pimple['factory_session'] = function($p){
			return new factory_session();
		};

		$pimple['factory_department'] = function($p){
			return new factory_department();
		};

		$pimple['factory_number'] = function($p){
			return new factory_number();
		};

		$pimple['factory_query'] = function($p){
			return new factory_query();
		};

		$pimple['factory_house'] = function($p){
			return new factory_house();
		};

		$pimple['factory_street'] = function($p){
			return new factory_street();
		};

		$pimple['factory_error'] = function($p){
			return new factory_error();
		};

		$pimple['factory_work'] = function($p){
			return new factory_work();
		};

    $pimple['factory_task'] = function($p){
			return new factory_task();
		};

    $pimple['factory_task2comment'] = function($p){
			return new factory_task2comment();
		};

		$pimple['mapper_query'] = function($p){
			return new mapper_query($p['pdo'], $p['company']);
		};

		$pimple['mapper_query2work'] = function($p){
			return new mapper_query2work($p['pdo']);
		};

		$pimple['mapper_accrual'] = function($p){
			return new mapper_accrual($p['pdo']);
		};

		$pimple['mapper_metrics'] = function($p){
			return new mapper_metrics($p['pdo']);
		};

		$pimple['mapper_session'] = function($p){
			return new mapper_session($p['pdo']);
		};

		$pimple['mapper_department'] = function($p){
			return new mapper_department($p['pdo'], $p['company']);
		};

		$pimple['mapper_street'] = function($p){
			return new mapper_street($p['pdo']);
		};

		$pimple['mapper_house'] = function($p){
			return new mapper_house($p['pdo']);
		};

		$pimple['mapper_work'] = function($p){
			return new mapper_work($p['pdo'], $p['company']);
		};

		$pimple['mapper_workgroup'] = function($p){
			return new mapper_workgroup($p['pdo'], $p['company']);
		};

		$pimple['mapper_processing_center'] = function($p){
			return new mapper_processing_center($p['pdo']);
		};

		$pimple['mapper_query2comment'] = function($p){
			return new mapper_query2comment($p['pdo']);
		};

		$pimple['mapper_city'] = function($p){
			return new mapper_city($p['pdo']);
		};

		$pimple['mapper_error'] = function($p){
			return new mapper_error($p['pdo']);
		};

		$pimple['mapper_meter'] = function($p){
			return new mapper_meter($p['pdo'], $p['company']);
		};

    $pimple['mapper_export'] = function($p){
			return new mapper_export($p['pdo']);
		};

    $pimple['mapper_task'] = function($p){
			return new mapper_task($p['pdo']);
		};

    $pimple['mapper_user2task'] = function($p){
			return new mapper_user2task($p['pdo']);
		};

    $pimple['mapper_task2comment'] = function($p){
			return new mapper_task2comment($p['pdo']);
		};

		$pimple['model_query'] = function($p){
			return new model_query($p['company']);
		};

		$pimple['model_work'] = function($p){
			return new model_work($p['company']);
		};

		$pimple['model_workgroup'] = function($p){
			return new model_workgroup($p['company']);
		};

		$pimple['model_query_work_type'] = function($p){
			return new model_query_work_type($p['company']);
		};

		$pimple['model_import'] = function($p){
			return new model_import($p['company']);
		};

    $pimple['model_export'] = function($p){
			return new model_export();
		};

    $pimple['model_task'] = function($p){
			return new model_task();
		};

    $pimple['model_user2task'] = function($p){
			return new model_user2task();
		};

		$pimple['pdo'] = function($pimple){
			$pdo = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
			$pdo->exec("SET NAMES utf8");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		  return $pdo;
		};

		$pimple['em'] = function($pimple){
		  $paths = array(__DIR__);
		  $isDevMode = (application_configuration::status == 'development')? true: false;
		  $dbParams = array(
		      'driver'   => 'pdo_mysql',
		      'host'     => application_configuration::database_host,
		      'user'     => application_configuration::database_user,
		      'password' => application_configuration::database_password,
		      'dbname'   => application_configuration::database_name,
		      'charset'  => 'utf8'
		  );

		  $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
		  return EntityManager::create($dbParams, $config);
		};

		di::set_instance($pimple);
		if(isset($_SESSION['user'])){
			$em = di::get('em');
			$pimple['user'] = $em->find('data_user', $_SESSION['user']);
			$pimple['company'] = $em->find('data_company', $_SESSION['company']);
		}
		di::set_instance($pimple);
	}
}