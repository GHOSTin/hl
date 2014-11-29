<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \main\conf;

class api{

  public function get_chat_options(Request $request, Application $app){
    return $app['twig']->render('\api\get_chat_options.tpl',
                              ['user' => $app['user'],
                               'host' => $app['chat_host'],
                               'port' => $app['chat_port']]);
  }
}