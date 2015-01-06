<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class registration{

  public function default_page(Application $app){
    return $app['twig']->render('registration/default_page.tpl', ['number' => $app['number']]);
  }

  public function send(Request $request, Application $app){
    $body[]  = 'Адресс:'.substr($request->get('address'), 0 , 255);
    $body[]  = 'л/с:'.substr($request->get('number'), 0, 255);
    $body[]  = 'ФИО:'.substr($request->get('fio'), 0, 255);
    $body[]  = 'Начисления:'.substr($request->get('accruals'), 0, 255);
    $body[]  = 'email:'.substr($request->get('email'), 0, 255);
    $body[]  = 'тел:'.substr($request->get('telephone'), 0, 255);
    $body[]  = 'сот:'.substr($request->get('cellphone'), 0, 255);
    $message = $app['Swift_Message'];
    $message->setSubject('Заявка на доступ')
            ->setFrom([$request->get('email')])
            ->setTo([$app['email_for_registration']])
            ->setBody(implode(PHP_EOL, $body));
    $app['mailer']->send($message);
    return $app['twig']->render('registration/send.tpl', ['number' => $app['number']]);
  }
}