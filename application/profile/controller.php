<?php
class controller_profile{

	public static function private_get_dialog_edit_password(
		model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_dialog_edit_cellphone(
		model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_dialog_edit_telephone(
		model_request $request){
		return ['user' => di::get('user')];
	}

  public static function private_get_userinfo(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_get_notification_center_content(
		model_request $request){
		return ['users' => di::get('em')->getRepository('data_user')->findAll()];
	}

	public static function private_show_default_page(model_request $request){
		return ['user' => di::get('user')];
	}

	public static function private_update_password(model_request $request){
		if($request->take_get('new_password')
			!== $request->take_get('confirm_password'))
			throw new RuntimeException('Password problem.');
		$user = di::get('user');
		$user->set_hash(data_user::generate_hash(
			$request->take_get('new_password')));
		di::get('em')->flush();
		return ['user' => $user];
	}

	public static function private_update_cellphone(model_request $request){
		$user = di::get('user');
		$user->set_cellphone($request->take_get('cellphone'));
		di::get('em')->flush();
		return ['user' => $user];
	}

	public static function private_update_telephone(model_request $request){
		$user = di::get('user');
		$user->set_telephone($request->take_get('telephone'));
		di::get('em')->flush();
		return ['user' => $user];
	}
}