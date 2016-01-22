<?php namespace main\controllers;

use Silex\Application;

class registrations{

  /**
   * Выводит начальную страницу раздела одобрения запросов на доступ
   */
  public function default_page(Application $app){
    return $app['main\models\registrations']->default_page();
  }

  /**
   * Выводит json запросов которые не были еще обработаны
   */
  public function open(Application $app){
    $response = $app['main\models\registrations']->get_open_requests();
    return $app->json($response);
  }
}