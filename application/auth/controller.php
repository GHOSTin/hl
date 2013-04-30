<?php
class controller_auth{
	/*
	* Если пользователь не авторизован проводит процеду проверки логина и пароля.
	*/
	public static function public_login(){
		if(!($_SESSION['user'] instanceof data_user))
			return model_auth::get_login();
	}
	/*
	* Авторизованного пользователя перекидывает на главную страницу сайта.
	*/
	public static function private_login(){
		header('Location:/');
		exit();
	}
}