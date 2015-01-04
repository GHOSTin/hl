<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \domain\number;
use Swift_Message;

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

  public function recovery(Application $app){
    return $app['twig']->render('recovery/default_page.tpl');
  }

  public function recovery_password(Request $request, Application $app){
    $number = $app['em']->getRepository('domain\number')->findOneByNumber($request->get('number'));
    if(is_null($number))
      return $app['twig']->render('recovery/not_found_number.tpl', ['number' => $request->get('number')]);
    $email = $number->get_email();
    if(empty($email))
      return $app['twig']->render('recovery/email_not_exists.tpl', ['number' => $request->get('number')]);
    $password = substr(number::generate_hash(time(), $app['salt']), 0, 8);
    $number->set_hash($password);
    $app['em']->flush();
    $message = $app['Swift_Message'];
    $message->setSubject('Востановление пароля')
            ->setFrom(['rcmp@mlsco.ru'])
            ->setTo([$email])
            ->setBody('Ваш новый пароль: '.$password);
    $app['mailer']->send($message);
    return $app['twig']->render('recovery/success.tpl', ['number' => $app['number']]);
  }
}