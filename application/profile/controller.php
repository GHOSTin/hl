<?php 
class controller_profile{
	public static function private_show_default_page(){
		$args['user_id'] = $_SESSION['user']->id;
		return ['user' => model_user::get_user($args)];
	}
	public static function private_get_notification_center_content(){
		return ['users' => model_user::get_users($args)];
	}
}