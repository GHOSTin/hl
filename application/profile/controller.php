<?php
class controller_profile{

	public static function private_get_dialog_edit_password(){
		$user = new data_user();
		$user->id = model_session::get_user()->id;
		return ['user' => model_user::get_users($user)[0]];
	}

	public static function private_get_dialog_edit_cellphone(){
		$user = new data_user();
		$user->id = model_session::get_user()->id;
		return ['user' => model_user::get_users($user)[0]];
	}

	public static function private_get_dialog_edit_telephone(){
		$user = new data_user();
		$user->id = model_session::get_user()->id;
		return ['user' => model_user::get_users($user)[0]];
	}
	
	public static function private_get_notification_center_content(){
		return ['users' => model_user::get_users(new data_user())];
	}

	public static function private_show_default_page(){
		$user = new data_user();
		$user->id = model_session::get_user()->id;
		return ['user' => model_user::get_users($user)[0]];
	}

	public static function private_update_password(){
		if($_GET['new_password'] !== $_GET['confirm_password'])
			throw new e_model('Введеные новый пароль и его подтверждение не совпадают.');
		return ['user' => model_profile::update_password(model_session::get_user(), $_GET['new_password'])];
	}

	public static function private_update_cellphone(){
		return ['user' => model_profile::update_cellphone(model_session::get_user(), $_GET['cellphone'])];
	}

	public static function private_update_telephone(){
		return ['user' => model_profile::update_telephone(model_session::get_user(), $_GET['telephone'])];
	}
}