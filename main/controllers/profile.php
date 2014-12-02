<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \domain\user;

class profile{

  public function default_page(Request $request, Application $app){
    return $app['twig']->render('profile\default_page.tpl',
                                ['user' => $app['user']]);
  }

  public function get_user_info(Request $request, Application $app){
    return $app['twig']->render('profile\get_userinfo.tpl',
                                ['user' => $app['user']]);
  }

  public function get_notification_center_content(Request $request,
                                                  Application $app){
    $users = $app['em']->getRepository('\domain\user')->findAll();
    return $app['twig']->render('profile\get_notification_center_content.tpl',
                                ['users' => $users]);
  }

  public function update_telephone(Request $request, Application $app){
    $user = $app['user'];
    $user->set_telephone($request->get('telephone'));
    $app['em']->flush();
    return $app['twig']->render('profile\get_userinfo.tpl',
                                ['user' => $app['user']]);
  }

  public function update_cellphone(Request $request, Application $app){
    $user = $app['user'];
    $user->set_cellphone($request->get('cellphone'));
    $app['em']->flush();
    return $app['twig']->render('profile\get_userinfo.tpl',
                                ['user' => $app['user']]);
  }

  public function update_password(Request $request, Application $app){
    $password = $request->get('new_password');
    $confirm = $request->get('confirm_password');
    if($password !== $confirm)
      throw new RuntimeException('Password problem.');
    $user = $app['user'];
    $user->set_hash(user::generate_hash($password, $app['salt']));
    $app['em']->flush();
    return $app['twig']->render('profile\get_userinfo.tpl',
                                ['user' => $app['user']]);
  }
}