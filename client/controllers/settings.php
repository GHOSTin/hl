<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use domain\number;

class settings{

  public function cellphone_form(Application $app){
    return $app['twig']->render('settings/cellphone/form.tpl', ['number' => $app['number']]);
  }

  public function change_cellphone(Request $request, Application $app){
    preg_match_all('/[0-9]/', $request->get('cellphone'), $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^[78]|', $cellphone))
      $cellphone = substr($cellphone, 1, 10);
    $app['number']->set_cellphone($cellphone);
    $app['em']->flush();
    return $app['twig']->render('settings/cellphone/success.tpl', ['number' => $app['number']]);
  }

  public function change_email(Request $request, Application $app){
    $app['number']->set_email($request->get('email'));
    $app['em']->flush();
    return $app['twig']->render('settings/email/success.tpl', ['number' => $app['number']]);
  }

  public function change_password(Request $request, Application $app){
    $password = $request->get('new_password');
    $confirm = $request->get('confirm_password');
    if($password !== $confirm){
      $template = 'not_identical.tpl';
    }else{
      if($app['number']->get_hash() === number::generate_hash($request->get('old_password'), $app['salt'])){
        $new_hash = number::generate_hash($password, $app['salt']);
        $app['number']->set_hash($new_hash);
        $app['em']->flush();
        $template = 'success.tpl';
      }else{
        $template = 'wrong_old_password.tpl';
      }
    }
    return $app['twig']->render('settings/password/'.$template, ['number' => $app['number']]);
  }

  public function default_page(Application $app){
    return $app['twig']->render('settings/default_page.tpl', ['number' => $app['number']]);
  }

  public function email_form(Application $app){
    return $app['twig']->render('settings/email/form.tpl', ['number' => $app['number']]);
  }

  public function password_form(Application $app){
    return $app['twig']->render('settings/password/form.tpl', ['number' => $app['number']]);
  }
}