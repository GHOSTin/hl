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
    $date = DateTime::createFromFormat('H:i d.m.Y', '12:00 01.'.$request->get('date'));
    $time = $date->getTimeStamp();
    $no_numbers = [];
    $numbers = [];
    $hndl = fopen($request->files->get('accruals'), 'r');
    while($row = fgetcsv($hndl, 0, ';')){
      if(count($row) !== 12)
        throw new RuntimeException();
      $num = trim($row[0]);
      if(isset($numbers[$num])){
        $number = $numbers[$num];
      }else{
        $number = $app['em']->getRepository('domain\number')->findOneByNumber($num);
        $numbers[$num] = $number;
      }
      if(!is_null($number)){
        $accrual = new accrual();
        $accrual->set_number($number);
        $accrual->set_time($time);
        $accrual->set_service(trim($row[1]));
        $accrual->set_unit(trim($row[2]));
        $accrual->set_tarif(trim($row[3]));
        $accrual->set_ind(trim($row[4]));
        $accrual->set_odn(trim($row[5]));
        $accrual->set_sum_ind(trim($row[6]));
        $accrual->set_sum_odn(trim($row[7]));
        $accrual->set_sum_total(trim($row[8]));
        $accrual->set_facilities(trim($row[9]));
        $accrual->set_recalculation(trim($row[10]));
        $accrual->set_total(trim($row[11]));
        $app['em']->persist($accrual);
      }else
        $no_numbers[$num] = $num;
    }
    $app['em']->flush();
    fclose($hndl);
    return $app['twig']->render('import\load_accruals.tpl', ['numbers' => $no_numbers, 'user' => null]);
  }

  public function load_debt(Request $request, Application $app){
    set_time_limit(0);
    $hndl = fopen($request->files->get('debt'), 'r');
    while($row = fgetcsv($hndl, 0, ';')){
      $number = $app['em']->getRepository('domain\number')->findOneByNumber($row[0]);
      if(!is_null($number))
        $number->set_debt($row[1]);
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