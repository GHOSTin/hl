<?php
class model_environment{
	public static function build_page($data){
		try{
			if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
				return load_template('default_page.main_page', $data);
				return $data['view'];
		}catch(exception $e){
			throw new exception('Ошибка при строительстве страницы.');
		}
	}
	/*
	* Строит роутер
	*/
	public static function build_router(){
		try{
			session_start();
			if($_SESSION['user'] instanceof data_user){
				$urlArray = parse_url($_SERVER['REQUEST_URI']);
				$url = explode('/', substr($urlArray['path'], 1));
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
			}else
				$route = ['auth', 'public_', 'login'];
			return $route;
		}catch(exception $e){
			throw new exception('Ошибка постройки маршрута.');
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
 			throw new exception('Нет соединения с базой данных.');
		}
		// устанавливаем параметры по умолчанию
		try{
			db::get_handler()->exec("SET NAMES utf8");
			db::get_handler()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			db::get_handler()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(exception $e){
			throw new exception('Аттрибуты соединения с базой данных не применились.'); 
		}
	}
	/*
	* Функция возвращает содержимое страницы
	*/
	public static function get_page_content(){
		try{
			self::create_batabase_connection();
			list($component, $prefix, $method) = self::build_router();
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			self::load_twig();
			if($_SESSION['user'] instanceof data_user){
				model_profile::get_user_profiles();
				$access = (model_profile::check_general_access($controller, $component));
				if($access !== true){
					$controller = 'controller_error';
					$view = 'view_error';
					$prefix = 'private_';
					$method = 'get_access_denied_message';
				}
				model_menu::get_hot_menu($component, $controller);
				$menu = view_menu::build_horizontal_menu(['menu' => $_SESSION['menu'], 'hot_menu' => $_SESSION['hot_menu']]);
			}
			$c_data = $controller::{$prefix.$method}();
			$data = ['component' => $component, 'view' => $view::{$prefix.$method}($c_data),
						'menu' => $menu];
			return self::build_page($data);
		}catch(exception $e){
			return $e->getMessage();
		}
	}
	/*
	* Регистрирует шаблонизатор
	*/
	public static function load_twig(){
		try{
			require_once ROOT.'/libs/Twig/Autoloader.php';
			Twig_Autoloader::register();
		}catch(exception $e){
			throw new exception('Шаблонизатор не может быть подгружен.');
		}
	}	
}