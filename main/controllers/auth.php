<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use domain\user;
use domain\session;

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
    $login = $request->get('login');
    $password = $request->get('password');
    $user = $app['em']->getRepository('domain\user')->findOneByLogin($login);
    if(!is_null($user)){
      if($user->get_hash() === user::generate_hash($password, $app['salt'])){
        $session = session::new_instance($user, $request->server->get('REMOTE_ADDR'));
        $app['em']->persist($session);
        $app['em']->flush();
        $app['session']->set('user', $user->get_id());
        return new RedirectResponse('/');
      }else
        return $app['twig']->render('auth/form.tpl',
                                    [
                                     'user' => null,
                                     'error' => 'WRONG_PASSWORD',
                                     'login' => $login,
                                     'password' => null
                                    ]);
    }else
      return $app['twig']->render('auth/form.tpl',
                                  [
                                   'user' => null,
                                   'error' => 'USER_NOT_EXIST',
                                   'login' => $login,
                                   'password' => $password
                                  ]);
  }

  public function logout(Application $app){
    $app['session']->invalidate();
    return new RedirectResponse('/enter/');
  }
}