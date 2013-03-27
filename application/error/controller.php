<?php
class controller_error{

	public static function private_show_default_page(){
		die('Нет страницы.');
	}
	
	public static function private_get_access_denied_message(){
		return true;
	}
}