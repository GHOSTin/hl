<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use domain\accrual;
use DateTime;

class import{

  public function default_page(Application $app){
    return $app['twig']->render('import\default_page.tpl', ['user' => $app['user']]);
  }

  public function load_accruals(Request $request, Application $app){
    set_time_limit(0);
    $hndl = fopen($request->files->get('accruals'), 'r');
    return $app['main\models\import_accruals']->load_accruals($hndl, $request->request->get('date'));
  }

  public function load_debt(Request $request, Application $app){
    set_time_limit(0);
    $hndl = fopen($request->files->get('debt'), 'r');
    while($row = fgetcsv($hndl, 0, ';')){
      $number = $app['em']->getRepository('domain\number')
                          ->findOneByNumber($row[0]);
      if(!is_null($number))
        $number->set_debt($row[1]);
      if(memory_get_usage() > 128*1024*1024){
        $app['em']->flush();
        $app['em']->clear();
      }
    }
    $app['em']->flush();
    fclose($hndl);
    return $app['twig']->render('import\load_debt.tpl', ['user' => $app['user']]);
  }

  public function load_flats(Application $app){
    set_time_limit(0);
    $app['main\models\import_numbers']->load_flats();
    $numbers = $app['main\models\import_numbers']->get_numbers();
    return $app['twig']->render('import\load_flats.tpl',
                                [
                                 'user' => $app['user'],
                                 'numbers' => $numbers
                                ]);
  }

  public function load_houses(Application $app){
    set_time_limit(0);
    $app['main\models\import_numbers']->load_houses();
    $flats = $app['main\models\import_numbers']->get_flats();
    return $app['twig']->render('import\load_houses.tpl',
                                [
                                 'user' => $app['user'],
                                 'flats' => $flats
                                ]);
  }

  public function load_meterages(Request $request, Application $app){
    set_time_limit(0);
    $hndl = fopen($request->files->get('metrs'), 'r');
    return $app['main\models\import_meterages']->load_meterages($hndl, $request->request->get('date'));
  }

  public function load_numbers(Application $app){
    set_time_limit(0);
    $app['main\models\import_numbers']->load_numbers();
    return $app['twig']->render('import\load_numbers.tpl', ['user' => $app['user']]);
  }

  public function load_fond_file(Request $request, Application $app){
    set_time_limit(0);
    $hndl = fopen($request->files->get('numbers'), 'r');
    $app['main\models\import_numbers']->load_file($hndl);
    $streets = $app['main\models\import_numbers']->get_streets();
    fclose($hndl);
    return $app['twig']->render('import\load_fond_file.tpl',
                                [
                                 'user' => $app['user'],
                                 'streets' => $streets
                                ]);
  }

  public function load_streets(Application $app){
    set_time_limit(0);
    $app['main\models\import_numbers']->load_streets();
    $houses = $app['main\models\import_numbers']->get_houses();
    return $app['twig']->render('import\load_streets.tpl',
                                [
                                 'user' => $app['user'],
                                 'houses' => $houses
                                ]);
  }
}