<?php namespace client\controllers;

use Silex\Application;

class accruals{

  public function default_page(Application $app){
    return $app['twig']->render('accruals/default_page.tpl', [
                                                               'number' => $app['number'],
                                                               'columns' => $app['accrual_columns']
                                                              ]);
  }
}