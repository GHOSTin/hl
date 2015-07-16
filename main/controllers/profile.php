<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class profile{

  public function default_page(Application $app){
    return $app['main\models\profile']->default_page();
  }

  public function get_user_info(Application $app){
    return $app['main\models\profile']->get_user_info();
  }

  public function update_cellphone(Request $request, Application $app){
    return $app['main\models\profile']->update_cellphone($request->get('cellphone'));
  }

  public function update_password(Request $request, Application $app){
    return $app['main\models\profile']->update_password(
                                        $request->get('new_password'),
                                        $request->get('confirm_password'),
                                        $app['salt']
                                      );
  }

  public function update_telephone(Request $request, Application $app){
    return $app['main\models\profile']->update_telephone($request->get('telephone'));
  }
}