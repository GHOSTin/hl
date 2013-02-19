<?php 
class controller_profile{
	public static function private_show_default_page(){
		$args['user_id'] = $_SESSION['user']->id;
		return model_user::get_user($args);
	}
}