<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;

class default_page{

  public function about(Application $app){
    return $app['twig']->render('about.tpl', ['user' => $app['user']]);
  }

  public function default_page(Application $app){
    if(is_null($app['user']))
      return new RedirectResponse('/enter/');
    else
      return $app['twig']->render('default_page.tpl', ['user' => $app['user']]);
  }
}