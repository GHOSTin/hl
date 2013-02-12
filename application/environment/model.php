<?php
class model_environment{
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
 			die('Fail database connection'); 
		}
		// устанавливаем параметры по умолчанию
		try{
			db::get_handler()->exec("SET NAMES utf8");
			db::get_handler()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			db::get_handler()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(exception $e){
			die('Fail set database properties'); 
		}
	}
	public static function get_page_content(){
		// twig include
		require_once ROOT.'/libs/Twig/Autoloader.php';
		Twig_Autoloader::register();
		// build router for controller
		$c = http_router::get_component_name();
		$controller = 'controller_'.$c;
		$method = http_router::get_method_name();
		// создаем соединение к базе данных
		self::create_batabase_connection();
		// check status session
		// и какието проверки
		if(self::get_auth_status()){
			if($controller === 'controller_auth' AND $method === 'login'){
				header('Location:/');
				exit();
			}
			self::get_user_info();
			$data = (empty($_SERVER['HTTP_X_REQUESTED_WITH']))?
					view_menu::build_horizontal_menu():
					'';
			$method = 'private_'.$method;
			$data .= $controller::$method();
		}else{
			if($controller === 'controller_auth' AND $method === 'login')
				$data = controller_auth::login();
				$data = view_auth::get_login_page();
		}
		$h = ['component' => $c, 'data' => $data];
		(empty($_SERVER['HTTP_X_REQUESTED_WITH']))?
			print load_template('default_page.main_page', $h):
			print $data;
	}
	/**
	* Проверяет залогинен ли пользователь
	* @return bolean
	*/
	private static function get_auth_status(){
		session_start();
		$user_id = (int) $_SESSION['user_id'];
		return (empty($user_id))? false: true;
	}
	/**
	* Запрашивает информацию о пользователе
	* @return void
	*/
	private static function get_user_info(){
		if(!isset($_SESSION['use2r'])){
			try{
				$user_id = (int) $_SESSION['user_id'];
				$sql = "SELECT `id`,`company_id`, `status`, `username` as `login`,
					`firstname`, `lastname`, `midlename` as `middlename`, `telephone`,`cellphone`
					FROM `users`
					WHERE `id` = :user_id";
				$stm = db::get_handler()->prepare($sql);
				$stm->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stm->execute();
				if($stm->rowCount() !== 1)
					throw new exception('user not exists');
				$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
				$user = $stm->fetch();
				$stm->closeCursor();
				$_SESSION['user'] = $user;
	 		}catch(exception $e){
	 			die('Fail user auth');
	 		}
	 	}
	}
}