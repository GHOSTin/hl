<?php
class model_auth{

	public static function get_login(){
		$login = htmlspecialchars($_POST['login']);
		$password = htmlspecialchars($_POST['password']);
		$sql = "SELECT `id`, `firstname` FROM `users`
			WHERE `username` = '".$login."'
			AND `password` = '".md5(md5($password).application_configuration::authSalt)."'";
		if(db::pdo()->query($sql) !== false){
			if(db::pdo()->query($sql)->rowCount() !== 1)
				return false;
			return db::pdo()->query($sql)->fetch()['id'];
 		}else{
 			return false;
 		}
	}
}