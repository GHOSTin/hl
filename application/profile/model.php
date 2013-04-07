<?php
class model_profile{
	/**
	* Записывает в сессию правила, ограничения, настройки, меню.
	*/
	public static function get_user_profiles(){
		$sql = "SELECT `profile`, `rules`, `restrictions`, `settings`
				FROM `profiles` WHERE  `user_id` = :user_id
				AND `company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':user_id', $_SESSION['user']->id , PDO::PARAM_INT);
		$stm->bindValue(':company_id',$_SESSION['user']->company_id , PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при получении профиля.');
		if($stm->rowCount() > 0){
			while($profile = $stm->fetch()){
				$_SESSION['rules'][$profile['profile']] = json_decode($profile['rules']);
				$_SESSION['restrictions'][$profile['profile']] = json_decode($profile['restrictions']);
				$_SESSION['settings'][$profile['profile']] = json_decode($profile['settings']);
				if($_SESSION['rules'][$profile['profile']]->generalAccess === true){
					$c = 'controller_'.$profile['profile'];
					if($profile['profile'] === 'import'
						OR $profile['profile'] === 'materialgroup'
						OR $profile['profile'] === 'meter'
						OR $profile['profile'] === 'phrase'
						OR $profile['profile'] === 'system'
						OR $profile['profile'] === 'workgroup'
						){
					}else
						$links[] = ['href' => $profile['profile'], 'title' => $c::$name];
				}
				$_SESSION['menu'] = $links;
			}
		}
		$stm->closeCursor();
	}
	/**
	* Проверяет права доступа пользователя.
	* @return bolean
	*/
	public static function check_general_access($controller, $component){
		if(property_exists($controller, 'rules')){
			if($_SESSION['rules'][$component]->generalAccess !== true)
				return false;
			else
				return true;
		}else
			return true;
	}
	/**
	* Обновляет пароль пользователя
	* @return bolean
	*/
	public static function update_password(data_user $current_user, $new_password){
		$new_password = (string) $new_password;
		if(strlen($new_password) < 6)
			throw new e_model('Пароль не может быть длиной меньше 10 символов.');
		$current_user->password = model_user::get_password_hash($new_password);
		$sql = 'UPDATE `users` SET `password` = :password
				WHERE `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':password', $current_user->password, PDO::PARAM_STR);
		$stm->bindValue(':id', $current_user->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при изменении пароля.');
		return $current_user;
	}
}