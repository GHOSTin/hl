<?php namespace main\controllers;

use Silex\Application;

class export{

  public function default_page(Application $app){

    return $app['twig']->render('export\default_page.tpl',
                                ['user' => $app['user'], 'menu' => null,
                                 'hot_menu' => null]);
  }

  public function export_numbers(Application $app){
    set_time_limit(0);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream; charset=utf-8');
    header( 'Content-Disposition: attachment;filename=export.csv');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $fp = tmpfile();
    $uri = stream_get_meta_data($fp)['uri'];
    fputcsv($fp, ['Улица', 'Дом', 'Квартира', 'Лицевой счет', 'ФИО'], ';');
    $numbers = $app['em']->getRepository('\domain\number')->findAll();
    foreach($numbers as $number){
      $value = [$number->get_flat()->get_house()->get_street()->get_name(),
                $number->get_flat()->get_house()->get_number(),
                $number->get_flat()->get_number(),
                $number->get_number(),
                $number->get_fio()];
      fputcsv($fp, $value, ';');
    }
    readfile($uri);
    fclose($fp);
    exit();
  }

  public function get_dialog_export_numbers(Application $app){
    return $app['twig']->render('export\get_dialog_export_numbers.tpl');
  }
}