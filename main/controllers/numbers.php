<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \RuntimeException;
use \domain\number;

class numbers{

  public function accruals(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\accruals.tpl',
                                ['user' => $app['user'], 'number' => $number]);
  }

  public function contact_info(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\contact_info.tpl',
                                ['user' => $app['user'], 'number' => $number]);
  }
  public function edit_department(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('house_id'));
    $department = $app['em']->find('\domain\department',
                                   $request->get('department_id'));
    $house->set_department($department);
    $app['em']->flush();
    return $app['twig']->render('number\build_house_content.tpl',
                                ['house' => $house]);
  }

  public function default_page(Application $app){
    $streets = $app['em']->getRepository('\domain\street')
                         ->findBy([], ['name' => 'ASC']);
    return $app['twig']->render('number\default_page.tpl',
                                ['user' => $app['user'], 'streets' => $streets]);
  }

  public function get_dialog_edit_number(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number.tpl',
                                ['number' => $number]);
  }

  public function get_dialog_edit_number_email(Request $request,
                                               Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number_email.tpl',
                                ['number' => $number]);
  }

  public function get_dialog_edit_number_cellphone(Request $request,
                                                   Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number_cellphone.tpl',
                                ['number' => $number]);
  }

  public function get_dialog_edit_department(Request $request,
                                                   Application $app){
    $house = $app['em']->find('\domain\house', $request->get('house_id'));
    $departments = $app['em']->getRepository('\domain\department')->findAll();
    return $app['twig']->render('number\get_dialog_edit_department.tpl',
                                ['house' => $house,
                                 'departments' => $departments]);
  }

  public function get_dialog_edit_number_fio(Request $request,
                                             Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number_fio.tpl',
                                ['number' => $number]);
  }

  public function get_dialog_edit_number_telephone(Request $request,
                                                   Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number_telephone.tpl',
                                ['number' => $number]);
  }

  public function get_dialog_edit_password(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_password.tpl',
                                ['number' => $number]);
  }

  public function get_house_content(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('id'));
    return $app['twig']->render('number\get_house_content.tpl',
                                ['house' => $house]);
  }

  public function get_number_content(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_number_content.tpl',
                                ['number' => $number]);
  }

  public function get_street_content(Request $request, Application $app){
    $street_id = $request->get('id');
    $houses = $app['\main\models\number']->get_houses_by_street($street_id);
    return $app['twig']->render('number\get_street_content.tpl',
                                ['houses' => $houses,
                                 'street_id' => $street_id]);
  }

  public function query_of_house(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('id'));
    return $app['twig']->render('number\query_of_house.tpl',
                                ['user' => $app['user'], 'house' => $house]);
  }

  public function query_of_number(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\query_of_number.tpl',
                                ['user' => $app['user'], 'number' => $number]);
  }

  public function update_number(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $old_number = $app['em']->getRepository('\domain\number')
                            ->findOneByNumber($request->get('number'));
    if(!is_null($old_number))
      if($number->get_id() !== $old_number->get_id())
        throw new RuntimeException('Number exists.');
    $number->set_number($request->get('number'));
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }

  public function update_number_email(Request $request, Application $app){
    preg_match_all('/[0-9A-Za-z.@-]/', $request->get('email'), $matches);
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $number->set_email(implode('', $matches[0]));
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }

  public function update_number_cellphone(Request $request, Application $app){
    preg_match_all('/[0-9]/', $request->get('cellphone'), $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^[78]|', $cellphone))
      $cellphone = substr($cellphone, 1, 10);
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $number->set_cellphone($cellphone);
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }

  public function update_number_fio(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $number->set_fio($request->get('fio'));
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }

  public function update_number_password(Request $request, Application $app){
    $password = $request->get('password');
    $confirm = $request->get('confirm');
    if($password !== $confirm)
      throw new RuntimeException('Подтверждение и пароль не совпадают.');
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $hash = number::generate_hash($password, $app['salt']);
    $number->set_hash($hash);
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }

  public function update_number_telephone(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    $number->set_telephone($request->get('telephone'));
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                ['number' => $number]);
  }
}