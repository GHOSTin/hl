<?php
class controller_profile{

	public static function private_get_dialog_edit_password(){
		$user = $_SESSION['user'];
		model_user::verify_id($user);
		return ['users' => model_user::get_users($user)];
	}

	public static function private_get_dialog_edit_cellphone(){
		return ['user' => $_SESSION['user']];
	}

	public static function private_get_dialog_edit_telephone(){
		return ['user' => $_SESSION['user']];
	}
	
	public static function private_get_notification_center_content(){
		return ['users' => model_user::get_users(new data_user())];
	}

	public static function private_show_default_page(){
		$user = $_SESSION['user'];
		model_user::verify_id($user);
		return ['users' => model_user::get_users($user)];
	}

	public static function private_update_password(){
		if($_GET['new_password'] !== $_GET['confirm_password'])
			throw new e_model('Введеные новый пароль и его подтверждение не совпадают.');
		return ['user' => model_profile::update_password($_SESSION['user'], $_GET['new_password'])];
	}

	public static function private_update_cellphone(){
		return ['user' => model_profile::update_cellphone($_SESSION['user'], $_GET['cellphone'])];
	}

	public static function private_update_telephone(){
		return ['user' => model_profile::update_telephone($_SESSION['user'], $_GET['telephone'])];
	}
}