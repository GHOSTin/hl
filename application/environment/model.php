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
	/*
	* Функция возвращает содержимое страницы
	*/
	public static function get_page_content(){
		try{
			session_start();
			if($_SESSION['user'] instanceof data_current_user){
				self::create_batabase_connection();
				list($component, $prefix, $method) = self::build_router();
				model_session::set_user($_SESSION['user']);
			}elseif(!empty($_POST)){
				self::create_batabase_connection();
				$user = model_auth::get_login();
				if($user instanceof data_current_user){
					list($component, $prefix, $method) = self::build_router();
					model_session::set_user($user);
				}else{
					session_destroy();
					$component = 'auth';
					$prefix = 'public_';
					$method = 'show_auth_form';
				}
			}else{
				session_destroy();
				$component = 'auth';
				$prefix = 'public_';
				$method = 'show_auth_form';
			}
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			$c_data['component'] = $controller::{$prefix.$method}();
			//$c_data['rules'] = $_SESSION['rules'][$component];
			return $view::{$prefix.$method}($c_data);
		}catch(exception $e){
			return $e->getMessage();
		}
	}
}