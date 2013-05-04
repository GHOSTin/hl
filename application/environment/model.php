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
		if($_SESSION['user'] instanceof data_current_user){
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
	}
	/*
	* Функция возвращает содержимое страницы
	*/
	public static function get_page_content(){
		try{
			list($component, $prefix, $method) = self::create_session();
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			// self::verify_general_access();
			$c_data['component'] = $controller::{$prefix.$method}();
			//$c_data['rules'] = $_SESSION['rules'][$component];
			return $view::{$prefix.$method}($c_data);
		}catch(exception $e){
			return $e->getMessage();
		}
	}
	public static function verify_general_access(){
		if($method === 'show_default_page')
            $c_data['componentName'] = $component;
        $c_data['anonymous'] = true;
        // нужно вынести это кусок -->
        if($_SESSION['user'] instanceof data_user){
            model_profile::get_user_profiles();
            $access = (model_profile::check_general_access($controller, $component));
            if($access !== true){
                $controller = 'controller_error';
                $view = 'view_error';
                $prefix = 'private_';
                $method = 'get_access_denied_message';
            }
            model_menu::build_hot_menu($component, $controller);
            $c_data['menu'] = view_menu::build_horizontal_menu(['menu' => $_SESSION['menu'], 'hot_menu' => $_SESSION['hot_menu']]);
            $c_data['anonymous'] = false;
        }
	}
}