<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use domain\number;

class queries{

  public function default_page(Application $app){
    $queries = $app['number']->get_queries();
    $number_queries = $queries->filter(
      function($element){
        return $element->get_initiator() == 'number';
      }
    );
    return $app['twig']->render('queries/default_page.tpl', ['number' => $app['number'], 'queries' => $number_queries]);
  }
}