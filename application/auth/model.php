<?php
class model_auth{
	/**
	* При удачной авторизации в сессию добавляются данные о пользователе(класс data_user).
	*/
	public static function get_login(){
		$sql = "SELECT `id`, `company_id`, `status`, `username` as `login`,
				`firstname`, `lastname`, `midlename` as `middlename`,
				`password`, `telephone`, `cellphone`
				FROM `users` WHERE `username` = :login AND `password` = :hash";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':login', htmlspecialchars($_POST['login']), PDO::PARAM_STR);
		$stm->bindValue(':hash', model_user::get_password_hash($_POST['password']) , PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблемы при авторизации.');
		if($stm->rowCount() !== 1){
			$stm->closeCursor();
			throw new e_model('Проблемы при авторизации.');
		}
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_current_user');
		$user = $stm->fetch();
		$stm->closeCursor();
		return $user;
	}
	/**
	* Настройка кук
	*/
	private static function set_cockies(){
		setcookie("chat_host", application_configuration::chat_host, 0);
		setcookie("chat_port", application_configuration::chat_port, 0);
	}
    /*if($method === 'show_default_page')
            $c_data['componentName'] = $component;
        $c_data['anonymous'] = true;*/
        // нужно вынести это кусок -->
        /*if($_SESSION['user'] instanceof data_user){
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
        }*/
        // <--
}