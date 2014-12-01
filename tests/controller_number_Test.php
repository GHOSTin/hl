<?php

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \main\controllers\numbers as controller;
use \domain\house;
use \domain\department;
use \domain\street;
use \domain\user;
use \main\models\number;

class controller_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_edit_department(){
    $house = new house();
    $department = new department();
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive([$this->equalTo('\domain\house')],
                                      [$this->equalTo('\domain\department')])
                    ->will($this->onConsecutiveCalls($house, $department));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\build_house_content.tpl',
                             ['house' => $house])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->edit_department($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->will($this->returnValue('street_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->will($this->returnValue($repository));
    $this->app['user'] = new user();
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\default_page.tpl',
                             ['user' => $this->app['user'],
                              'streets' => 'street_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_street_content(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('\main\models\number')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_houses_by_street')
          ->with(125)
          ->will($this->returnValue('house_array'));
    $this->app['\main\models\number'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_street_content.tpl',
                             ['houses' => 'house_array',
                              'street_id' => 125])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_street_content($this->request,
                                                      $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_department(){
    $this->request->query->set('house_id', 125);
    $this->app['em']->expects($this->once())
      ->method('find')
      ->with('\domain\house', 125)
      ->will($this->returnValue('house_object'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
      ->disableOriginalConstructor()
      ->getMock();
    $repository->expects($this->once())
      ->method('findAll')
      ->will($this->returnValue('departments_array'));
    $this->app['em']->expects($this->once())
      ->method('getRepository')
      ->with('\domain\department')
      ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_department.tpl',
                             ['house' => 'house_object',
                              'departments' => 'departments_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_department($this->request,
                                                              $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_house_content(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\house', 125)
                    ->will($this->returnValue('house_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_house_content.tpl',
                             ['house' => 'house_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_house_content($this->request,
                                                     $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_query_of_house(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\house', 125)
                    ->will($this->returnValue('house_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\query_of_house.tpl',
                             ['user' => 'user_object',
                              'house' => 'house_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->query_of_house($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_query_of_number(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\query_of_number.tpl',
                             ['user' => 'user_object',
                              'number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->query_of_number($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_number_content(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_number_content.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_number_content($this->request,
                                                      $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_contact_info(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\contact_info.tpl',
                             ['number' => 'number_object',
                              'user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->contact_info($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number($this->request,
                                                          $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_accruals(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\accruals.tpl',
                             ['number' => 'number_object',
                              'user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->accruals($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number_fio(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number_fio.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number_fio($this->request,
                                                              $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_password(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_password.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_password($this->request,
                                                            $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number_cellphone(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number_cellphone.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number_cellphone(
                                                    $this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number_telephone(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number_telephone.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number_telephone(
                                                    $this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number_email(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number_email.tpl',
                             ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number_email(
                                                    $this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_email(){
    $this->request->query->set('email', 'nekrasov@mlsco.ru');
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_email')
           ->with('nekrasov@mlsco.ru');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number_email($this->request,
                                                       $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_telephone(){
    $this->request->query->set('telephone', '647957');
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_telephone')
           ->with('647957');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number_telephone($this->request,
                                                           $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_cellphone(){
    $this->request->query->set('cellphone', '+79222944742');
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_cellphone')
           ->with('9222944742');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number_cellphone($this->request,
                                                           $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_fio(){
    $this->request->query->set('fio', 'Некрасов Евгений');
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_fio')
           ->with('Некрасов Евгений');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number_fio($this->request,
                                                     $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_password_1(){
    $this->request->query->set('password', 'Aa12345678');
    $this->request->query->set('confirm', 'Aa12345678');
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_hash');
    $this->app['salt'] = 'salt';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number_password($this->request,
                                                          $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_password_2(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('password', 'Aa12345678');
    $this->request->query->set('confirm', 'Aa123456');
    $response = $this->controller->update_number_password($this->request,
                                                          $this->app);
  }
}