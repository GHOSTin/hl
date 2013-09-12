<?php
class controller_profile{

	public static function private_get_dialog_edit_password(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_get_dialog_edit_cellphone(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_get_dialog_edit_telephone(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}
	
	public static function private_get_notification_center_content(model_request $request){
		return ['users' => model_user::get_users(new data_user())];
	}

	public static function private_show_default_page(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->get_user($id)];
	}

	public static function private_update_password(model_request $request){
		if($request->take_get('new_password') !== $request->take_get('confirm_password'))
			throw new e_model('Введеные новый пароль и его подтверждение не совпадают.');
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_password($id, $request->take_get('new_password'))];
	}

	public static function private_update_cellphone(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_cellphone($id, $request->take_get('cellphone'))];
	}

	public static function private_update_telephone(model_request $request){
		$id = model_session::get_user()->get_id();
		return ['user' => (new model_user)->update_telephone($id, $request->take_get('telephone'))];
	}
}