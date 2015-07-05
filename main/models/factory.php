<?php namespace main\models;

use Silex\Application;

class factory{

  private $app;

  public function __construct(Application $app){
    $this->app = $app;
  }

  public function get_number_model($id){
    return new number5($this->app['twig'], $this->app['em'], $this->app['user'], $id);
  }
}