<?php
class controller_exit{

	public static function private_show_default_page(){
		session_destroy();
		header('Location:/');
		exit();
	}
}