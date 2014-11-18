<?php

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \boxxy\classes\di;

class model_environment{

	private static $profiles = ['default_page', 'profile', 'exit',
		'error', 'about', 'export', 'task', 'metrics', 'works', 'api'];

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
		$user = di::get('user');
		if(!is_null($user)){
			$profile = $user->get_profile($component);
			if(!in_array($component, self::$profiles, true)){
				if($profile->get_rules()['generalAccess'] !== true)
					throw new RuntimeException('Доступа нет.');
				$data['rules'] = $profile->get_rules();
			}
			$data['user'] = $user;
			$profiles = ['number', 'query', 'metrics', 'task'];
			foreach($profiles as $profile){
				$c = 'controller_'.$profile;
				if(property_exists($c, 'name'))
					$data['menu'][] = ['href' => $profile, 'title' => $c::$name];
			}
		}
		$data['response'] = $controller::$method($request);
		$data['request'] = $request;
		return $data;
	}

	public static function render_template($component, $method, $data){
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
	  $loader->prependPath(ROOT.'/templates/'.$component.'/', $component);
		$options = array();
		if(application_configuration::status == 'production')
			$options['cache'] = ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'twig/';
		$twig = new Twig_Environment($loader, $options);
	  return $twig->render('@'.$component.'/'.$method.'.tpl', $data);
	}

	public static function before(){
		require_once ROOT.'/application/application_configuration.php';
		date_default_timezone_set(application_configuration::php_timezone);
		$pimple = new \Pimple\Container();

		$pimple['pdo'] = function($pimple){
			$pdo = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
			$pdo->exec("SET NAMES utf8");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		  return $pdo;
		};

		$pimple['model_query'] = function($pimple){
		  return new model_query($pimple);
		};

		$pimple['model_number'] = function($pimple){
			return new model_number($pimple);
		};

		$pimple['model_report_query'] = function($pimple){
		  return new model_report_query();
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
		}else{
			$pimple['user'] = null;
		}
		di::set_instance($pimple);
	}
}