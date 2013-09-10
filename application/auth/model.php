<?php
class model_auth{
	
	/**
	* Возвращает пользователя для сессии data_cureent_user
	*/
	public function get_user($login, $password){
		$sql = new sql();
    	$sql->query("SELECT `id`, `company_id`, `status`, `username` as `login`,
					`firstname`, `lastname`, `midlename` as `middlename`,
					`password`, `telephone`, `cellphone`
					FROM `users` WHERE `username` = :login AND `password` = :hash");
		$sql->bind(':login', htmlspecialchars($login), PDO::PARAM_STR);
		$sql->bind(':hash', (new model_user)->get_password_hash($password) , PDO::PARAM_STR);
		$user = $sql->map(new data_current_user(), 'Проблема при авторизации.')[0];
		if($sql->count() !== 1){
			$sql->close();
			throw new e_model('Проблемы при авторизации.');
		}
		$sql->close();
		if($user->status !== 'true')
			die('Вы заблокированы и не можете войти в систему.');
		model_user::is_data_user($user);
		return $user;
	}
}