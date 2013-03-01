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
				$route = [http_router::get_component_name(),
				'private_'.http_router::get_method_name()];
			}else
				$route = ['auth', 'public_login'];
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
			list($component, $method) = self::build_router();
			$controller = 'controller_'.$component;
			$view = 'view_'.$component;
			self::load_twig();
			//self::get_user_profiles($controller);
			// проверяю если ли права доступа
			if($_SESSION['user'] instanceof data_user){
				$menu = view_menu::build_horizontal_menu();
			}
			$c_data = $controller::$method();
			try{
				$data = ['component' => $component, 'view' => $view::$method($c_data),
						'menu' => $menu];
			}catch(exception $e){
				// throw new exception('Проблема на этапе представления информации компонента.');
			}
			return self::build_page($data);
		}catch(exception $e){
			return $e->getMessage();
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