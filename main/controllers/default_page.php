<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \domain\user;
use \domain\session;

class default_page{

  public function about(Request $request, Application $app){
    return $app['twig']->render('about.tpl',
                  ['user' => $app['user'], 'menu' => null, 'hot_menu' => null]);
  }

  public function default_page(Request $request, Application $app){
    if(is_null($app['user']))
      return $app['twig']->render('enter.tpl', ['user' => $app['user']]);
    else
      return $app['twig']->render('default_page.tpl',
                  ['user' => $app['user'], 'menu' => null, 'hot_menu' => null]);
  }

  public function login(Request $request, Application $app){
    $login = $request->get('login');
    $password = $request->get('password');
    if(!is_null($login) AND !is_null($password)){
      $user = $app['em']->getRepository('\domain\user')->findOneByLogin($login);
      if(!is_null($user)){
        if($user->get_status() !== 'true')
          die('Вы заблокированы и не можете войти в систему.');
        $hash = user::generate_hash($password);
        if($user->get_hash() === $hash){
          $session = new session();
          $session->set_user($user);
          $session->set_ip($_SERVER['REMOTE_ADDR']);
          $session->set_time(time());
          $app['em']->persist($session);
          $app['em']->flush();
          $_SESSION['user'] = $user->get_id();
          return new RedirectResponse('/');
        }
      }
    }
    return $app['twig']->render('enter.tpl', ['user' => $app['user']]);
  }

  public function logout(Request $request, Application $app){
    session_destroy();
    return new RedirectResponse('/');
  }
}