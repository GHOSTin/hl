<?php
class model_environment{

	public static function get_page_content(){

		require_once ROOT.'/libs/Twig/Autoloader.php';
		Twig_Autoloader::register();
		$c = http_router::get_component_name();
		$controller = 'controller_'.$c;
		$method = http_router::get_method_name();
		# header
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			print load_template('default_page.build_header', ['component' => $c]);
		# основной контент
		if(self::get_auth_status()){
			if($controller === 'controller_auth' AND $method === 'login'){
				header('Location:/');
				exit();
			}
			self::get_user_info();
			print view_menu::build_horizontal_menu();
			$method = 'private_'.$method;
			print $controller::$method();
		}else{
			if($controller === 'controller_auth' AND $method === 'login'){
				print controller_auth::login();
			}else{
				print view_auth::get_login_page();
			}
		}
		# bottom
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			print load_template('default_page.build_bottom', ['component' => $c]);
	}

	private static function get_auth_status(){
		session_start();
		$user_id = (int) $_SESSION['user_id'];
		return (empty($user_id))? false: true;
	}

	private static function get_user_info(){
		if(!isset($_SESSION['user'])){
			$sql = "SELECT `company_id`, `status`, `username`,
				`firstname`, `lastname`, `midlename`,
				`telephone`,`cellphone`
				FROM `users`
				WHERE `id` = ".$_SESSION['user_id'];
			if(db::pdo()->query($sql) !== false){
				if(db::pdo()->query($sql)->rowCount() !== 1)
					return false;
				$record = db::pdo()->query($sql)->fetch();
				$user = new data_user();
				$user->firstname = $record['firstname'];
				$user->lastname = $record['lastname'];
				$_SESSION['user'] = $user;
	 		}else{
	 			return false;
	 		}
	 	}
	}
}