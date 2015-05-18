<?php namespace main\controllers;

use Silex\Application;

class number{

  public function generate_password(Application $app, $id){
    $model = $app['main\models\factory']->get_number_model($id);
    return $model->generate_password();
  }

  public function get_dialog_generate_password(Application $app, $id){
    $model = $app['main\models\factory']->get_number_model($id);
    return $model->get_dialog_generate_password();
  }
}