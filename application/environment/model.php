<?php
class model_environment{

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
			if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
				$data['user'] = $_SESSION['user'];
				self::init_profile($component);
				$data['menu'] = model_menu::build_menu($component);
			}
			$data['file_prefix'] = $component;
			$data['component'] = $controller::$method($request);
			$data['request'] = $request;
			$data['version'] = file_get_contents(ROOT.'/version');
			$template = ROOT.'/application/'.$component.'/templates/'.$method.'.tpl';
			if(file_exists($template))
				return load_template($component.'.'.$method, $data);
			else
				return load_template('error.no_template', $data);
		}catch(exception $e){
			if($e instanceof e_model){
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

	public static function init_profile($component){
		$profile = (new mapper_user2profile(model_session::get_company(),
			model_session::get_user()))->find($component);
		if($profile instanceof data_profile)
			model_session::set_profile($profile);
		else
			if(!in_array($component, ['default_page', 'profile', 'exit',
				'processing_center', 'import', 'user', 'meter', 'error', 'company'], true))
				throw new e_model('Нет доступа.');
	}

	public static function before(){
		if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_user){
			model_session::set_user($_SESSION['user']);
			model_session::set_company($_SESSION['company']);
		}
	}
}