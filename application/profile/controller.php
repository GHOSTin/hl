<?php
class controller_profile{

	public static function private_get_dialog_edit_password(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_dialog_edit_cellphone(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_dialog_edit_telephone(model_request $request){
		return ['user' => di::get('user')];
	}

  public static function private_get_userinfo(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_notification_center_content(model_request $request){
		return ['users' => di::get('em')->getRepository('data_user')->findAll()];
	}

	public static function private_show_default_page(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_update_password(model_request $request){
		if($request->take_get('new_password') !== $request->take_get('confirm_password'))
			throw new RuntimeException('Введеные новый пароль и его подтверждение не совпадают.');
		return ['user' => (new model_user)->update_password(di::get('user')->get_id(), $request->take_get('new_password'))];
	}

	public static function private_update_cellphone(model_request $request){
		return ['user' => (new model_user)->update_cellphone(di::get('user')->get_id(), $request->take_get('cellphone'))];
	}

	public static function private_update_telephone(model_request $request){
		return ['user' => (new model_user)->update_telephone(di::get('user')->get_id(), $request->take_get('telephone'))];
	}
}