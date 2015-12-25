<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class recovery{

  public function recovery_form(Application $app){
    return $app['client\models\recovery']->recovery_form();
  }

  public function recovery_password(Request $request, Application $app){
    $context = [
                'login' => $request->get('number'),
                'ip' => $request->server->get('REMOTE_ADDR'),
                'xff' => $request->headers->get('X-Forwarded-For'),
                'agent' => $request->server->get('HTTP_USER_AGENT')
               ];
    return $app['client\models\recovery']->recovery(trim($request->get('number')),
                                                    $app['salt'],
                                                    $app['Swift_Message'],
                                                    $app['mailer'],
                                                    $app['email_for_reply'],
                                                    $context,
                                                    $app['site_url']
                                                   );
  }
}