<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \domain\user;

class api{

  public function get_chat_options(Application $app){
    if(is_null($app['user']))
      return $app->json([], 400);
    else
      return $app->json(['user' => $app['user']->get_id(),
                         'host' => $app['chat_host'],
                         'port' => $app['chat_port']]);
  }

  public function get_users(Application $app){
    $users = $app['em']->getRepository('\domain\user')->findAll();
    return $app->json($users);
  }

  public function get_user_by_login_and_password(Request $request, Application $app){
    $user = $app['em']->getRepository('\domain\user')
                      ->findOneByLogin($request->get('login'));
    if(!is_null($user)){
      $hash = user::generate_hash($request->get('password'), $app['salt']);
      if($hash === $user->get_hash())
        return $app->json($user);
    }
    return $app->json(null, 400);
  }
}