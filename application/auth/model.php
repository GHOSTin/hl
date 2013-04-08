<?php
class model_auth{
	/**
	* При удачной авторизации в сессию добавляются данные о пользователе(класс data_user).
	*/
	public static function get_login(){
		$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
				`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
				`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
				`users`.`cellphone`
				FROM `users`
				WHERE `username` = :login AND `password` = :hash";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':login', htmlspecialchars($_POST['login']), PDO::PARAM_STR, 255);
		$stm->bindParam(':hash', model_user::get_password_hash($_POST['password']) , PDO::PARAM_STR, 255);
		$stm->execute();
		if($stm->rowCount() !== 1){
			$stm->closeCursor();
			return false;
		}else{
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
			$_SESSION['user'] = $stm->fetch();
			setcookie("chat_host", application_configuration::chat_host, 0);
			setcookie("chat_port", application_configuration::chat_port, 0);
			$stm->closeCursor();
			return true;
		}
	}
}