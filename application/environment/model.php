<?php
class model_environment{

	private static $profiles = ['default_page', 'profile', 'exit',
		'processing_center', 'import', 'meter', 'error', 'company', 'report',
		'about'];

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
			if(in_array(get_class($e), ['e_model', 'DomainException'], true)){
				$args['error'] = $e;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
            return load_template('error.show_ajax_error', $args);
        else
            return load_template('error.show_html_error', $args);
			}else{
				die($e);
				die('Problem');
			}
		}
	}

	public static function prepare_answer($controller, $component, $method, $request){
		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
			self::init_profile($component);
			$data['user'] = di::get('user');
			$data['menu'] = model_menu::build_menu($component);
		}
		$profile = model_session::get_profile();
		if($profile instanceof data_profile)
			$data['rules'] = $profile->get_rules();
		$data['component'] = $controller::$method($request);
		$data['request'] = $request;
		return $data;
	}

	public static function render_template($component, $method, $data){
		$template = ROOT.'/application/'.$component.'/templates/'.$method.'.tpl';
		if(file_exists($template))
			return load_template($component.'.'.$method, $data);
		else
			return load_template('error.no_template', $data);
	}

	public static function init_profile($component){
		$profile = (new mapper_user2profile(di::get('company'),
			di::get('user')))->find($component);
		if(in_array($component, self::$profiles, true))
			return;
		elseif($profile instanceof data_profile){
			if($profile->get_rules()['generalAccess'] !== true)
				throw new e_model('Доступа нет.');
			model_session::set_profile($profile);
		}else
			throw new e_model('Доступа нет.');
	}

	public static function before(){
		$pimple = new Pimple();

		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
			$pimple['user'] = $_SESSION['user'];
			$pimple['company'] = $_SESSION['company'];
		}

		$pimple['factory_client_query'] = function($p){
			return new factory_client_query();
		};

		$pimple['factory_flat'] = function($p){
			return new factory_flat();
		};

		$pimple['factory_session'] = function($p){
			return new factory_session();
		};

		$pimple['factory_company'] = function($p){
			return new factory_company();
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

		$pimple['factory_user'] = function($p){
			return new factory_user();
		};

		$pimple['factory_street'] = function($p){
			return new factory_street();
		};

		$pimple['factory_error'] = function($p){
			return new factory_error();
		};

		$pimple['mapper_number'] = function($p){
			return new mapper_number($p['pdo'], $p['company']);
		};

		$pimple['mapper_user'] = function($p){
			return new mapper_user($p['pdo']);
		};

		$pimple['mapper_query'] = function($p){
			return new mapper_query($p['pdo'], $p['company']);
		};

		$pimple['mapper_session'] = function($p){
			return new mapper_session($p['pdo']);
		};

		$pimple['mapper_company'] = function($p){
			return new mapper_company($p['pdo']);
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

		$pimple['mapper_group'] = function($p){
			return new mapper_group($p['pdo'], $p['company']);
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

		$pimple['mapper_city'] = function($p){
			return new mapper_city($p['pdo']);
		};

		$pimple['mapper_error'] = function($p){
			return new mapper_error($p['pdo']);
		};

		$pimple['mapper_meter'] = function($p){
			return new mapper_meter($p['pdo'], $p['company']);
		};

		$pimple['model_number'] = function($p){
			return new model_number($p['company']);
		};

		$pimple['model_query'] = function($p){
			return new model_query($p['company']);
		};

		$pimple['model_group'] = function($p){
			return new model_group($p['company']);
		};

		$pimple['model_user'] = function($p){
			return new model_user();
		};

		$pimple['model_work'] = function($p){
			return new model_work($p['company']);
		};

		$pimple['model_workgroup'] = function($p){
			return new model_workgroup($p['company']);
		};

		$pimple['model_street'] = function($p){
			return new model_street();
		};

		$pimple['model_query_work_type'] = function($p){
			return new model_query_work_type($p['company']);
		};

		$pimple['twig'] = $pimple->share(function($pimple){
			$options = [];
			$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
			return new Twig_Environment($loader, $options);
		});

		$pimple['pdo'] = $pimple->share(function($pimple){
			$pdo = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
			$pdo->exec("SET NAMES utf8");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		  return $pdo;
		});
		di::set_instance($pimple);
	}
}