<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use domain\number;

class settings{

  public function change_password(Request $request, Application $app){
    $password = $request->get('new_password');
    $confirm = $request->get('confirm_password');
    if($password !== $confirm)
      return $app['twig']->render(
        'settings/change_password.tpl',
        ['number' => $app['number'], 'description' => 'Пароли не совпадают.', 'type' => 'error']
      );
    if($app['number']->get_hash() === number::generate_hash($request->get('old_password'), $app['salt'])){
      $new_hash = number::generate_hash($password, $app['salt']);
      $app['number']->set_hash($new_hash);
      $app['em']->flush();
      return $app['twig']->render(
        'settings/change_password.tpl',
        ['number' => $app['number'], 'description' => 'Пароль изменен.', 'type' => 'success']
      );
    }else{
      return $app['twig']->render(
        'settings/change_password.tpl',
        ['number' => $app['number'], 'description' => 'Старый пароль указан не верно.', 'type' => 'error']
      );
    }
  }

  public function default_page(Application $app){
    return $app['twig']->render('settings/default_page.tpl', ['number' => $app['number']]);
  }
}