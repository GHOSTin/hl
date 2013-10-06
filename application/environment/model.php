<?php
class model_environment{

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
			$data['file_prefix'] = $component;
			$data['component'] = $controller::$method($request);
			$data['request'] = $request;
			if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
			self::init_profiles();
			$data['menu'] = model_menu::build_menu($component);
			// if(isset(model_session::get_rules()[$component]))
			// 	$data['rules'] = model_session::get_rules()[$component];
			// model_session::set_session(new component_session_manager(new php_session_storage(), $component));
		}
			$template = ROOT.'/application/'.$component.'/templates/'.$method.'.tpl';
			if(file_exists($template)){
				return load_template($component.'.'.$method, $data);
			}else{
				return load_template('error.no_template', $data);
			}
		}catch(exception $e){
			die($e);
			// if($e instanceof e_model){
			// 	$args['error'] = $e;
   //      if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
   //          return load_template('error.show_ajax_error', $args);
   //      else
   //          return load_template('error.show_html_error', $args);
			// }elseif($e instanceof e_controller){
			// 	header("HTTP/1.0 404 Not Found");
			// 	return load_template('error.show_404_error', []);
			// }else{
			// 	$args['error'] = $e;
   //      if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
   //          return load_template('error.show_ajax_error', $args);
   //      else
   //          return load_template('error.show_html_error', $args);
			// }
		}
	}

	public static function init_profiles(){
		$profiles = (new model_user2profile(model_session::get_company(), model_session::get_user()))->get_profiles();
	}

	public static function before(){
		self::create_batabase_connection();
		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
			model_session::set_user($_SESSION['user']);
			model_session::set_company($_SESSION['company']);
			model_session::set_settings($_SESSION['settings']);
		}
	}
}