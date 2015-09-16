<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class query_type{

  public function color(Application $app, Request $request, $id){
    return $app['main\models\factory']->get_query_type_model($id)
                                      ->update_color($request->get('color'));
  }
}