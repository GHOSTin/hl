<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class files{

  public function load(Request $request, Application $app){
    $response = $app['main\models\files']->load($request->files->get('file'));
    return $app->json($response);
  }
}