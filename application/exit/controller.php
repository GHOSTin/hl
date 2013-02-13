<?php
class controller_exit{
	
	public static function private_show_default_page(){
		//unset($_SESSION['user_id']);
		//unset($_SESSION['id']);
		session_destroy();
		header('Location:/');
		exit();
	}
	
	public static function public_show_default_page(){
		self::private_show_default_page();
	}	
}