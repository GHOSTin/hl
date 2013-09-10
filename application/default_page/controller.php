<?php
class controller_default_page{

	public static function private_show_default_page(){
		return true;
	}

  public static function public_show_default_page(model_request $request){
    if(!is_null($request->take_post('login'))
      AND !is_null($request->take_post('password'))){
      $model = new model_auth();
      $user = $model->get_user($request->take_post('login'),
                              $request->take_post('password'));
      var_dump($user);
      exit();
    }
    return true;
  }
}