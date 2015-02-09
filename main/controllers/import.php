<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class import{

  public function default_page(Application $app){
    return $app['twig']->render('import\default_page.tpl', ['user' => $app['user']]);
  }
}