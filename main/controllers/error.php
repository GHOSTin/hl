<?php namespace main\controllers;

use \RuntimeException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class error{

  public function show_dialog(Request $request, Application $app){
    return $app['twig']->render('\error\show_dialog.tpl');
  }

  public function send_error(Request $request, Application $app){
    $time = time();
    if(!is_null($app['em']->find('\domain\error', $time)))
      throw new RuntimeException();
    $error = new \domain\error();
    $error->set_text($request->get('text'));
    $error->set_time($time);
    $error->set_user($app['user']);
    $app['em']->persist($error);
    $app['em']->flush();
    return new Response('');
  }

  public function delete_error(Request $request, Application $app){
    $error = $app['em']->find('\domain\error', $request->get('time'));
    if(is_null($error))
      throw new RuntimeException('Not entity');
    $app['em']->remove($error);
    $app['em']->flush();
    return new Response('');
  }

  public function default_page(Request $request, Application $app){
    $errors = $app['em']->getRepository('\domain\error')->findAll();
    return $app['twig']->render('\error\default_page.tpl',
                                ['user' => $app['user'], 'menu' => null,
                                'hot_menu' => null, 'errors' => $errors]);
  }
}