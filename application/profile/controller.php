<?php
class controller_profile{

	public static function private_get_dialog_edit_password(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_get_dialog_edit_cellphone(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_get_dialog_edit_telephone(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}
	
	public static function private_get_notification_center_content(){
		return ['users' => model_user::get_users(new data_user())];
	}

	public static function private_show_default_page(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_update_password(){
		if($_GET['new_password'] !== $_GET['confirm_password'])
			throw new e_model('Введеные новый пароль и его подтверждение не совпадают.');
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_password($id, $_GET['new_password'])];
	}

	public static function private_update_cellphone(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_cellphone($id, $_GET['cellphone'])];
	}

	public static function private_update_telephone(){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_telephone($id, $_GET['telephone'])];
	}
}