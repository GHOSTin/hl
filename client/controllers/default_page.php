<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use domain\number;

class default_page{

  public function default_page(Application $app){
    if(is_null($app['number']))
      return $app['twig']->render('enter.tpl', ['number' => $app['number']]);
    else
      return $app['twig']->render('default_page.tpl', ['number' => $app['number']]);
  }

  public function login(Request $request, Application $app){
    $login = trim($request->get('login'));
    $password = trim($request->get('password'));
    $context = [
              'login' => $login,
              'ip' => $request->server->get('REMOTE_ADDR'),
              'xff' => $request->headers->get('X-Forwarded-For'),
              'agent' => $request->server->get('HTTP_USER_AGENT')
             ];
    $number = $app['em']->getRepository('domain\number')->findOneByNumber($login);
    if(!is_null($number)){
      $hash = number::generate_hash($password, $app['salt']);
      if($number->get_hash() === $hash){
        $app['session']->set('number', $number->get_id());
        $app['auth_log']->addInfo('Success login', $context);
        return new RedirectResponse('/');
      }else{
        $app['auth_log']->addWarning('Wrong password', $context);
      }
    }else{
      $app['auth_log']->addWarning('Not found number', $context);
    }
    return $app['twig']->render('enter.tpl', ['number' => $app['number']]);
  }

  public function logout(Application $app){
    $app['session']->invalidate();
    return new RedirectResponse('/');
  }
}