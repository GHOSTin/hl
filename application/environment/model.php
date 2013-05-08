<?php
class model_environment{
	/*
	* Строит роутер
	*/
	public static function build_router(){
		try{
			$url_array = parse_url($_SERVER['REQUEST_URI']);
			$url = explode('/', substr($url_array['path'], 1));
			$component = (string) $url[0];
			if(empty($component)){
				$component = 'default_page';
				$method = 'show_default_page';
			}else{
				$method = (string) $url[1];
				if(empty($method))
					$method = 'show_default_page';
			}
			$route = [$component, 'private_', $method];
			return $route;
		}catch(exception $e){
			throw new e_model('Ошибка постройки маршрута.');
		}
	}
	/**
	* Инициализирует соединение с базой данных
	* @return void
	*/
	public static function create_batabase_connection(){
		try{
			db::connect(application_configuration::database_host,
						application_configuration::database_name,
						application_configuration::database_user,
						application_configuration::database_password);
		}catch(exception $e){
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
	public static function create_session(){
		session_start();
		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_current_user){
			self::create_batabase_connection();
			model_session::set_user($_SESSION['user']);
			return self::build_router();
		}elseif(!empty($_POST['login'])){
			self::create_batabase_connection();
			$user = model_auth::auth_user();
			if($user instanceof data_current_user){
				model_session::set_user($user);
				return self::build_router();
			}else{
				session_destroy();
				return ['auth', 'public_', 'show_auth_form'];
			}
		}else{
			session_destroy();
			return ['auth', 'public_', 'show_auth_form'];
		}
		exit();
	}
	/*
	* Функция возвращает содержимое страницы
	*/
	public static function get_page_content(){
		try{
			list($component, $prefix, $method) = self::create_session();
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			$user = model_session::get_user();
			if($user instanceof data_current_user){
				model_profile::get_user_profiles($user);
				$data['menu'] = model_menu::build_menu();
				if(isset(model_session::get_rules()[$component]))
					$data['rules'] = model_session::get_rules()[$component];
				self::verify_general_access($component);
			}
			$data['file_prefix'] = $component;
			$data['component'] = $controller::{$prefix.$method}();
			return $view::{$prefix.$method}($data);
		}catch(exception $e){
			return $e;
		}
	}
	/**
	* Верификация доступа к компоненту
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