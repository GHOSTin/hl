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

  public function get_query_type_model($id){
    return new query_type($this->app['em'], $this->app['user'], $id);
  }

  public function get_street_model($id){
    return new street($this->app['em'], $this->app['user'], $id);
  }

  public function get_outage_model($id){
    return new outage($this->app['twig'], $this->app['em'], $this->app['user'], $id);
  }

  public function get_workgroup_model($id){
    return new workgroup($this->app['twig'], $this->app['em'], $this->app['user'], $id);
  }
}