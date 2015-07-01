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
    $number = $app['em']->getRepository('domain\number')->findOneByNumber($login);
    if(!is_null($number)){
      $hash = number::generate_hash($password, $app['salt']);
      if($number->get_hash() === $hash){
        $app['session']->set('number', $number->get_id());
        return new RedirectResponse('/');
      }
    }
    return $app['twig']->render('enter.tpl', ['number' => $app['number']]);
  }

  public function logout(Application $app){
    $app['session']->invalidate();
    return new RedirectResponse('/');
  }

  public function recovery(Application $app){
    return $app['twig']->render('recovery/default_page.tpl');
  }

  public function recovery_password(Request $request, Application $app){
    $num = trim($request->get('number'));
    $number = $app['em']->getRepository('domain\number')->findOneByNumber($num);
    if(is_null($number))
      return $app['twig']->render('recovery/not_found_number.tpl', ['number' => $num]);
    $email = $number->get_email();
    if(empty($email))
      return $app['twig']->render('recovery/email_not_exists.tpl', ['number' => $num]);
    $password = substr(sha1(time().$app['salt']), 0, 8);
    $number->set_hash(number::generate_hash($password, $app['salt']));
    $app['em']->flush();
    $body = $app['twig']->render('recovery\generate_password.tpl',
                                [
                                 'number' => $number,
                                 'password' => $password
                                ]);
    $message = $app['Swift_Message'];
    $message->setSubject('Востановление пароля')
            ->setFrom([$app['email_for_reply']])
            ->setTo([$email])
            ->setBody($body);
    $app['mailer']->send($message);
    return $app['twig']->render('recovery/success.tpl', ['number' => $app['number']]);
  }
}