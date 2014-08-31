<?php

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;

class model_environment{

	private static $profiles = ['default_page', 'profile', 'exit',
		'import', 'error', 'report',
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
		$pimple = di::get_instance();
			$pimple['profile'] = null;
			return;

		$profile = di::get('user')->get_profile($component);
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

		$pimple['model_import'] = function($p){
			return new model_import();
		};

		$pimple['pdo'] = function($pimple){
			$pdo = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
			$pdo->exec("SET NAMES utf8");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		  return $pdo;
		};

    /** @return \Doctrine\ORM\EntityManager */
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
		}
		di::set_instance($pimple);
	}
}