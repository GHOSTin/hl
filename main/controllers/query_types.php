<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;

class query_types{

    public function create_query_type(Request $request, Application $app){
    $name = $request->get('name');
    $query_type = $app['em']->getRepository('domain\query_type')->findOneByName($name);
    if(!is_null($query_type))
      throw new RuntimeException('Такое событие существует.');
    $query_type = \domain\query_type::new_instance($name);
    $app['em']->persist($query_type);
    $app['em']->flush();
    $query_types = $app['em']->getRepository('domain\query_type')->findAll();
    return $app['twig']->render('system\query_types\query_types.tpl', ['query_types' => $query_types]);
  }

  public function default_page(Application $app){
    $query_types = $app['em']->getRepository('domain\query_type')->findAll();
    return $app['twig']->render('system\query_types\default_page.tpl',
                                [
                                 'query_types' => $query_types,
                                 'user' => $app['user']
                                ]);
  }

  public function get_dialog_create_query_type(Application $app){
    return $app['twig']->render('system\query_types\get_dialog_create_query_type.tpl');
  }
}