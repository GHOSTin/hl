<?php
class controller_default_page{

	public static function private_show_default_page(){
		return true;
	}

  public static function public_show_default_page(model_request $request){
    if(!is_null($request->take_post('login'))
      AND !is_null($request->take_post('password'))){
      $mapper = di::get('mapper_user');
      $user = $mapper->find_by_login_and_password(htmlspecialchars($request->take_post('login')),
                                              (new model_user)->get_password_hash($request->take_post('password')));
      if(!is_null($user)){
        if($user->get_status() !== 'true')
          die('Вы заблокированы и не можете войти в систему.');
        $company = di::get('mapper_company')->find($user->get_company_id());
        if(is_null($company))
          die('Нет такой компании.');
        $session = ['user' => $user, 'time' => time(),
          'ip' => $_SERVER['REMOTE_ADDR']];
        $ses = di::get('factory_session')->build($session);
        di::get('mapper_session')->insert($ses);
        $_SESSION['user'] = $user->get_id();
        $_SESSION['company'] = $company->get_id();
        setcookie("chat_host", application_configuration::chat_host, 0);
        setcookie("chat_port", application_configuration::chat_port, 0);
        setcookie("uid", $user->get_id(), 0);
        header('Location:/');
      }
    }
    return true;
  }
}