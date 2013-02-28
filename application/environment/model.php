<?php
class model_environment{
	public static function build_page($data){
		if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			return load_template('default_page.main_page', $data);
			return $data['view'];
	}
	/*
	* Строит роутер
	*/
	public static function build_router(){
		try{
			session_start();
			if($_SESSION['user'] instanceof data_user){
				$route = [http_router::get_component_name(),
				'private_'.http_router::get_method_name()];
			}else
				$route = ['auth', 'public_login'];
			return [true, $route];
		}catch(exception $e){
			return [false, 'Fail build router'];
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
 			return [false, 'Fail database connection']; 
		}
		// устанавливаем параметры по умолчанию
		try{
			db::get_handler()->exec("SET NAMES utf8");
			db::get_handler()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			db::get_handler()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(exception $e){
			return [false, 'Fail set database properties']; 
		}
		return [true];
	}
	/*
	* Функция возвращает содержимое страницы
	*/
	public static function get_page_content(){
		try{
			list($error, $code) = self::create_batabase_connection();
			if($error !== true)
				throw new exception($code);
			list($error, $code) = self::build_router();
			if($error !== true)
				throw new exception($code);
				list($component, $method) = $code;
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			list($error, $code) = self::load_twig();
			if($error !== true)
				throw new exception($code);
			//self::get_user_profiles($controller);
			// проверяю если ли права доступа
			if(true){
				if($_SESSION['user'] instanceof data_user){
					$menu = view_menu::build_horizontal_menu();
				}
				$c_data = $controller::$method();
				$data = ['component' => $component, 'view' => $view::$method($c_data),
						'menu' => $menu];
			}else{
				$data = ['component' => 'error', 'view' => 'Access Denied'];
			}
			return self::build_page($data);
		}catch(exception $e){
			die($e->getMessage());
		}
	}
	/**
	* Проверяет залогинен ли пользователь
	* @return bolean
	*/
	private static function get_user_profiles($controller){
		// потом проверяем соответсвет ли блок профиля с настройками по умолчанию
		//  если соответсвует то даем выполнятся запросу
		// иначе выводим ACCESS DENIED
		// нужно сделать запрос к базе данных
		// 	получить все профили
		// засунуть в сессию
		// Если не существет rules то проверку не делаем
		try{
			if(property_exists($controller, 'rules') AND ($_SESSION['user'] instanceof data_user)){
				$sql = "SELECT `company_id`, `user_id`, `profile`,
					`rules`, `restrictions`, `settings`
					FROM `profiles`
					WHERE  `user_id` = :user_id
					AND `company_id` = :company_id";
				$stm = db::get_handler()->prepare($sql);
				$stm->bindValue(':user_id',$_SESSION['user']->id , PDO::PARAM_INT);
				$stm->bindValue(':company_id',$_SESSION['user']->company_id , PDO::PARAM_INT);
				$stm->execute();
				//$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
				$query = $stm->fetch();
				var_dump($query);
				die('Profile');
				$stm->closeCursor();
				return $query;
			}
			//var_dump($controller::$rules);
		}catch(exception $e){
			die($e->getMessage());
			return false;
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
			return [false, 'Fail load template machine'];
		}
		return [true];
	}	
}