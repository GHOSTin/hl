<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class workgroup{

  public function create_phrase(Application $app, Request $request, $id){
    $model = $app['main\models\factory']->get_workgroup_model($id);
    $model->create_phrase($request->get('phrase'));
    return $model->get_content();
  }

  public function create_phrase_dialog(Application $app, $id){
    $model = $app['main\models\factory']->get_workgroup_model($id);
    return $model->create_phrase_dialog();
  }

  public function phrases(Application $app, $id){
    $workgroup = $app['main\models\repository']->get_workgroup($id);
    return $app->json(['phrases' => $workgroup->get_phrases()->toArray()]);
  }
}