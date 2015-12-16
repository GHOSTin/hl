<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;
use domain\number2event;
use DateTime;

class numbers{

  public function accruals(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\accruals.tpl',
                                [
                                  'user' => $app['user'],
                                  'number' => $number
                                ]);
  }

  public function contact_info(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\contact_info.tpl',
                                [
                                 'user' => $app['user'],
                                 'number' => $number
                                ]);
  }

  public function edit_department(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('house_id'));
    $department = $app['em']->find('\domain\department', $request->get('department_id'));
    $house->set_department($department);
    $app['em']->flush();
    return $app['twig']->render('number\build_house_content.tpl', ['house' => $house]);
  }

  public function default_page(Application $app){
    return $app['main\models\numbers']->default_page();
  }

  public function get_streets(Application $app){
    $response = $app['main\models\numbers']->streets();
    return $app->json($response);
  }

  public function get_dialog_edit_number(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    return $app['twig']->render('number\get_dialog_edit_number.tpl', ['number' => $number]);
  }

  public function get_dialog_edit_department(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('house_id'));
    $departments = $app['em']->getRepository('\domain\department')->findAll();
    return $app['twig']->render('number\get_dialog_edit_department.tpl',
                                [
                                 'house' => $house,
                                 'departments' => $departments
                                ]);
  }

  public function get_events(Request $request, Application $app){
    $workgroup = $app['em']->find('domain\workgroup', $request->get('id'));
    return $app['twig']->render('number\get_events.tpl', ['workgroup' => $workgroup]);
  }

  public function get_house_content(Application $app, $id){
    $response = $app['main\models\numbers']->get_house_content($id);
    return $app->json($response);
  }

  public function get_number_content(Application $app, $id){
    $response = $app['main\models\numbers']->get_number_content($id);
    return $app->json($response);
  }

  public function outages(Application $app, $id){
    return $app['main\models\numbers']->get_outages($id);
  }

  public function get_street_content(Application $app, $id){
    $street_model = $app['main\models\factory']->get_street_model($id);
    $response = $app['main\models\numbers']->get_street_content($street_model);
    return $app->json($response);
  }

  public function query_of_house(Application $app, $id){
    $house = $app['em']->find('domain\house', $id);
    return $app['twig']->render('number\query_of_house.tpl',
                                [
                                 'user' => $app['user'],
                                 'house' => $house
                                ]);
  }

  public function query_of_number(Request $request, Application $app){
    $number = $app['em']->find('domain\number', $request->get('id'));
    return $app['twig']->render('number\query_of_number.tpl',
                                [
                                 'user' => $app['user'],
                                 'number' => $number
                                ]);
  }

  public function update_number(Request $request, Application $app){
    $number = $app['em']->find('\domain\number', $request->get('id'));
    if(is_null($number))
      throw new RuntimeException();
    $old_number = $app['em']->getRepository('\domain\number')
                            ->findOneByNumber($request->get('number'));
    if(!is_null($old_number))
      if($number->get_id() !== $old_number->get_id())
        throw new RuntimeException('Number exists.');
    $number->set_number($request->get('number'));
    $app['em']->flush();
    return $app['twig']->render('number\update_number_fio.tpl',
                                [
                                 'number' => $number,
                                 'user' => $app['user']
                                ]);
  }
}