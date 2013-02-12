<?php
class model_environment{

	public static function create_batabase_connection(){
		try{
			db::connect(application_configuration::database_host,
						application_configuration::database_name,
						application_configuration::database_user,
						application_configuration::database_password,
						[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
		}catch(exception $e){
 			die('Fail database connection'); 
		}
		# устанавливаем свойства соединения
		try{
			db::get_handler()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			db::get_handler()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(exception $e){
			die('Fail set database properties'); 
		}
	}

	public static function get_page_content(){
		# twig include
		require_once ROOT.'/libs/Twig/Autoloader.php';
		Twig_Autoloader::register();
		# build router for controller
		$c = http_router::get_component_name();
		$controller = 'controller_'.$c;
		$method = http_router::get_method_name();
		# создаем соединение к базе данных
		self::create_batabase_connection();
		# check status session
		# и какието проверки
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
		if(!isset($_SESSION['user'])){
			try{
				$user_id = (int) $_SESSION['user_id'];
				$sql = "SELECT `id`,`company_id`, `status`, `username`,
					`firstname`, `lastname`, `midlename`, `telephone`,`cellphone`
					FROM `users`
					WHERE `id` = :user_id";
				$stm = db::get_handler()->prepare($sql);
				$stm->bindParam(':user_id', $user_id);
				$stm->execute();
				if($stm->rowCount() !== 1)
					throw new exception('user not exists');
				$record = $stm->fetch();
				$user = new data_user();
				$user->id = $record['id'];
				$user->firstname = $record['firstname'];
				$user->lastname = $record['lastname'];
				$_SESSION['user'] = $user;
	 		}catch(exception $e){
	 			die('Fail user auth');
	 		}
	 	}
	}
}