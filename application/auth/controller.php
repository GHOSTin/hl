 <?php
class controller_auth{

	public static function public_login(){
		if(!($_SESSION['user'] instanceof data_user))
			return model_auth::get_login();
	}
	
	public static function private_login(){
		header('Location:/');
		exit();
	}	
}