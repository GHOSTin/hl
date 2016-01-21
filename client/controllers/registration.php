<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class registration{

  /**
   * Форма подачи запроса
   */
  public function registration_form(Application $app){
    return $app['client\models\registration']->registration_form();
  }

  /**
   * Обработка формы подачи запроса
   */
  public function process_registration_form(Request $request, Application $app){
    $address = trim(substr($request->get('address'), 0 , 255));
    $number = trim(substr($request->get('number'), 0, 255));
    $fio = trim(substr($request->get('fio'), 0, 255));
    $email= trim(substr($request->get('email'), 0, 255));
    $tellephone = trim(substr($request->get('telephone'), 0, 255));
    $cellphone = trim(substr($request->get('cellphone'), 0, 255));
    return $app['client\models\registration']->create_request($number, $fio, $address, $email, $tellephone, $cellphone);
  }
}