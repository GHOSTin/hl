<?php
class controller_default_page{

	public static function private_show_default_page(){
		return true;
	}

  public static function public_show_default_page(model_request $request){
    if(!is_null($request->take_post('login'))
      AND !is_null($request->take_post('password'))){
      $model = new mapper_user();
      $user = $model->find_by_login_and_password($request->take_post('login'),
                                              $request->take_post('password'));
      if(!is_null($user)){
        if($user->get_status() !== 'true')
          die('Вы заблокированы и не можете войти в систему.');
        $user->verify('company_id');
        $company = new data_company();
        $company->id = $user->company_id;
        $_SESSION['user'] = $user;
        $_SESSION['company'] = $company;
        header('Location:/');
      }
    }
    return true;
  }
}