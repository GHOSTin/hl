<?php
class model_environment{

	/**
	* Строит компоненты роутера.
	* @return array
	*/
	public static function build_router(){
		try{
			$path = parse_url($_SERVER['REQUEST_URI']);
			if($path['path'] === '/')
				return ['default_page', 'show_default_page'];
			elseif(preg_match_all('|^/[a-z_]+/$|', $path['path'], $arr, PREG_PATTERN_ORDER)){
				$args = explode('/', $arr[0][0]);
				return [$args[1], 'show_default_page'];
			}elseif(preg_match_all('|^/[a-z_]+/[a-z_]+$|', $path['path'], $arr, PREG_PATTERN_ORDER)){
				$args = explode('/', $arr[0][0]);
				return [$args[1], $args[2]];
			}else
				throw new e_controller('Нет такой страницы.');
		}catch(exception $e){
			throw new e_controller('Нет такой страницы.');
		}
	}

	/**
	* Инициализирует соединения с базой данных.
	* @return void
	*/
	public static function create_batabase_connection(){
		try{
			db::connect(application_configuration::database_host,
						application_configuration::database_name,
						application_configuration::database_user,
						application_configuration::database_password);
		}catch(exception $e){
			die($e->getMessage());
 			throw new e_model('Нет соединения с базой данных.');
		}
		// устанавливаем параметры по умолчанию
		try{
			db::get_handler()->exec("SET NAMES utf8");
			db::get_handler()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			db::get_handler()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(exception $e){
			throw new e_model('Аттрибуты соединения с базой данных не применились.'); 
		}
	}

	/**
	* Строит роутер исходя из залогинености пользователя.
	*/
	public static function create_session(){
		session_start();
		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_current_user){
			self::create_batabase_connection();
			model_session::set_user($_SESSION['user']);
			model_session::set_company($_SESSION['company']);
			$route = self::build_router();
			$route[] = 'private_';
			return $route;
		}elseif(!empty($_POST['login'])){
			try{
				self::create_batabase_connection();
				$user = model_auth::auth_user();
				if($user instanceof data_current_user){
					model_session::set_user($user);
					model_user::verify_company_id($user);
					$company = new data_company();
					$company->id = $user->company_id;
					model_session::set_company($company);
					$route = self::build_router();
					$route[] = 'private_';
					return $route;
				}else{
					session_destroy();
					return ['auth', 'show_auth_form', 'public_'];
				}
			}catch(exception $e){
				session_destroy();
				return ['auth', 'show_auth_form', 'public_'];
			}
		}else{
			session_destroy();
			return ['auth', 'show_auth_form', 'public_'];
		}
	}

	/*
	* Возвращает содержимое страницы.
	*/
	public static function get_page_content(){
		try{
			list($component, $method, $prefix) = self::create_session();
			$controller = 'controller_'.$component;
			if(!class_exists($controller))
				throw new e_controller('Нет такой страницы.');
			if(!method_exists($controller, $prefix.$method))
				throw new e_controller('Нет такой страницы.');
			$view = 'view_'.$component;
			$user = model_session::get_user();
			if($user instanceof data_current_user){
				model_profile::get_user_profiles(model_session::get_company(), $user);
				$data['menu'] = model_menu::build_menu($component);
				if(isset(model_session::get_rules()[$component]))
					$data['rules'] = model_session::get_rules()[$component];
				self::verify_general_access($component);
			}
			$data['file_prefix'] = $component;

			$data['component'] = $controller::{$prefix.$method}();
			return $view::{$prefix.$method}($data);
		}catch(exception $e){
			if($e instanceof e_model)
				return view_error::show_error($e);
			elseif($e instanceof e_controller)
				return view_error::show_404();
			else
				return view_error::show_error($e);
		}
	}

	/**
	* Верификация доступа к компоненту.
	*/
	public static function verify_general_access($component){
       /* if(model_session::get_user() instanceof data_current_user){
            $access = (model_profile::check_general_access($controller, $component));
            if($access !== true){
                $controller = 'controller_error';
                $view = 'view_error';
                $prefix = 'private_';
                $method = 'get_access_denied_message';
            }
        }*/
	}
}