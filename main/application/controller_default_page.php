<?php

use \boxxy\classes\di;

class controller_default_page{

	public static function private_show_default_page(){
		return null;
	}

  public static function public_show_default_page(model_request $request){
    $login = $request->take_post('login');
    $password = $request->take_post('password');
    if(!is_null($login) AND !is_null($password)){
      $em = di::get('em');
      $user = $em->getRepository('data_user')->findOneByLogin($login);
      if(!is_null($user)){
        if($user->get_status() !== 'true')
          die('Вы заблокированы и не можете войти в систему.');
        $hash = data_user::generate_hash($password);
        if($user->get_hash() === $hash){
          $session = new data_session();
          $session->set_user($user);
          $session->set_ip($_SERVER['REMOTE_ADDR']);
          $session->set_time(time());
          $em->persist($session);
          $em->flush();
          $_SESSION['user'] = $user->get_id();
          setcookie("chat_host", application_configuration::chat_host, 0);
          setcookie("chat_port", application_configuration::chat_port, 0);
          setcookie("uid", $user->get_id(), 0);
          header('Location:/');
        }
      }
    }
    return null;
  }
}