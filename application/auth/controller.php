<?php
class controller_auth{
	
	public static function login(){
		$user_id = model_auth::get_login();
		if($user_id !== false){
			$_SESSION['user_id'] = $user_id;
			header('Location:/');
			exit();
		}else{
			return view_auth::get_login_page();	
		}
	}
}