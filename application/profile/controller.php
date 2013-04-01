<?php 
class controller_profile{

	public static function private_show_default_page(){
		$user = new data_user();
		$user->id= $_SESSION['user']->id;
		return ['user' => model_user::get_users($user)];
	}
	
	public static function private_get_notification_center_content(){
		return ['users' => model_user::get_users(new data_user())];
	}
}