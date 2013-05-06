<?php
class model_auth{
	/**
	* Возвращает пользователя для сессии data_cureent_user
	*/
	public static function auth_user(){
		$sql = "SELECT `id`, `company_id`, `status`, `username` as `login`,
				`firstname`, `lastname`, `midlename` as `middlename`,
				`password`, `telephone`, `cellphone`
				FROM `users` WHERE `username` = :login AND `password` = :hash";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':login', htmlspecialchars($_POST['login']), PDO::PARAM_STR);
		$stm->bindValue(':hash', model_user::get_password_hash($_POST['password']) , PDO::PARAM_STR);
		stm_execute($stm, 'Проблемы при авторизации.');
		if($stm->rowCount() !== 1){
			$stm->closeCursor();
			throw new e_model('Проблемы при авторизации.');
		}
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_current_user');
		$user = $stm->fetch();
		$stm->closeCursor();
		return $user;
	}
}