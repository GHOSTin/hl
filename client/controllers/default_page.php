<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \domain\number;

class default_page{

  public function default_page(Application $app){
    if(is_null($app['number']))
      return $app['twig']->render('enter.tpl', ['number' => $app['number']]);
    else
      return $app['twig']->render('default_page.tpl', ['number' => $app['number']]);
  }

  public function login(Request $request, Application $app){
    $login = $request->get('login');
    $password = $request->get('password');
    if(!is_null($login) AND !is_null($password)){
      $number = $app['em']->getRepository('\domain\number')->findOneByNumber($login);
      if(!is_null($number)){
        $hash = number::generate_hash($password, $app['salt']);
        if($number->get_hash() === $hash){
          $_SESSION['number'] = $number->get_id();
          return new RedirectResponse('/');
        }
      }
    }
    return $app['twig']->render('enter.tpl', ['number' => $app['number']]);
  }

  public function logout(Application $app){
    session_destroy();
    return new RedirectResponse('/');
  }
}