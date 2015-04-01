<?php namespace main\controllers;

use Silex\Application;

class system{

  public function default_page(Application $app){
    return $app['twig']->render('system\default_page.tpl', ['user' => $app['user']]);
  }
}