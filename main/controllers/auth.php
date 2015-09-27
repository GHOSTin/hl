<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use domain\user;

class auth{

  public function login_form(Application $app){
    return $app['twig']->render('auth/form.tpl',
                                [
                                 'user' => null,
                                 'error' => null,
                                 'login' => null,
                                 'password' => null
                                ]);
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
    $user = $app['em']->getRepository('domain\user')->findOneByLogin($login);
    if(!is_null($user)){
      if($user->get_hash() === user::generate_hash($password, $app['salt'])){
        $app['session']->set('user', $user->get_id());
        $app['auth_log']->addInfo('Success login', $context);
        return new RedirectResponse('/');
      }else{
        $app['auth_log']->addWarning('Wrong password', $context);
        return $app['twig']->render('auth/form.tpl',
                                    [
                                     'user' => null,
                                     'error' => 'WRONG_PASSWORD',
                                     'login' => $login,
                                     'password' => null
                                    ]);
      }
    }else{
      $app['auth_log']->addWarning('Not found number', $context);
      return $app['twig']->render('auth/form.tpl',
                                  [
                                   'user' => null,
                                   'error' => 'USER_NOT_EXIST',
                                   'login' => $login,
                                   'password' => $password
                                  ]);
    }
  }

  public function logout(Application $app){
    $app['session']->invalidate();
    return new RedirectResponse('/enter/');
  }
}