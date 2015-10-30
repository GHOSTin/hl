<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class phrase{

  public function remove_dialog(Application $app, $id){
    $model = $app['main\models\factory']->get_phrase_model($id);
    return $model->remove_phrase_dialog();
  }

  public function remove(Application $app, Request $request){
    $model = $app['main\models\factory']->get_phrase_model($request->get('id'));
    return $model->remove();
  }
}