<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class report_outages{

  public function default_page(Application $app){
    return $app['main\models\report_outages']->default_page();
  }

  public function html(Application $app){
    return $app['main\models\report_outages']->html();
  }
}