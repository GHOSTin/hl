<?php
class model_environment{

	public static function get_page_content(){
		$component = 'controller_'.http_router::get_component_name();
		$method = http_router::get_method_name();
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			include ROOT.'/site/templates/default/header.php';
		if(self::get_auth_status()){
			if($component === 'controller_auth' AND $method === 'login'){
				header('Location:/');
				exit();
			}
			self::get_user_info();
			view_menu::build_horizontal_menu();
			$method = 'private_'.$method;
			$component::$method();
		}else{
			if($component === 'controller_auth' AND $method === 'login'){
				controller_auth::login();
			}else{
				view_auth::get_login_page();
			}
		}
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			include ROOT.'/site/templates/default/bottom.php';
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