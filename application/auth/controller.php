<?php
class controller_auth{
	static $rules = ['general_access'];
	public static function public_login(){
		return view_auth::get_login_page();	
	}
	public static function private_login(){
		header('Location:/');
		exit();
	}	
}