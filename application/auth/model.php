<?php
class model_auth{
	/**
	* Возвращает идентификатор пользователя в базе
	* @return bolean or int
	*/
	public static function get_login(){
		try{
			$login = htmlspecialchars($_POST['login']);
			$hash = md5(md5(htmlspecialchars($_POST['password'])).application_configuration::authSalt);
			$sql = "SELECT `id`, `firstname`
					FROM `users`
					WHERE `username` = :login AND `password` = :hash";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':login', $login, PDO::PARAM_STR, 255);
			$stm->bindParam(':hash', $hash , PDO::PARAM_STR, 255);
			$stm->execute();
			if($stm->rowCount() !== 1)
				throw new exception('user not exists');
			return $stm->fetch()['id'];
 		}catch(exception $e){
 			return false;
 		}
	}
}