<?php
class controller_auth{
	/*
	* Если пользователь не авторизован проводит процеду проверки логина и пароля.
	*/
	public static function public_show_auth_form(){
		return true;
	}
}